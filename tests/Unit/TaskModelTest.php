<?php

namespace Tests\Unit;

use App\Models\Task;
use PHPUnit\Framework\TestCase;

class TaskModelTest extends TestCase
{
    public function test_status_label_returns_correct_value_for_pending()
    {
        $task = new Task(['status' => 'pending']);
        $this->assertEquals('В ожидании', $task->status_label);
    }

    public function test_status_label_returns_correct_value_for_in_progress()
    {
        $task = new Task(['status' => 'in_progress']);
        $this->assertEquals('В работе', $task->status_label);
    }

    public function test_task_is_overdue_when_deadline_in_past_and_not_completed()
    {
        $task = new Task([
            'deadline' => now()->subDay(),
            'status' => 'pending',
        ]);

        $this->assertTrue($task->isOverdue());
    }

    public function test_task_is_not_overdue_when_completed()
    {
        $task = new Task([
            'deadline' => now()->subDay(),
            'status' => 'completed',
        ]);

        $this->assertFalse($task->isOverdue());
    }

    public function test_task_is_not_overdue_when_no_deadline()
    {
        $task = new Task(['status' => 'pending']);
        $this->assertFalse($task->isOverdue());
    }
}
