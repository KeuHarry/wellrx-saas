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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products');
            $table->integer('quantity');
            $table->integer('before_qoh')->default(0);
            $table->integer('after_qoh')->default(0);
            $table->tinyInteger('type');
            $table->date('expiry_date');
            $table->integer('batch_id');
            $table->float('acq_cost', 20, 2);
            $table->float('wholesale_cost', 20, 2);
            $table->float('retail_cost', 20, 2);
            $table->float('unit_cost', 20, 2);
            $table->float('sub_total', 20, 2);
            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->text('reason')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('order_item_id')->nullable()->constrained('order_items');
            $table->softDeletes();
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
        Schema::dropIfExists('inventories');
    }
};
