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
        Schema::create('medical_certificates', function (Blueprint $table) {
            $table->id();
            $table->string('appointment_id');
            $table->string('patient_id');
            $table->string('doctor_id');
            $table->string('purpose');
            $table->string('medical_condition');
            $table->string('remarks');
            $table->string('generate_pdf')->default(true);
            $table->string('upload_pdf')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_certificates');
    }
};
