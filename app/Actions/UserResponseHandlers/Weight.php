<?php

namespace App\Actions\UserResponseHandlers;

class Weight implements UserResponseHandler
{
    public function handle($userResponse, &$userData)
    {
        $userData['weight'] = $userResponse->pivot->value;

        return (new BodyMassIndex)->handle($userResponse, $userData);
    }
}
