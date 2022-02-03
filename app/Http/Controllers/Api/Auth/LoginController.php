<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();
    
        $user = User::where('email', $validated['email'])->first();
    
        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json([
            'success' => true,
            'user' => $user,
            'token' => $user->createToken($validated['device_name'])->plainTextToken
        ]);
    }
}
