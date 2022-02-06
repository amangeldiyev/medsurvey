<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;

class LogoutController extends Controller
{
    /**
     * Handle an incoming logout request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        request()->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
