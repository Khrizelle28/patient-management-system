<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalCertificate extends Model
{
    protected $guarded = ['id'];

    public function patient()
    {
        return $this->hasOne(PatientUser::class, 'id', 'patient_id');
    }

    public function doctor()
    {
        return $this->hasOne(User::class, 'id', 'doctor_id');
    }
}
