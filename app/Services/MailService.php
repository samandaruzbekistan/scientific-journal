<?php

use App\Models\User;

class MailService
{
    public function sendEmailVerification(User $user){
        $user->sendEmailVerification();
    }
}
