<?php

namespace App\Mails;

use App\Models\User;

class ResetPasswordEmail extends \Illuminate\Mail\Mailable
{

    public function __construct(public User $user, public string $token) { }

    public function build(): self
    {
        return $this->from(env('MAIL_FROM_ADDRESS'))
            ->to($this->user->email)
            ->subject(config('app.name') . ' - Password Reset')
            ->markdown('emails.password-reset')
            ->with(['user' => $this->user, 'token' => $this->token]);
    }
}
