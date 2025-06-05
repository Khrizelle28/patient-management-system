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
        Schema::table('diagnosis', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->string('estimated_date_confinement')->nullable()->after('fetal_heart_tone');
            $table->foreignId('doctor_id')->after('fetal_heart_tone');
            $table->foreignId('patient_user_id')->after('id');
            // $table->string('type')->after('weight');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diagnosis', function (Blueprint $table) {
            $table->dropColumn(['doctor_id', 'estimated_date_confinement']);
        });
    }
};
