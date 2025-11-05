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
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('service_type')->nullable()->after('notes'); // Pregnant or Non-Pregnant
            $table->decimal('service_price', 10, 2)->nullable()->after('service_type');
            $table->boolean('has_pap_smear')->default(false)->after('service_price');
            $table->decimal('pap_smear_price', 10, 2)->nullable()->after('has_pap_smear');
            $table->boolean('needs_medical_certificate')->default(false)->after('pap_smear_price');
            $table->decimal('medical_certificate_price', 10, 2)->nullable()->after('needs_medical_certificate');
            $table->decimal('total_amount', 10, 2)->nullable()->after('medical_certificate_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn([
                'service_type',
                'service_price',
                'has_pap_smear',
                'pap_smear_price',
                'needs_medical_certificate',
                'medical_certificate_price',
                'total_amount',
            ]);
        });
    }
};
