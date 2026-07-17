<?php

namespace App\Models;

class DoctorProfile extends BaseModel {
    protected string $table = 'doctor_profile';
    protected array $fillable = [
        'user_id', 'name', 'degree', 'specialization', 'bmdc_number',
        'hospital', 'bio', 'experience_years', 'consultation_fee',
        'languages', 'photo', 'cover_image'
    ];

    /**
     * Get profile by doctor user ID
     */
    public function getByUserId(int $userId) {
        return $this->findBy('user_id', $userId);
    }
}
