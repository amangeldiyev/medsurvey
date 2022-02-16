<?php

namespace App\Actions\UserResponseHandlers;

class Height implements UserResponseHandler
{
    public function handle($userResponse, &$userData)
    {
        $userData['height'] = $userResponse->pivot->value;

        return (new BodyMassIndex)->handle($userResponse, $userData);
    }
}
