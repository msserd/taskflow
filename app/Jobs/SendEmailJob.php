<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $email,
        public string $subject,
        public string $body
    ) {}

    public function handle(): void
    {
        if (!$this->email) {
            return;
        }

        Mail::raw($this->body, function ($message) {
            $message->to($this->email)->subject($this->subject);
        });
    }
}
