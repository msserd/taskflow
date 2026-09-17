<?php

namespace App\Jobs;

use App\Models\TaskLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class LogTaskJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public array $data
    ) {}

    public function handle(): void
    {
        if (!$this->data) {
            return;
        }

        TaskLog::create($this->data);
    }
}
