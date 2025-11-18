<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('paypal_payment_id')->nullable()->after('notes');
            $table->string('paypal_payer_id')->nullable()->after('paypal_payment_id');
            $table->string('paypal_transaction_id')->nullable()->after('paypal_payer_id');
            $table->enum('payment_status', ['pending', 'completed', 'failed', 'cancelled', 'refunded'])->default('pending')->after('paypal_transaction_id');
            $table->timestamp('payment_completed_at')->nullable()->after('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['paypal_payment_id', 'paypal_payer_id', 'paypal_transaction_id', 'payment_status', 'payment_completed_at']);
        });
    }
};
