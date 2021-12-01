<?php

namespace App\Service;

use App\Entity\Task;
use App\Form\TaskFormType;
use App\Exception\FormValidationException;
use App\Message\TaskMessage;
use App\Repository\TaskRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;

class TaskService
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var TaskRepository
     */
    private $taskRepository;
    /**
     * @var MessageBusInterface
     */
    private $messageBus;
    /**
     * @var string
     */
    private $photoStorage;

    public function __construct(
        FormFactoryInterface $formFactory,
        TaskRepository $taskRepository,
        MessageBusInterface $messageBus,
        string $photoStorage)
    {

        $this->formFactory = $formFactory;
        $this->taskRepository = $taskRepository;
        $this->messageBus = $messageBus;
        $this->photoStorage = $photoStorage;
    }

    public function create(Request $request, bool &$taskPresent): task
    {
        $task = new Task();

        // Не разобрался, почему форма не видит файл, так пока сделал (возможно форма внутри "имя формы ищет")
        $data = $request->request->all();
        if ($request->files->has('userPhoto')) {
            $data['userPhoto'] = $request->files->get('userPhoto');
        }

        $form = $this->formFactory->create(TaskFormType::class, $task);
        $form->submit($data);


        if (!$form->isValid()) {
            throw new FormValidationException($form);
        }

        /** @var UploadedFile $userPhoto */
        $userPhoto = $form['userPhoto']->getData();

        $userPhotoHash = md5_file($userPhoto->getRealPath());

        $existingTask = $this->taskRepository->findOneBy(['userPhoto' => $userPhotoHash]);
        if ($existingTask) {
            $taskPresent = true;
            return $existingTask;
        } else {
            $task
                ->setUserPhoto($userPhotoHash)
                ->setUserPhotoExtension($userPhoto->getClientOriginalExtension())
                ->setStatus(Task::STATUS_RECEIVED);
            $this->taskRepository->save($task);
            $userPhoto->move($this->photoStorage, $userPhotoHash . '.' . $userPhoto->getClientOriginalExtension());

            $this->messageBus->dispatch(new TaskMessage($task));

            return $task;
        }
    }
}
