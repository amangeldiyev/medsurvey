<?php

namespace App\Actions\UserResponseHandlers;

class Smoking implements UserResponseHandler
{
    public function handle($userResponse, &$userData)
    {
        if ($userResponse->order === 1) {
            return 'Курение снижает шансы забеременеть.';
        }
    }
}
