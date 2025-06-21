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
        Schema::create('patient_users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('age');
            $table->string('civil_status');
            $table->string('street')->nullable();
            $table->string('barangay');
            $table->string('city_municipality');
            $table->string('province');
            $table->string('occupation')->nullable();
            $table->string('contact_no');
            $table->string('birthday');
            $table->string('birthplace');
            $table->string('username');
            $table->string('password');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_users');
    }
};
