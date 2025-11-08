<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, update existing orders with old statuses to new statuses
        DB::table('orders')
            ->whereIn('status', ['pending', 'processing', 'cancelled'])
            ->update(['status' => 'ready to pickup']);

        // Then alter the enum to only have 'ready to pickup' and 'completed'
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('ready to pickup', 'completed') DEFAULT 'ready to pickup'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum values
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending'");

        // Optionally revert the status values (though this may not be accurate)
        DB::table('orders')
            ->where('status', 'ready to pickup')
            ->update(['status' => 'pending']);
    }
};
