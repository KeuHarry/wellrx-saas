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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->float('stock_number', 20, 2)->nullable();
            $table->string('label')->unique();
            $table->boolean('is_prescription')->default(0);
            $table->boolean('is_generic')->default(0);
            $table->foreignId('generic_product_id')->nullable()->constrained('products');
            $table->string('barcode')->nullable();
            $table->string('cip_code')->nullable();
            $table->string('ndc')->nullable();
            $table->integer('card_number')->nullable();
            $table->float('acq_cost', 20, 2);
            $table->float('wholesale_cost', 20, 2);
            $table->float('retail_cost', 20, 2);
            $table->float('unit_cost', 20, 2);
            $table->float('markup', 8, 2)->nullable();
            $table->string('description')->nullable();
            $table->integer('qoh')->default(0);
            $table->integer('min_quantity')->nullable();
            $table->integer('max_quantity')->nullable();
            $table->integer('blister_pack_per_product')->nullable();
            $table->integer('blister_pack_total')->nullable();
            $table->boolean('is_decomposable')->default(1);
            $table->boolean('is_active')->default(1);
            $table->integer('pkg_size')->default(1);
            $table->foreignId('shelf_id')->constrained('shelves');
            $table->foreignId('product_category_id')->constrained('categories');
            $table->foreignId('fee_id')->nullable()->constrained('fees');
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
        Schema::dropIfExists('products');
    }
};
