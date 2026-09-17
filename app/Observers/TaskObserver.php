<?php

namespace App\Observers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
            Auth::id(),
            $task->assigned_to,
            $task->user_id,
            ...User::where('role', 'admin')->pluck('id')->toArray(),
        ]));

        Task::clearCache($userIds);
    }
}
