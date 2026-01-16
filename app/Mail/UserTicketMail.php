<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserTicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public $tickets;
    public $user;

    public function __construct($tickets, $user)
    {
        $this->tickets = $tickets;
        $this->user = $user;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Ticket Purchase Confirmation',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.customer_ticket',
        );
    }
}