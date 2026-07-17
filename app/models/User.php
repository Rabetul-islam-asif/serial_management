<?php

namespace App\Models;

class User extends BaseModel {
    protected string $table = 'users';
    protected array $fillable = [
        'name', 'email', 'phone', 'password_hash', 'role', 
        'avatar', 'is_active', 'remember_token', 'last_login_at'
    ];

    /**
     * Authenticate user by email & password
     */
    public function authenticate(string $email, string $password) {
        $user = $this->findBy('email', $email);
        
        if ($user && $user['is_active'] && password_verify($password, $user['password_hash'])) {
            // Update last login
            $this->update($user['id'], [
                'last_login_at' => date('Y-m-d H:i:s')
            ]);
            return $user;
        }
        
        return null;
    }
}
