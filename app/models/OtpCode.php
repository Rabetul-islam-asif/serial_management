<?php

namespace App\Models;

class OtpCode extends BaseModel {
    protected string $table = 'otp_codes';
    protected array $fillable = [
        'phone', 'code_hash', 'expires_at', 'attempts', 'verified', 'created_at'
    ];
    protected bool $useTimestamps = false;

    /**
     * Generate and save a new OTP code for a phone number
     */
    public function generate(string $phone): string {
        // Set to a static 123456 code for quick developer testing
        $code = '123456';
        
        // Hash it for DB safety
        $codeHash = password_hash($code, PASSWORD_BCRYPT);
        
        // Expiry (5 minutes)
        $expiresAt = date('Y-m-d H:i:s', time() + config('auth.otp_expiry', 300));
        
        $this->create([
            'phone' => $phone,
            'code_hash' => $codeHash,
            'expires_at' => $expiresAt,
            'attempts' => 0,
            'verified' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return $code;
    }

    /**
     * Verify an OTP code for a phone number
     */
    public function verify(string $phone, string $code): bool {
        // Retrieve latest unexpired, unverified OTP code
        $sql = "SELECT * FROM {$this->table} 
                WHERE phone = :phone 
                  AND verified = 0 
                  AND expires_at > NOW() 
                ORDER BY id DESC LIMIT 1";
        
        $results = $this->query($sql, ['phone' => $phone]);
        
        if (empty($results)) {
            return false;
        }
        
        $otpRecord = $results[0];
        
        // Check maximum attempts limit
        if ($otpRecord['attempts'] >= config('auth.otp_attempts_limit', 3)) {
            return false;
        }

        // Increment attempts count
        $this->update($otpRecord['id'], [
            'attempts' => $otpRecord['attempts'] + 1
        ]);

        if (password_verify($code, $otpRecord['code_hash'])) {
            // Update status to verified
            $this->update($otpRecord['id'], [
                'verified' => 1
            ]);
            return true;
        }

        return false;
    }
}
