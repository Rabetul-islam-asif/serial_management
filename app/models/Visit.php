<?php

namespace App\Models;

class Visit extends BaseModel {
    protected string $table = 'visits';
    protected array $fillable = [
        'patient_id', 'serial_id', 'chamber_id', 'visit_date', 
        'chief_complaint', 'diagnosis', 'doctor_notes', 'next_visit_date', 'status'
    ];
}
