<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;

    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    // Email subject
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your OTP Code - HomeSena',
        );
    }

    // Email content (use the new email Blade view)
    public function content(): Content
    {
        return new Content(
            view: 'emails.send_otp', // ✅ use email template
            with: [
                'otp' => $this->otp,   // pass OTP variable
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}