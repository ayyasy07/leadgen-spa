<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $contactMessage;
    public $replyText;

    public function __construct(ContactMessage $contactMessage, string $replyText)
    {
        $this->contactMessage = $contactMessage;
        $this->replyText = $replyText;
    }

    public function build()
    {
        return $this
            ->subject('Re: ' . ($this->contactMessage->subject ?: 'Your Message'))
            ->view('emails.contact-reply');
    }
}