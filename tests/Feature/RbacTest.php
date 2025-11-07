<?php

use App\Models\User;
use App\Models\Role;
use App\Models\Ability;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create abilities
    $this->manageMembers = Ability::create([
        'name' => 'Manage Members',
        'slug' => 'manage_members',
        'description' => 'Can manage organization members',
    ]);

    $this->viewReports = Ability::create([
        'name' => 'View Reports',
        'slug' => 'view_reports',
        'description' => 'Can view organization reports',
    ]);

    // Create roles
    $this->ketua = Role::create([
        'name' => 'Ketua',
        'slug' => 'ketua',
        'description' => 'Organization leader',
    ]);

    $this->anggota = Role::create([
        'name' => 'Anggota',
        'slug' => 'anggota',
        'description' => 'Organization member',
    ]);

    // Assign abilities to roles
    $this->ketua->abilities()->attach([$this->manageMembers->id, $this->viewReports->id]);
    $this->anggota->abilities()->attach([$this->viewReports->id]);
});

test('user can be assigned a role', function () {
    $user = User::factory()->create();
    $user->roles()->attach($this->ketua);

    expect($user->hasRole('ketua'))->toBeTrue()
        ->and($user->hasRole('anggota'))->toBeFalse();
});

test('user can have multiple roles', function () {
    $user = User::factory()->create();
    $user->roles()->attach([$this->ketua->id, $this->anggota->id]);

    expect($user->hasRole('ketua'))->toBeTrue()
        ->and($user->hasRole('anggota'))->toBeTrue();
});

test('user can check for abilities', function () {
    $user = User::factory()->create();
    $user->roles()->attach($this->ketua);

    expect($user->hasAbility('manage_members'))->toBeTrue()
        ->and($user->hasAbility('view_reports'))->toBeTrue()
        ->and($user->hasAbility('non_existent_ability'))->toBeFalse();
});

test('anggota cannot access manage_members ability', function () {
    $user = User::factory()->create();
    $user->roles()->attach($this->anggota);

    expect($user->hasAbility('manage_members'))->toBeFalse()
        ->and($user->hasAbility('view_reports'))->toBeTrue();
});
