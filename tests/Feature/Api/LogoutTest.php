<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function logout_user()
    {
        $user = User::factory()->create();

        $token = $user->createToken('phone')->plainTextToken;

        $response = $this->post('/api/logout', [], [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertSuccessful();

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }
}
