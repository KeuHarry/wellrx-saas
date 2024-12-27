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
        Schema::create('configurations', function (Blueprint $table) {
            $table->id();
            $table->string('footer_message');
            $table->string('currency', 15);
            $table->tinyInteger('decimal_digit');
            $table->string('thousand_seperator', 1);
            $table->string('decimal_seperator', 1);
            $table->boolean('is_screen_printing')->default(1);
            $table->boolean('is_dark_mode')->default(0);
            $table->tinyInteger('layout_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configurations');
    }
};
