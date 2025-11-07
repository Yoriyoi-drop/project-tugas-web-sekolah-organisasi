<?php

use App\Models\User;

it('returns tailored payload for authenticated token user', function () {
    $this->artisan('migrate')->assertExitCode(0);

    $user = User::factory()->create();

    // create token (Sanctum)
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer '.$token)
        ->getJson('/api/data/login');

    $response->assertStatus(200);
    $response->assertJsonStructure(['id','name','email','is_admin','avatar','phone']);
});
