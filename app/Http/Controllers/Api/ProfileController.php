<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProfileStoreRequest;

class ProfileController extends Controller
{
    /**
     * Return user data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show()
    {
        return request()->user();
    }

    /**
     * Update user profile data
     *
     * @param ProfileStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProfileStoreRequest $request)
    {
        $validated = $request->validated();

        $request->user()->update($validated);

        return response()->json([
            'success' => true
        ]);
    }
}
