<?php

namespace App\Listeners;

use App\Events\TaskCreated;
use App\Events\TaskUpdated;
use App\Jobs\LogTaskJob;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;

class LogTaskChange
{
    use InteractsWithQueue;

    public function handle(TaskCreated|TaskUpdated $event): void
    {

        $task = $event->task;

        if ($event instanceof TaskCreated) {
            dispatch(new LogTaskJob([
                'task_id' => $task->id,
                'user_id' => Auth::id() ?? $task->user_id,
                'event' => 'created',
                'changes' => $task->getAttributes(),
            ]));

            return;
        }

        $oldAttributes = $task->getOriginal();
        $newAttributes = $task->getAttributes();

        $changes = [];

        foreach ($newAttributes as $field => $newValue) {

            if (in_array($field, ['created_at', 'updated_at', 'deleted_at'])) {
                continue;
            }

            $oldValue = $oldAttributes[$field] ?? null;

            if ($field == 'deadline') {
                $oldValue = $oldValue ? Carbon::parse($oldValue)->format('Y-m-d H:i:s') : null;
                $newValue = $newValue ? Carbon::parse($newValue)->format('Y-m-d H:i:s') : null;
            }

            if ($oldValue != $newValue) {
                $changes[$field] = [
                    'old' => $oldValue,
                    'new' => $newValue,
                ];
            }
        }

        if (empty($changes)) {
            return;
        }

        dispatch(new LogTaskJob([
            'task_id' => $task->id,
            'user_id' => Auth::id() ?? $task->user_id,
            'event' => 'updated',
            'changes' => $changes,
        ]));
    }
}
