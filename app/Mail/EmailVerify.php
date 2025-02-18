<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailVerify extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $token;
    public $id;
    public $locale;
    /**
     * Create a new message instance.
     */
    public function __construct($name, $token, $id, $locale)
    {
        $this->name = $name;
        $this->id = $id;
        $this->token = $token;
        $this->locale = $locale;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        if($this->locale == 'en')
            return new Envelope(
                subject: 'Confirm your email at inno-journal.uz.',
            );
        elseif ($this->locale == 'uz')
            return new Envelope(
                subject: 'inno-journal.uz da emailingizni tasdiqlang.',
            );
        else{
            return new Envelope(
                subject: 'Подтвердите свой адрес электронной почты на inno-journal.uz.',
            );
        }
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        if($this->locale == 'en'){
            return new Content(
                view: 'mail.email_verify_en',
            );
        }
        elseif ($this->locale == 'uz'){
            return new Content(
                view: 'mail.email_verify_uz',
            );
        }
        else{
            return new Content(
                view: 'mail.email_verify_ru',
            );
        }
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
