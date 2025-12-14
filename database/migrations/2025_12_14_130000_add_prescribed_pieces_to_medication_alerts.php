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
            $table->integer('prescribed_pieces')->nullable()->after('duration_days');
            $table->integer('times_per_day')->nullable()->after('prescribed_pieces');
            $table->string('start_day')->nullable()->after('times_per_day');
            $table->string('first_dose_time')->nullable()->after('start_day');
            $table->string('first_dose_period')->nullable()->after('first_dose_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medication_alerts', function (Blueprint $table) {
            $table->dropColumn(['prescribed_pieces', 'times_per_day', 'start_day', 'first_dose_time', 'first_dose_period']);
        });
    }
};
