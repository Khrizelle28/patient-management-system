<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $guarded = ['id'];

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'appointment_date',
        'appointment_time',
        'email',
        'status',
        'notes',
        'service_type',
        'service_price',
        'has_pap_smear',
        'pap_smear_price',
        'needs_medical_certificate',
        'medical_certificate_price',
        'total_amount',
        'paypal_payment_id',
        'paypal_payer_id',
        'paypal_transaction_id',
        'payment_status',
        'payment_completed_at',
    ];

    public function patient()
    {
        return $this->hasOne(PatientUser::class, 'id', 'patient_id');
    }

    public function doctor()
    {
        return $this->hasOne(User::class, 'id', 'doctor_id');
    }
}
