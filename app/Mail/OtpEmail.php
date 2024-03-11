<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $email;

    public function __construct($otp, $email)
    {
        $this->otp = $otp;
        $this->email = $email;
    }

    public function build()
    {   
        $email = $this->email;
        $fromEmail = config('mail.from.address');
        $subject = 'OTP For Registration';
        $name = 'Kids Connect';
        
        return $this->view('mailsBody.otpVerification')
            ->to($email)
            ->from($fromEmail, $name)
            // ->cc($address, $name)
            // ->bcc($address, $name)
            ->replyTo($fromEmail, $name)
            ->subject($subject)
            ->with(['otp' => $this->otp]); // Pass $this->otp directly
    }
}
