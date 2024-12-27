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
        Schema::create('insurers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('tax_payer_number');
            $table->string('registration_number');
            $table->float('credit_limit', 20, 2)->default(0);
            $table->float('balance', 20, 2)->default(0);
            $table->float('deposit', 20, 2)->default(0);
            $table->string('address')->nullable();
            $table->string('neighborhood')->nullable();
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
        Schema::dropIfExists('insurers');
    }
};
