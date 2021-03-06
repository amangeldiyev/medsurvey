<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\PasswordResetController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\SurveyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink']);
Route::post('/reset-password', [PasswordResetController::class, 'store']);

Route::group(['middleware' => 'auth:sanctum'], function ($route) {
    $route->get('/user', [ProfileController::class, 'show']);
    $route->put('/user', [ProfileController::class, 'store']);

    $route->get('/survey/start', [SurveyController::class, 'start']);
    $route->get('/survey/results', [SurveyController::class, 'results']);
    $route->post('/survey', [SurveyController::class, 'store']);
    
    $route->post('/logout', [LogoutController::class, 'logout']);
});
