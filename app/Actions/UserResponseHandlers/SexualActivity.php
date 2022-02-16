<?php

namespace App\Actions\UserResponseHandlers;

class SexualActivity implements UserResponseHandler
{
    public function handle($userResponse, &$userData)
    {
        $userData['sexual_activity'] = $userResponse->order;
    }
}
