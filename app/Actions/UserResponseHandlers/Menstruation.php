<?php

namespace App\Actions\UserResponseHandlers;

class Menstruation implements UserResponseHandler
{
    public function handle($userResponse, &$userData)
    {
        if ($userResponse->order === 2) {
            return 'Не регулярьный цикл говорит о чрезмерном или низком овариальном резерве.';
        }
    }
}
