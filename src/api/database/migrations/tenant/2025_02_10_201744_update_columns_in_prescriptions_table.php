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
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->float('discount_percentage')->nullable()->change();
            $table->float('discount_flat')->nullable()->change();
            $table->float('insurance_percentage')->nullable()->change();
            $table->float('insurance_flat')->nullable()->change();
            $table->float('taxes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            //
        });
    }
};
