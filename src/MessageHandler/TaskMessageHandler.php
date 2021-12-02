<?php

namespace App\MessageHandler;

use App\Entity\Task;
use App\Exception\TaskNotReadyException;
use App\Message\TaskMessage;
use App\Repository\TaskRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class TaskMessageHandler implements MessageHandlerInterface
{
    /**
     * @var TaskRepository
     */
    private $taskRepository;
    /**
     * @var string
     */
    private $endpoint;
    /**
     * @var string
     */
    private $photoStorage;

    public function __construct(
        TaskRepository $taskRepository,
        string $endpoint,
        string $photoStorage
    ) {
        $this->taskRepository = $taskRepository;
        $this->endpoint = $endpoint;
        $this->photoStorage = $photoStorage;
    }

    private function processTask(TaskMessage $taskMessage)
    {
        $result = '';
        while(!is_object($result) || $result->status === 'error') {
            $result = $this->sendRequest($taskMessage);
        }

        $task = $this->taskRepository->find($taskMessage->getTask()->getId());
        if ($result->retry_id === null) {
            $task->setResult($result->result);
            $task->setStatus(Task::STATUS_READY);
            $this->taskRepository->save($task);
        } else {
            $taskMessage->setRetryId($result->retry_id);
            $task->setStatus(Task::STATUS_WAIT);
            $this->taskRepository->save($task);
            throw new TaskNotReadyException($task);
        }
    }

    private function sendRequest(TaskMessage $taskMessage)
    {
        $task = $taskMessage->getTask();

        $fields = null;
        if ($taskMessage->getRetryId() === null) {
            $fields = [
                'name'  => $task->getUserName(),
                'photo' => curl_file_create (
                    $this->photoStorage . '/' . $task->getUserPhoto() . '.' . $task->getUserPhotoExtension(),
                    'image/' . $task->getUserPhotoExtension(),
                    'image.' . $task->getUserPhotoExtension()
                ),
            ];
        } else {
            $fields = ['retry_id', $taskMessage->getRetryId()];
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->endpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: multipart/form-data']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, false);
    }

    /**
     * @param TaskMessage $taskMessage
     *
     * @throws TaskNotReadyException
     */
    public function __invoke(TaskMessage $taskMessage)
    {
        $this->processTask($taskMessage);
    }
}
