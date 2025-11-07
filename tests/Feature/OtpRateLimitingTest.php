<?php

use App\Models\User;
use App\Models\EmailOtp;
use App\Models\SecurityLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->session(['otp_user_id' => $this->user->id]);
});

test('user is rate limited after multiple failed OTP attempts', function () {
    $otp = EmailOtp::create([
        'user_id' => $this->user->id,
        'code_hash' => Hash::make('123456'),
        'expires_at' => now()->addMinutes(10),
        'sent_count' => 1,
        'last_sent_at' => now(),
    ]);

    // Make 5 failed attempts
    for ($i = 0; $i < 5; $i++) {
        $response = $this->post(route('otp.verify'), [
            'code' => 'wrong_code'
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors('code');
    }

    // The 6th attempt should fail with account locked
    $response = $this->post(route('otp.verify'), [
        'code' => 'wrong_code'
    ]);

    $response->assertStatus(302);
    $response->assertSessionHasErrors('code');
    expect(session('errors')->first('code'))->toContain('Try again later');

    // Verify security logs
    expect(SecurityLog::where('action', 'account_locked')->exists())->toBeTrue();
});

test('account is locked after exceeding failed attempts', function () {
    // Create an OTP
    $otp = EmailOtp::create([
        'user_id' => $this->user->id,
        'code_hash' => Hash::make('123456'),
        'expires_at' => now()->addMinutes(10),
        'sent_count' => 1,
        'last_sent_at' => now(),
    ]);

    // Make 6 failed attempts
    for ($i = 0; $i < 6; $i++) {
        $response = $this->post(route('otp.verify'), [
            'code' => 'wrong_code'
        ]);
        $response->assertStatus(302);
    }

    // Refresh user from database
    $this->user->refresh();

    expect($this->user->isLocked())->toBeTrue()
        ->and(SecurityLog::where('action', 'account_locked')->exists())->toBeTrue();
});

test('locked account cannot attempt OTP verification', function () {
    // Lock the account
    $this->user->lockAccount(15);

    $response = $this->post(route('otp.verify'), [
        'code' => '123456'
    ]);

    $response->assertStatus(302);
    $response->assertSessionHasErrors('code');
    expect(session('errors')->first('code'))->toContain('Account is locked');
});

test('successful OTP verification resets failed attempts', function () {
    // Set some failed attempts and create OTP
    $this->user->lockAccount(15);

    EmailOtp::create([
        'user_id' => $this->user->id,
        'code_hash' => Hash::make('123456'),
        'expires_at' => now()->addMinutes(10),
        'sent_count' => 1,
        'last_sent_at' => now(),
    ]);

    // Unlock account to allow verification
    $this->user->unlockAccount();

    // Verify with correct code
    $response = $this->post(route('otp.verify'), [
        'code' => '123456'
    ]);

    // Refresh user from database
    $this->user->refresh();

    expect($this->user->failed_login_attempts)->toBe(0)
        ->and($this->user->locked_until)->toBeNull()
        ->and(SecurityLog::where('action', 'otp_success')->exists())->toBeTrue();
});
