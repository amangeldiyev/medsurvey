<?php

namespace App\Actions\UserResponseHandlers;

use Carbon\Carbon;

class Age implements UserResponseHandler
{
    public function handle($userResponse, &$userData)
    {
        $age = Carbon::createFromFormat('Y-m-d', $userResponse->pivot->value)->age;

        $userData['age'] = $age;

        if ($age < 35) {
            return 'У вас ранний репродуктивный возраст.';
        }

        if ($age < 40) {
            return 'Вы в репродуктивном возрасте.';
        }

        return 'У вас поздний репродуктивный возраст.';
    }
}
