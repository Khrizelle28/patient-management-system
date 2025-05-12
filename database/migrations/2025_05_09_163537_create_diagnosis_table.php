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
        Schema::create('diagnosis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('ob_score');
            $table->string('gravida');
            $table->string('para');
            $table->string('last_menstrual_period');
            $table->string('blood_pressure');
            $table->string('weight');
            $table->string('type');
            $table->string('age_of_gestation')->nullable();
            $table->string('fundal_height')->nullable();
            $table->string('fetal_heart_tone')->nullable();
            $table->string('remarks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnosis');
    }
};
