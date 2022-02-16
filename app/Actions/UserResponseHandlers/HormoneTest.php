<?php

namespace App\Actions\UserResponseHandlers;

class HormoneTest implements UserResponseHandler
{
    public function handle($userResponse, &$userData)
    {
        $userData['hormone_test'] = ($userResponse->order === 2);

        if (!$userData['hormone_test']) {
            return 'Для получения точного ответа, Вам необходимо сдать гормон АМГ и ввести здесь полученный результат.';
        }
    }
}
