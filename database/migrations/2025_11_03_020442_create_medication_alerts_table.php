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
        Schema::create('medication_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patient_users')->onDelete('cascade');
            $table->string('time');
            $table->string('period'); // AM or PM
            $table->string('medication_name');
            $table->text('remarks')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medication_alerts');
    }
};
