<?php

namespace App\Actions\UserResponseHandlers;

class BodyMassIndex implements UserResponseHandler
{
    public function handle($userResponse, &$userData)
    {
        if (isset($userData['height']) && isset($userData['weight'])) {
            $userData['bmi'] = $userData['weight'] / pow($userData['height']/100, 2);

            if ($userData['bmi'] < 18.5) {
                return 'У Вас недостаточная масса тела.';
            }

            if ($userData['bmi'] < 25) {
                return 'Индекс массы в норме.';
            }

            return 'Избыточная масса тела';
        }
    }
}
