<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function register_user()
    {
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
    }
}
