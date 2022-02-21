<?php

namespace App\Actions\UserResponseHandlers;

class PlanningDuration implements UserResponseHandler
{
    public function handle($userResponse, &$userData)
    {
        $userData['planning_duration'] = $userResponse->title;

        if ($userResponse->order <= 2 && $userData['age'] < 40) {
            return 'Вам еще рано бить тревогу';
        }

        return 'При отсутствии беременности рекомендуем Вам пройти комплексное обследование.';
    }
}
