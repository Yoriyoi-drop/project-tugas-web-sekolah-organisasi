<?php

namespace Tests\Unit;

use App\Models\EmailOtp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailOtpModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_otp_has_fillable_attributes()
    {
        $fillable = [
            'user_id',
            'code_hash',
            'attempts',
            'sent_count',
            'last_sent_at',
            'expires_at',
            'consumed_at',
            'ip_address',
            'user_agent',
        ];
        $this->assertEquals($fillable, (new EmailOtp())->getFillable());
    }

    public function test_email_otp_has_datetime_casts()
    {
        $user = User::factory()->create();
        
        $emailOtp = EmailOtp::create([
            'user_id' => $user->id,
            'code_hash' => 'hashed_code',
            'attempts' => 0,
            'sent_count' => 1,
            'last_sent_at' => now(),
            'expires_at' => now()->addMinutes(30),
            'consumed_at' => null,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0'
        ]);

        $this->assertInstanceOf(\DateTime::class, $emailOtp->last_sent_at);
        $this->assertInstanceOf(\DateTime::class, $emailOtp->expires_at);
        $this->assertNull($emailOtp->consumed_at);
    }

    public function test_email_otp_belongs_to_user()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);
        
        $emailOtp = EmailOtp::create([
            'user_id' => $user->id,
            'code_hash' => 'hashed_code',
            'attempts' => 0,
            'sent_count' => 1,
            'last_sent_at' => now(),
            'expires_at' => now()->addMinutes(30),
            'consumed_at' => null,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0'
        ]);

        $this->assertTrue($emailOtp->user->is($user));
        $this->assertInstanceOf(User::class, $emailOtp->user);
    }

    public function test_is_expired_method()
    {
        $user = User::factory()->create();
        
        // Test with expired OTP
        $expiredOtp = EmailOtp::create([
            'user_id' => $user->id,
            'code_hash' => 'hashed_code',
            'attempts' => 0,
            'sent_count' => 1,
            'last_sent_at' => now()->subHour(),
            'expires_at' => now()->subMinutes(10),
            'consumed_at' => null,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0'
        ]);

        $this->assertTrue($expiredOtp->isExpired());

        // Test with non-expired OTP
        $validOtp = EmailOtp::create([
            'user_id' => $user->id,
            'code_hash' => 'hashed_code',
            'attempts' => 0,
            'sent_count' => 1,
            'last_sent_at' => now(),
            'expires_at' => now()->addMinutes(10),
            'consumed_at' => null,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0'
        ]);

        $this->assertFalse($validOtp->isExpired());
    }

    public function test_is_consumed_method()
    {
        $user = User::factory()->create();
        
        // Test with consumed OTP
        $consumedOtp = EmailOtp::create([
            'user_id' => $user->id,
            'code_hash' => 'hashed_code',
            'attempts' => 0,
            'sent_count' => 1,
            'last_sent_at' => now(),
            'expires_at' => now()->addMinutes(30),
            'consumed_at' => now(),
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0'
        ]);

        $this->assertTrue($consumedOtp->isConsumed());

        // Test with non-consumed OTP
        $nonConsumedOtp = EmailOtp::create([
            'user_id' => $user->id,
            'code_hash' => 'hashed_code',
            'attempts' => 0,
            'sent_count' => 1,
            'last_sent_at' => now(),
            'expires_at' => now()->addMinutes(30),
            'consumed_at' => null,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0'
        ]);

        $this->assertFalse($nonConsumedOtp->isConsumed());
    }

    public function test_create_email_otp()
    {
        $user = User::factory()->create();
        
        $emailOtp = EmailOtp::create([
            'user_id' => $user->id,
            'code_hash' => 'hashed_code',
            'attempts' => 0,
            'sent_count' => 1,
            'last_sent_at' => now(),
            'expires_at' => now()->addMinutes(30),
            'consumed_at' => null,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0'
        ]);

        $this->assertDatabaseHas('email_otps', [
            'user_id' => $user->id,
            'code_hash' => 'hashed_code',
            'attempts' => 0,
            'sent_count' => 1,
        ]);

        $this->assertEquals($user->id, $emailOtp->user_id);
        $this->assertEquals('hashed_code', $emailOtp->code_hash);
        $this->assertEquals(0, $emailOtp->attempts);
    }

    public function test_update_email_otp()
    {
        $user = User::factory()->create();
        
        $emailOtp = EmailOtp::create([
            'user_id' => $user->id,
            'code_hash' => 'hashed_code',
            'attempts' => 0,
            'sent_count' => 1,
            'last_sent_at' => now(),
            'expires_at' => now()->addMinutes(30),
            'consumed_at' => null,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0'
        ]);

        $updated = $emailOtp->update([
            'attempts' => 2,
            'sent_count' => 3,
            'consumed_at' => now()
        ]);

        $this->assertTrue($updated);
        $this->assertDatabaseHas('email_otps', [
            'user_id' => $user->id,
            'attempts' => 2,
            'sent_count' => 3,
        ]);
        $this->assertNotNull($emailOtp->fresh()->consumed_at);
    }

    public function test_delete_email_otp()
    {
        $user = User::factory()->create();
        
        $emailOtp = EmailOtp::create([
            'user_id' => $user->id,
            'code_hash' => 'hashed_code',
            'attempts' => 0,
            'sent_count' => 1,
            'last_sent_at' => now(),
            'expires_at' => now()->addMinutes(30),
            'consumed_at' => null,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0'
        ]);

        $deleted = $emailOtp->delete();

        $this->assertTrue($deleted);
        $this->assertDatabaseMissing('email_otps', [
            'user_id' => $user->id,
            'code_hash' => 'hashed_code'
        ]);
    }
}