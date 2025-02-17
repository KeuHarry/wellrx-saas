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
        Schema::create('prescription_item_label_codes', function (Blueprint $table) {
            $table->foreignId('prescription_item_id')->constrained('prescription_items')->cascadeOnDelete();
            $table->foreignId('label_code_id')->constrained('label_codes');
            $table->primary(['prescription_item_id', 'label_code_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescription_item_label_codes');
    }
};
