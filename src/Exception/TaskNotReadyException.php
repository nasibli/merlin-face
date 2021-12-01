<?php

namespace App\Exception;

use App\Entity\Task;

class TaskNotReadyException extends \Exception
{
    public function __construct(Task $task)
    {
        parent::__construct('Task ' . $task->getId() . ' is not ready');
    }
}
