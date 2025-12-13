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
        Schema::table('medication_alerts', function (Blueprint $table) {
            // Change time field to TEXT to store JSON array for multiple alarm times
            $table->text('time')->change();

            // Note: The 'duration_days' field is currently being used to store 'times_per_day'
            // We keep it as is for backward compatibility
            // In the future, we can restore it to its original purpose if needed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medication_alerts', function (Blueprint $table) {
            // Revert time field back to string
            $table->string('time')->change();
        });
    }
};
