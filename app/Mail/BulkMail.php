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
    protected $attachmentPath;

    /**
     * @param string      $subjectLine
     * @param string      $body
     * @param string|null $attachmentPath  // storage path relative to `storage/app`
     */
    public function __construct($subjectLine, $body, $attachmentPath = null)
    {
        $this->subjectLine    = $subjectLine;
        $this->body           = $body;
        $this->attachmentPath = $attachmentPath;
    }

    public function build()
    {
        $m = $this
            ->from(config('mail.from.address'), auth()->user()->name)
            ->replyTo(auth()->user()->email, auth()->user()->name)
            ->subject($this->subjectLine)
            ->markdown('emails.templates.bulk')
            ->with(['body' => $this->body]);
            
        if ($this->attachmentPath) {
            $m->attach(storage_path('app/public/' . $this->attachmentPath));
        }
        

        return $m;
    }
}
