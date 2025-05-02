<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BulkMail extends Mailable
{
    use Queueable, SerializesModels;

    public $body;
    public $subjectLine;

    public function __construct($subjectLine, $body)
    {
        $this->subjectLine = $subjectLine;
        $this->body        = $body;
    }

    public function build()
    {
        $user = auth()->user();

        return $this
            ->from(config('mail.from.address'), $user->name)
            ->replyTo($user->email, $user->name)             
            ->subject($this->subjectLine)
            ->markdown('emails.templates.bulk')
            ->with(['body' => $this->body]);
    }
}
