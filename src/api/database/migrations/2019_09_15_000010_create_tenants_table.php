<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('pharmacy_name');
            $table->string('registration_number');
            $table->string('country');
            $table->string('address');
            $table->string('identifier')->unique();
            $table->string('account')->unique();
            $table->string('email')->unique();
            $table->string('website_url')->nullable();
            $table->string('phone_number');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->json('data')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
}
