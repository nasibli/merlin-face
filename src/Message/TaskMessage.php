<?php

namespace App\Message;

use App\Entity\Task;

class TaskMessage
{
    /**
     * @var Task
     */
    private $task;

    private $retryId = null;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function getTask(): Task
    {
        return $this->task;
    }

    public function getRetryId(): ?int
    {
        return $this->retryId;
    }

    public function setRetryId(int $retryId)
    {
        $this->retryId = $retryId;
    }

}
