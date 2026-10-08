<?php

namespace App\Observers;

use App\Models\Task;

class TaskObserver
{
    public function created(Task $task): void
    {
        $this->clearCache($task);
    }

    public function updated(Task $task): void
    {
        $this->clearCache($task);
    }

    public function deleted(Task $task): void
    {
        $this->clearCache($task);
    }

    protected function clearCache(Task $task): void
    {
        $userIds = array_filter(array_unique([
            $task->user_id,
            $task->assigned_to,
            $task->getOriginal('assigned_to'),
        ]));

        Task::clearCache($userIds);
    }
}
