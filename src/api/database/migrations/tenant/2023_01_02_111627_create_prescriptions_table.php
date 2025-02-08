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
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients');
            $table->tinyInteger('status')->default(0);
            $table->float('total');
            $table->float('net_due');
            $table->float('discount_percentage');
            $table->string('discount_flat');
            $table->date('prescription_date');
            $table->date('prescription_exp_date');
            $table->float('insurance_percentage');
            $table->float('insurance_flat');
            $table->string('bpc')->nullable();
            $table->date('bpc_date')->nullable();
            $table->boolean('is_invoice_transmitted')->default(0);
            $table->boolean('is_insurance_paid')->default(0);
            $table->boolean('is_adjudicated')->default(0);
            $table->foreignId('prescriber_id')->constrained('prescribers');
            $table->foreignId('created_by')->constrained('users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
