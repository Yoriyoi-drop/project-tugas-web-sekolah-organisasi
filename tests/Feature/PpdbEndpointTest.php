<?php

use App\Models\PPDB;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns ppdb list via api', function () {
    // Create a user for authentication
    $user = \App\Models\User::factory()->create();

    PPDB::create([
        'name' => 'Test PPDB 1',
        'nik' => '3374121234567891',
        'email' => 'test.ppdb@example.com',
        'birth_date' => '2010-01-01',
        'birth_place' => 'Test City',
        'gender' => 'male',
        'address' => 'Test Address',
        'phone' => '081234567890',
        'parent_name' => 'Test Parent',
        'parent_phone' => '081234567891',
        'previous_school' => 'Test School',
        'desired_major' => 'IPA',
        'status' => 'pending'
    ]);

    $response = $this->actingAs($user)->getJson('/api/data/ppdb');
    $response->assertStatus(200);
    // Since we returned pagination, the structure changes.
    // We check if the data exists in the 'data' key or if 1 record is present in total.
    // For simple pagination:
    $response->assertJsonCount(1, 'data');
});
