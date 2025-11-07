<?php

use App\Models\PPDB;

it('seeder creates ppdb rows', function () {
    $this->artisan('migrate')->assertExitCode(0);

    $this->artisan('db:seed', ['--class' => \Database\Seeders\PPDBSeeder::class])->assertExitCode(0);

    $this->assertDatabaseHas('ppdb', [
        'name' => 'Budi Santoso',
        'nik' => '3374121234567890',
        'email' => 'budi.santoso@email.com'
    ]);
});
