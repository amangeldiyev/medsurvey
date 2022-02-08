<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Option;
use App\Models\Survey;
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
     * Return first queston from survey
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function start()
    {
        $survey = Survey::first();

        $question = $survey->questions()
            ->orderBy('order')
            ->with('options')
            ->first();

        return response()->json([
            'success' => true,
            'question' => $question
        ]);
    }

    /**
     * Save user's response to question and return next question
     *
     * @param \Illuminate\Http\Request $request
     * @param Option $option
     * @return \Illuminate\Http\JsonResponse
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

        $nextQuestion = $option->subQuestion ?? $option->question->next();

        return response()->json([
            'success' => true,
            'question' => $nextQuestion->load('options')
        ]);
    }
}
