<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoctorMaxPatient extends Model
{
    protected $fillable = [
        'doctor_id',
        'max_patients',
    ];

    /**
     * Get the doctor that owns this max patients record.
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
