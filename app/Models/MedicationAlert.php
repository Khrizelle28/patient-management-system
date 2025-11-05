<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicationAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'time',
        'period',
        'medication_name',
        'remarks',
        'is_enabled',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    /**
     * Get the patient that owns the medication alert.
     */
    public function patient()
    {
        return $this->belongsTo(PatientUser::class, 'patient_id');
    }
}
