<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RegistrationRequest;
use App\Models\User;

class RegisterController extends Controller
{
    public function register(RegistrationRequest $request)
    {
        $validated = $request->validated();

        $user = User::create($validated);

        return response()->json([
            'success' => true,
            'user' => $user,
            'token' => $user->createToken($validated['device_name'])->plainTextToken
        ]);
    }
}
