<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Option;
use App\Models\Survey;
use App\Services\SurveyResultService;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    /**
     * Get survey with questions and options
     *
     * @param Survey $survey
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Survey $survey)
    {
        return response()->json([
            'success' => true,
            'survey' => $survey->load('questions.options')
        ]);
    }

    /**
     * Return survey questions with options
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function start()
    {
        $survey = Survey::first();

        return response()->json([
            'success' => true,
            'survey' => $survey->load('questions.options')
        ]);
    }

    /**
     * Store user's survey response
     * {
     *     "userResponses": [
     *         {"option_id": 1, "value": "1999-01-01"},
     *         {"option_id": 2, "value": "170"}
     *     ]
     * }
     *
     * @param \Illuminate\Http\Request $request
     * @param SurveyResultService $surveyService
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, SurveyResultService $surveyService)
    {
        $responses = $request->userResponses;

        foreach ($responses as $option) {
            $request->user()->responses()->syncWithPivotValues(
                $option['option_id'],
                ['value' => $option['value']],
                false
            );
        }

        $surveyResult = $surveyService->process($request->user());

        return response()->json([
            'success' => true,
            'results' => $surveyResult['results'],
            'userData' => $surveyResult['userData']
        ]);
    }

    /**
     * Return user's survey data
     *
     * @param \Illuminate\Http\Request $request
     * @param SurveyResultService $surveyService
     * @return \Illuminate\Http\JsonResponse
     */
    public function results(Request $request, SurveyResultService $surveyService)
    {
        $surveyResult = $surveyService->process($request->user());

        return response()->json([
            'success' => true,
            'results' => $surveyResult['results'],
            'userData' => $surveyResult['userData']
        ]);
    }

    /**
     * Save user's response to question and return next question
     *
     * @param \Illuminate\Http\Request $request
     * @param Option $option
     * @return \Illuminate\Http\JsonResponse
     *
     * @bodyParam value string Max 250 chars.
     */
    public function storeUserResponse(Request $request, Option $option)
    {
        $request->validate([
            'value' => 'nullable|string|max:250'
        ]);

        $request->user()->responses()->syncWithPivotValues(
            $option->id,
            ['value' => $request->value],
            false
        );

        $nextQuestion = $option->subQuestions ?? $option->question->next();

        return response()->json([
            'success' => true,
            'question' => $nextQuestion->load('options')
        ]);
    }
}
