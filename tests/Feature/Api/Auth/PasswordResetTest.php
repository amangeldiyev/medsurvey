<?php

namespace Tests\Feature\Api\Auth;

use App\Models\User;
use App\Notifications\ResetPassword;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('user can reset password with code', function () {
    // Step 1: request password reset
    Notification::fake();

    $response = $this->post('/api/forgot-password', [
        'email' => $this->user->email
    ], ['accept' => 'application/json']);
    
    $this->assertDatabaseHas('password_resets', ['email' => $this->user->email]);

    Notification::assertSentTo(
        [$this->user],
        ResetPassword::class
    );
    
    $response->assertSuccessful()
        ->assertJson(['success' => true]);


    // Step 2: Reset password with code from email
    $code = DB::table('password_resets')
        ->where('email', $this->user->email)
        ->pluck('code')
        ->first();

    $response = $this->post('/api/reset-password', [
        'email' => $this->user->email,
        'code' => $code,
        'password' => 'newpassword',
        'password_confirmation' => 'newpassword',
    ]);

    $this->assertDatabaseMissing('password_resets', ['email' => $this->user->email])
        ->assertTrue(Hash::check('newpassword', $this->user->fresh()->password));

    $response->assertSuccessful()
        ->assertJson(['success' => true]);
});
