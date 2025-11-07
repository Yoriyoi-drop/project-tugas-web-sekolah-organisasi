<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\SecurityLog;
use App\Services\SecurityService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityLoggingTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_logs_otp_generation(): void
    {
        $user = User::factory()->create();

        $user->generateEmailOtp();

        $this->assertDatabaseHas('security_logs', [
            'user_id' => $user->id,
            'action' => SecurityService::ACTION_OTP_GENERATE,
            'risk_level' => SecurityService::RISK_LOW
        ]);
    }

    public function test_it_logs_failed_otp_verification(): void
    {
        $user = User::factory()->create();
        $code = $user->generateEmailOtp();

        $this->withSession(['otp_user_id' => $user->id]);

        $response = $this->post(route('otp.verify'), [
            'code' => 'WRONG1'
        ]);

        $this->assertDatabaseHas('security_logs', [
            'user_id' => $user->id,
            'action' => SecurityService::ACTION_OTP_FAILED,
            'risk_level' => SecurityService::RISK_MEDIUM
        ]);
    }

    public function test_it_logs_successful_otp_verification(): void
    {
        $user = User::factory()->create();
        $code = $user->generateEmailOtp();

        $this->withSession(['otp_user_id' => $user->id]);

        $response = $this->post(route('otp.verify'), [
            'code' => $code
        ]);

        $this->assertDatabaseHas('security_logs', [
            'user_id' => $user->id,
            'action' => SecurityService::ACTION_OTP_VERIFY,
            'risk_level' => SecurityService::RISK_LOW
        ]);
    }
}
