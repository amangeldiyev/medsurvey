<?php

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;

uses(WithFaker::class);

test('store user profile data', function () {
    $user = User::factory()->create();

    $token = $user->createToken('phone')->plainTextToken;

    $response = $this->put('/api/user', [
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
});
