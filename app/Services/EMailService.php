<?php

namespace App\Services;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class EMailService
{
    public function sendUserEmailVerification($name, $id, $email, $token, $locale){
        Mail::to($email)->send(new \App\Mail\EmailVerify($name, $token, $id, $locale));
    }
}
