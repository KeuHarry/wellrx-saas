<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_code')->unique();
            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->timestamp('invoice_date')->useCurrent();
            $table->tinyInteger('status')->default(0);
            $table->float('sub_total', 25, 2);
            $table->float('tax_total', 25, 2);
            $table->float('total', 25, 2);
            $table->float('charges', 25, 2);
            $table->text('comment')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
