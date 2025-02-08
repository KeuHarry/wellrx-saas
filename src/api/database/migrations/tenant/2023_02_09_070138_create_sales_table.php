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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->float('total');
            $table->float('taxes');
            $table->float('discount');
            $table->float('net_due');
            $table->float('tendered')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('cashier_status')->default(0);
            $table->tinyInteger('type')->default(0);
            $table->boolean('is_one_time_payment')->default(1);
            $table->tinyInteger('payment_method')->default(0);
            $table->float('paid_amount')->nullable();
            $table->string('payment_motive')->nullable();
            $table->date('payment_date_limit')->nullable();
            $table->timestamp('completed_date')->nullable();
            $table->foreignId('cashier_id')->nullable()->constrained('users');
            $table->foreignId('cash_register_id')->constrained('cash_registers');
            $table->foreignId('customer_id')->nullable()->constrained('customers');
            $table->foreignId('prescription_id')->nullable()->constrained('prescriptions');
            $table->foreignId('refunded_sale_id')->nullable()->constrained('sales');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
