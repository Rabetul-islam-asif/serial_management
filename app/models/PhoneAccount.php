<?php

namespace App\Models;

class PhoneAccount extends BaseModel {
    protected string $table = 'phone_accounts';
    protected array $fillable = ['phone', 'password_hash', 'is_active', 'last_login_at'];

    /**
     * Find account by phone number
     */
    public function findByPhone(string $phone): ?array {
        return $this->findBy('phone', $phone);
    }

    /**
     * Authenticate with phone + password
     */
    public function authenticate(string $phone, string $password): ?array {
        $account = $this->findByPhone($phone);
        if ($account && password_verify($password, $account['password_hash'])) {
            $this->update($account['id'], ['last_login_at' => date('Y-m-d H:i:s')]);
            return $account;
        }
        return null;
    }

    /**
     * Generate a random 6-digit numeric password
     */
    public function generatePassword(): string {
        return str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Create a new phone account with hashed password, return the ID
     */
    public function createAccount(string $phone, string $plainPassword): int {
        return $this->create([
            'phone' => $phone,
            'password_hash' => password_hash($plainPassword, PASSWORD_BCRYPT),
            'is_active' => 1
        ]);
    }

    /**
     * Reset password and return new plain password
     */
    public function resetPassword(int $id): string {
        $newPassword = $this->generatePassword();
        $this->update($id, [
            'password_hash' => password_hash($newPassword, PASSWORD_BCRYPT)
        ]);
        return $newPassword;
    }
}
