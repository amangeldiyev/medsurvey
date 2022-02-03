<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function store_user_data()
    {
        $user = User::factory()->create();

        $token = $user->createToken('phone')->plainTextToken;

        $response = $this->post('/api/profile', [
            'name' => $name = $this->faker->name(),
            'nationality' => $nationality = $this->faker->word(),
            'dob' => $dob = $this->faker->date(),
            'gender' => $gender = $this->faker->numberBetween(0, 1)
        ], ['Authorization' => 'Bearer ' . $token]);

        $response->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'name' => $name,
            'nationality' => $nationality,
            'dob' => $dob,
            'gender' => $gender
        ]);
    }
}
