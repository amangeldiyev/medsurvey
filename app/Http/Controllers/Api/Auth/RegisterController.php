<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationForm;
use App\Models\User;

class RegisterController extends Controller
{
    public function register(RegistrationForm $request)
    {
        $validatedData = $request->validated();

        $user = User::create($validatedData);

        return $user->createToken($validatedData['device_name'])->plainTextToken;
    }
}
