<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;

class UserTicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public $tickets, public $user) {}

    public function content(): Content
    {
        return new Content(
            view: 'emails.user_notification',
        );
    }
}