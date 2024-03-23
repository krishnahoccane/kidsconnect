<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $password;
    public $email;

    public function __construct($email, $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public function build()
    {   
        $email = $this->email;
        $fromEmail = config('mail.from.address');
        $subject = 'Password Recovery';
        $name = 'Kids Connect';
        
        return $this->view('mailsBody.forgotPassword')
            ->to($email)
            ->from($fromEmail, $name)
            ->replyTo($fromEmail, $name)
            ->subject($subject)
            ->with(['password' => $this->password]);
    }
}
