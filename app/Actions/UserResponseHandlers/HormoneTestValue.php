<?php

namespace App\Actions\UserResponseHandlers;

class HormoneTestValue implements UserResponseHandler
{
    public function handle($userResponse, &$userData)
    {
        switch ($userResponse->order) {
            case 1:
                $userData['hormone_test_date'] = 'До 1 года';
                break;
            case 2:
                $userData['hormone_test_date'] = 'Более 1 года назад';
                break;
            case 3:
                $userData['hormone_test_value'] = $userResponse->pivot->value;

                if ($userData['age'] <= 35) {
                    if ($userData['hormone_test_value'] >= 2.5) {
                        return 'У вас очень высокий овариальный резерв.';
                    }
    
                    if ($userData['hormone_test_value'] >= 1) {
                        return 'У вас средний овариальный резерв.';
                    }
                }

                if ($userData['hormone_test_value'] >= 0.5) {
                    return 'Вам надо поторопиться.';
                }
                
                return 'Климакс не за гороми.';
        }
    }
}
