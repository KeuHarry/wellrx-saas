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
        Schema::create('sale_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales');
            $table->tinyInteger('payment_method')->default(0);
            $table->string('reference')->nullable();
            $table->float('tendered')->default(0);
            $table->float('amount');
            $table->float('change')->default(0);
            $table->foreignId('cashier_id')->nullable()->constrained('users');
            $table->foreignId('cash_register_id')->constrained('cash_registers');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_payments');
    }
};
