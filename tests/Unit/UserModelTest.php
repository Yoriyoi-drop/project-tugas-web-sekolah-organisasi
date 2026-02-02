<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\EmailOtp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_admin()
    {
        $adminUser = User::factory()->create(['is_admin' => true]);
        $regularUser = User::factory()->create(['is_admin' => false]);

        $this->assertTrue($adminUser->isAdmin());
        $this->assertFalse($regularUser->isAdmin());
    }

    public function test_user_can_generate_email_otp()
    {
        $user = User::factory()->create();

        $code = $user->generateEmailOtp('127.0.0.1', 'UnitTest');

        $this->assertIsString($code);
        $this->assertNotNull($code);
        
        // Check that an EmailOtp record was created
        $this->assertDatabaseHas('email_otps', [
            'user_id' => $user->id
        ]);
    }

    public function test_user_can_check_if_locked()
    {
        $user = User::factory()->create([
            'locked_until' => now()->addHour()
        ]);

        $this->assertTrue($user->isLocked());

        $user->locked_until = now()->subHour();
        $this->assertFalse($user->isLocked());
    }

    public function test_user_can_lock_account()
    {
        $user = User::factory()->create();

        $user->lockAccount(60); // Lock for 60 minutes

        $this->assertNotNull($user->locked_until);
        $this->assertTrue($user->isLocked());
    }

    public function test_user_can_unlock_account()
    {
        $user = User::factory()->create([
            'locked_until' => now()->addHour()
        ]);

        $user->unlockAccount();

        $this->assertNull($user->locked_until);
        $this->assertFalse($user->isLocked());
    }
    
    public function test_user_has_security_logs_relationship()
    {
        $user = User::factory()->create();
        $securityLog = \App\Models\SecurityLog::create([
            'user_id' => $user->id,
            'action' => 'test_action',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'UnitTest',
            'data' => [],
            'risk_level' => 'low',
        ]);

        $this->assertCount(1, $user->securityLogs);
    }
}