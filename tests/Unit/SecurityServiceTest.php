<?php

namespace Tests\Unit;

use App\Services\SecurityService;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class SecurityServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_mask_sensitive_data_masks_correctly()
    {
        $phone = '08123456789';
        $masked = SecurityService::maskSensitiveData($phone, 2);
        
        $this->assertEquals('08********89', $masked);
    }

    public function test_mask_sensitive_data_short_string()
    {
        $short = '123';
        $masked = SecurityService::maskSensitiveData($short, 3);
        
        $this->assertEquals('***', $masked);
    }

    public function test_encrypt_decrypt_sensitive_field()
    {
        $original = 'Secret Address Data';
        $encrypted = SecurityService::encryptSensitiveField($original);
        $decrypted = SecurityService::decryptSensitiveField($encrypted);
        
        $this->assertNotEquals($original, $encrypted);
        $this->assertEquals($original, $decrypted);
    }

    public function test_decrypt_invalid_field_returns_original()
    {
        $invalid = 'invalid_encrypted_data';
        $result = SecurityService::decryptSensitiveField($invalid);
        
        $this->assertEquals($invalid, $result);
    }

    public function test_validate_secure_session_expired()
    {
        // Set old session time
        session(['last_security_check' => time() - 2000]); // More than 30 minutes ago
        
        $isValid = SecurityService::validateSecureSession();
        
        $this->assertFalse($isValid);
    }

    public function test_validate_secure_session_valid()
    {
        // Set current session time
        session(['last_security_check' => time()]);
        
        $isValid = SecurityService::validateSecureSession();
        
        $this->assertTrue($isValid);
    }

    public function test_log_activity_creates_security_log()
    {
        $user = User::factory()->create();
        
        SecurityService::logActivity('test_action', ['test' => 'data'], 'low', $user->id);
        
        $this->assertDatabaseHas('security_logs', [
            'user_id' => $user->id,
            'action' => 'test_action',
            'risk_level' => 'low'
        ]);
    }
}
