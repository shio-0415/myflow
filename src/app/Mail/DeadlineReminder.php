<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Collection;

class DeadlineReminder extends Mailable
{
    public function __construct(public User $user, public Collection $tasks)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '【MyFlow】明日が期限のタスクが' . $this->tasks->count() . '件あります',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.deadline-reminder');
    }
}
