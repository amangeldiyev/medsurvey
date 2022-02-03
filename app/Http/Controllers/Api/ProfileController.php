<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProfileStoreRequest;

class ProfileController extends Controller
{
    public function store(ProfileStoreRequest $request)
    {
        $validated = $request->validated();

        $request->user()->update($validated);

        return response()->json([
            'success' => true
        ]);
    }
}
