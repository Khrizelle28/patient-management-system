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
        'email',
        'notes',
        'paypal_payment_id',
        'paypal_payer_id',
        'paypal_transaction_id',
        'payment_status',
        'payment_completed_at',
        'last_pickup_notification_sent_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    /**
     * Generate unique order number in format ORD-MM-SSSSS.
     */
    public static function generateOrderNumber(): string
    {
        $month = now()->format('m'); // Current month (01-12)

        // Count orders created in the current month
        $count = self::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();

        // Increment series number
        $series = str_pad($count + 1, 5, '0', STR_PAD_LEFT); // 5 digits with leading zeros

        return "ORD-{$month}-{$series}";
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
