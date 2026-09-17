<?php

namespace App\Listeners;

use App\Events\TaskCreated;
use App\Events\TaskUpdated;
use App\Jobs\SendEmailJob;

class SendTaskAssignedNotification
{
    public function handle(TaskCreated|TaskUpdated $event): void
    {
        $task = $event->task;

        if (!$task->assigned_to) {
            return;
        }

        $assignedUser = $task->assignedUser;

        if (!$assignedUser || !$assignedUser->email) {
            return;
        }

        $subject = 'Вам назначена задача';
        $body = "Задача: {$task->title}";
        if ($task->description) {
            $body .= "\nОписание: {$task->description}";
        }

        if ($event instanceof TaskCreated) {

            dispatch(new SendEmailJob(
                $assignedUser->email,
                $subject,
                $body
            ));

            return;
        }

        if ($event instanceof TaskUpdated) {
            $oldAttributes = $task->getOriginal();
            if (isset($oldAttributes['assigned_to']) && $oldAttributes['assigned_to'] == $task->assigned_to) {
                return;
            }

            dispatch(new SendEmailJob(
                $assignedUser->email,
                $subject,
                $body
            ));
        }

    }
}
