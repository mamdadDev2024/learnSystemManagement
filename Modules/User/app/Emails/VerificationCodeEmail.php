<?php

namespace Modules\User\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Modules\User\Models\User;

class VerificationCodeEmail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public int $code, public User $user)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'verification code from ' . config('app.name')
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown:'user::mails.verification',
            with:[
                'code' => $this->code,
                'user' => $this->user
            ]
        );
    }
}
