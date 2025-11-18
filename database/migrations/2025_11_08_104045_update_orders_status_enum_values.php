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
        // Step 1: Convert enum to VARCHAR temporarily
        DB::statement("ALTER TABLE orders MODIFY COLUMN status VARCHAR(50) DEFAULT 'ready to pickup'");

        // Step 2: Update existing orders with old statuses to new statuses
        DB::table('orders')
            ->whereIn('status', ['pending', 'processing', 'cancelled'])
            ->update(['status' => 'ready to pickup']);

        // Step 3: Convert back to enum with only the new values
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('ready to pickup', 'completed') DEFAULT 'ready to pickup'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Step 1: Convert enum to VARCHAR temporarily
        DB::statement("ALTER TABLE orders MODIFY COLUMN status VARCHAR(50) DEFAULT 'pending'");

        // Step 2: Revert the status values (though this may not be accurate)
        DB::table('orders')
            ->where('status', 'ready to pickup')
            ->update(['status' => 'pending']);

        // Step 3: Revert to original enum values
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending'");
    }
};
