<?php

namespace App\Actions\UserResponseHandlers;

class PlanningPregnancy implements UserResponseHandler
{
    public function handle($userResponse, &$userData)
    {
        $result = [];

        $userData['planning_pregnancy'] = ($userResponse->order === 1);

        if ($userData['planning_pregnancy']) {
            if ($userData['sexual_activity'] >= 3) {
                $result[] = 'Для планирования и наступления беременности необходимо вести регулярную половую жизнь.';
            }

            if ($userData['contraception'] != 7) {
                $result[] = 'Для планирования и наступления беременности необходима отмена контрацепции.';
            }
        } else {
            return 'Это хорошо, что Вы заботитесь о своем репродуктивном здоровье.';
        }

        return $result;
    }
}
