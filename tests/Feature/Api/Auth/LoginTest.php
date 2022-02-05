<?php

use App\Models\User;

test('login user', function () {
    $user = User::factory()->create();

    $response = $this->post('/api/login', [
        'email' => $user->email,
        'password' => 'password',
        'device_name' => 'phone'
    ]);

    $response->assertSuccessful();

    $this->assertNotEmpty($response->getContent());

    $this->assertDatabaseHas('personal_access_tokens', [
        'name' => 'phone',
    ]);
});
