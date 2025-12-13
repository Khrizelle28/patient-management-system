<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\SemaphoreSmsServices;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class NotifyReadyForPickupOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:notify-pickup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify patients via SMS when their orders have been ready for pickup for 24 hours';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (env('ENABLE_SMS_NOTIFICATION', false) !== true) {
            $this->warn('SMS notifications are disabled. Set ENABLE_SMS_NOTIFICATION=true in .env to enable.');

            return self::FAILURE;
        }

        $this->info('Checking for orders ready for pickup that need notification...');

        $twentyFourHoursAgo = Carbon::now()->subHours(24);

        // Get orders with status 'ready to pickup' that either:
        // 1. Have never been notified and were created more than 24 hours ago
        // 2. Were last notified more than 24 hours ago
        $orders = Order::with('patientUser')
            ->where('status', 'ready to pickup')
            ->where(function ($query) use ($twentyFourHoursAgo) {
                $query->where(function ($q) use ($twentyFourHoursAgo) {
                    // Never notified and created more than 24 hours ago
                    $q->whereNull('last_pickup_notification_sent_at')
                        ->where('created_at', '<=', $twentyFourHoursAgo);
                })
                    ->orWhere(function ($q) use ($twentyFourHoursAgo) {
                        // Last notification was more than 24 hours ago
                        $q->whereNotNull('last_pickup_notification_sent_at')
                            ->where('last_pickup_notification_sent_at', '<=', $twentyFourHoursAgo);
                    });
            })
            ->get();

        if ($orders->isEmpty()) {
            $this->info('No orders found that need pickup notifications.');

            return self::SUCCESS;
        }

        $this->info("Found {$orders->count()} order(s) to notify.");

        $successCount = 0;
        $failureCount = 0;

        foreach ($orders as $order) {
            try {
                $result = $this->sendPickupNotification($order);

                if ($result['success']) {
                    $successCount++;
                    $this->info("✓ Notified patient for order #{$order->order_number}");
                } else {
                    $failureCount++;
                    $this->error("✗ Failed to notify patient for order #{$order->order_number}");
                }
            } catch (\Exception $e) {
                $failureCount++;
                $this->error("✗ Exception for order #{$order->order_number}: {$e->getMessage()}");
            }
        }

        $this->newLine();
        $this->info("Summary: {$successCount} sent, {$failureCount} failed");

        return self::SUCCESS;
    }

    /**
     * Send SMS notification to patient about order pickup.
     */
    protected function sendPickupNotification(Order $order): array
    {
        try {
            $patient = $order->patientUser;

            if (empty($order->contact_number)) {
                Log::error('Order contact number is empty', [
                    'order_id' => $order->id,
                    'patient_id' => $patient->id,
                ]);

                return [
                    'success' => false,
                    'error' => 'Contact number is empty',
                ];
            }

            $pickupName = $order->pickup_name ?? $patient->first_name.' '.ucfirst(strtolower($patient->last_name));
            $appName = env('APP_NAME', 'Good Health Clinic');

            $message = "Hello {$pickupName},\n\n";
            $message .= "Your order #{$order->order_number} is ready for pickup.\n\n";
            $message .= "Please collect your order at your earliest convenience.\n\n";
            $message .= 'Total Amount: ₱'.number_format($order->total_amount, 2)."\n\n";
            $message .= "Thank you,\n{$appName}";

            Log::info('Sending pickup notification SMS', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'patient_id' => $patient->id,
                'contact_no' => $order->contact_number,
            ]);

            $smsService = new SemaphoreSmsServices;
            $result = $smsService->sendSms($order->contact_number, $message);

            if ($result['success']) {
                // Update the last notification timestamp
                $order->update([
                    'last_pickup_notification_sent_at' => Carbon::now(),
                ]);

                Log::info('Pickup notification SMS sent successfully', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'message_id' => $result['message_id'] ?? null,
                ]);
            } else {
                Log::error('Failed to send pickup notification SMS', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'error' => $result['error'] ?? 'Unknown error',
                ]);
            }

            return $result;

        } catch (\Exception $e) {
            Log::error('Exception in pickup notification', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
