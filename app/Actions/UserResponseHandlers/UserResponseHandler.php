<?php

namespace App\Actions\UserResponseHandlers;

interface UserResponseHandler
{
    public function handle($userResponse, &$userData);
}
