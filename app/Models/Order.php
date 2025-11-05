<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'patient_user_id',
        'order_number',
        'total_amount',
        'status',
        'pickup_name',
        'contact_number',
        'notes',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    /**
     * Generate unique order number.
     */
    public static function generateOrderNumber(): string
    {
        return 'ORD-'.strtoupper(uniqid()).'-'.time();
    }

    /**
     * Get the patient user that owns the order.
     */
    public function patientUser(): BelongsTo
    {
        return $this->belongsTo(PatientUser::class);
    }

    /**
     * Get the order items for the order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
