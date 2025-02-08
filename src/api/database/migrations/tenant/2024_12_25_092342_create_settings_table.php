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
        Schema::create('settings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('key')->unique();
            $table->string('value')->nullable();
            $table->timestamps();
        });

        DB::table('settings')->insert([
            [
                'id' => Str::uuid(),
                'key' => 'pharmacy_name',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'key' => 'pharmacy_address',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'key' => 'pharmacy_city',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'key' => 'pharmacy_phone',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'key' => 'pharmacy_email',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'key' => 'proprietor',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'key' => 'footer_message',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'key' => 'currency',
                'value' => '$',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'key' => 'decimal_digit',
                'value' => '2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'key' => 'decimal_separator',
                'value' => '.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'key' => 'thousand_separator',
                'value' => ',',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
