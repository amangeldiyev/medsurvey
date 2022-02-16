<?php

namespace App\Actions\UserResponseHandlers;

class Contraception implements UserResponseHandler
{
    public function handle($userResponse, &$userData)
    {
        $userData['contraception'] = $userResponse->order;
    }
}
