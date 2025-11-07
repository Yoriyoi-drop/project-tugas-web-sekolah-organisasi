<?php

use App\Models\PPDB;

it('returns ppdb list via api', function () {
    // migrate and create sample data
    $this->artisan('migrate')->assertExitCode(0);

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

    $response = $this->getJson('/api/data/ppdb');
    $response->assertStatus(200);
    $response->assertJsonCount(1);
});
