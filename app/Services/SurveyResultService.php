<?php

namespace App\Services;

use App\Actions\UserResponseHandlers\Age;
use App\Actions\UserResponseHandlers\Contraception;
use App\Actions\UserResponseHandlers\Height;
use App\Actions\UserResponseHandlers\HormoneTest;
use App\Actions\UserResponseHandlers\HormoneTestValue;
use App\Actions\UserResponseHandlers\Menstruation;
use App\Actions\UserResponseHandlers\PlanningDuration;
use App\Actions\UserResponseHandlers\PlanningPregnancy;
use App\Actions\UserResponseHandlers\SexualActivity;
use App\Actions\UserResponseHandlers\Smoking;
use App\Actions\UserResponseHandlers\Weight;
use App\Models\User;

class SurveyResultService
{
    protected $results = [];

    public $userData = [];

    /**
     * Actions performed on user responses
     *
     * {question order} => {Action class}
     */
    protected $actions = [
        1 => Age::class,
        2 => Height::class,
        3 => Weight::class,
        4 => Smoking::class,
        7 => Menstruation::class,
        8 => SexualActivity::class,
        9 => Contraception::class,
        12 => PlanningPregnancy::class,
        13 => PlanningDuration::class,
        14 => HormoneTest::class,
        15 => HormoneTestValue::class
    ];

    public function process(User $user)
    {
        $responses = $user->responses()
            ->with('question')
            ->get()
            ->sortBy(function ($response) {
                return $response->question->order;
            });

        foreach ($responses as $userResponse) {
            $this->handleResponse($userResponse);
        }

        return [
            'results' => $this->results,
            'userData' => $this->userData
        ];
    }

    protected function handleResponse($userResponse)
    {
        if (isset($this->actions[$userResponse->question->order])) {
            $action = $this->actions[$userResponse->question->order];
            
            $result = call_user_func_array([$action, 'handle'], [$userResponse, &$this->userData]);
            
            if ($result) {
                if (is_array($result)) {
                    foreach ($result as $r) {
                        $this->results[] = $r;
                    }
                } else {
                    $this->results[] = $result;
                }
            }
        }
    }
}
