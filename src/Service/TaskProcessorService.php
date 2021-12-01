<?php

namespace App\Service;

use App\Message\TaskMessage;
use App\Repository\TaskRepository;

class TaskProcessorService
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
    )
    {
        $this->taskRepository = $taskRepository;
        $this->endpoint = $endpoint;
        $this->photoStorage = $photoStorage;
    }

    public function processTask(TaskMessage $taskMessage):void
    {

    }

}
