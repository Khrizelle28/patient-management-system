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
        'selected_days',
        'duration_days',
        'start_date',
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

    /**
     * Get alarm times as array (supports both JSON and legacy formats).
     */
    public function getAlarmTimesAttribute()
    {
        // Check if time is JSON array (starts with '[')
        if (is_string($this->time) && str_starts_with(trim($this->time), '[')) {
            $decoded = json_decode($this->time, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        // Legacy format: single time with separate period field
        return [['time' => $this->time, 'period' => $this->period]];
    }

    /**
     * Set alarm times (converts array to JSON if multiple times).
     */
    public function setAlarmTimesAttribute($value)
    {
        if (is_array($value) && count($value) > 1) {
            // Multiple times - store as JSON
            $this->attributes['time'] = json_encode($value);
            $this->attributes['period'] = $value[0]['period'] ?? 'AM';
        } elseif (is_array($value) && count($value) == 1) {
            // Single time - store in legacy format for compatibility
            $this->attributes['time'] = $value[0]['time'];
            $this->attributes['period'] = $value[0]['period'];
        }
    }
}
