<?php

use App\Models\User;

test('logout user', function () {
    $user = User::factory()->create();
    
    $token = $user->createToken('phone')->plainTextToken;
    
    $response = $this->post('/api/logout', [], [
        'Authorization' => 'Bearer ' . $token
    ]);
    
    $response->assertSuccessful();
    
    $this->assertDatabaseCount('personal_access_tokens', 0);
});
