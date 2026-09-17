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
                'field' => 'created',
                'old_value' => null,
                'new_value' => 'Задача создана',
            ]));

            return;
        }

        $oldAttributes = $task->getOriginal();
        $newAttributes = $task->getAttributes();

        if (isset($newAttributes['deadline'])) {
            $newAttributes['deadline'] = Carbon::parse($newAttributes['deadline'])->format('Y-m-d H:i:s');
        }

        $changedFields = array_keys(array_diff_assoc($newAttributes, $oldAttributes));

        foreach ($changedFields as $field) {
            if (in_array($field, ['created_at', 'updated_at'])) {
                continue;
            }

            dispatch(new LogTaskJob([
                'task_id' => $task->id,
                'user_id' => Auth::id() ?? $task->user_id,
                'field' => $field,
                'old_value' => $oldAttributes[$field] ?? null,
                'new_value' => $newAttributes[$field] ?? null,
            ]));
        }
    }
}
