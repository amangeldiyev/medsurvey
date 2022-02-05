<?php

use App\Models\User;

test('register user', function () {
    $user = User::factory()->make();

    $response = $this->post('/api/register', [
        'email' => $user->email,
        'password' => 'password',
        'password_confirmation' => 'password',
        'device_name' => 'phone'
    ]);

    $response->assertSuccessful();

    $this->assertNotEmpty($response->getContent());

    $this->assertDatabaseHas('users', [
        'email' => $user->email
    ]);

    $this->assertDatabaseHas('personal_access_tokens', [
        'name' => 'phone',
    ]);
});
