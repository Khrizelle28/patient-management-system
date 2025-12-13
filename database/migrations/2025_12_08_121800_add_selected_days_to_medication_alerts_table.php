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
            $table->string('selected_days')->default('1,2,3,4,5,6,7')->after('is_enabled');
            $table->integer('duration_days')->default(7)->after('selected_days');
            $table->date('start_date')->nullable()->after('duration_days');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medication_alerts', function (Blueprint $table) {
            $table->dropColumn(['selected_days', 'duration_days', 'start_date']);
        });
    }
};
