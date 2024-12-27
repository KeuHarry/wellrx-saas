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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('patient_code')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('dob');
            $table->string('address')->nullable();
            $table->string('city');
            $table->string('email');
            $table->string('phone');
            $table->string('company')->nullable();
            $table->foreignId('insurer_id')->nullable()->constrained('insurers');
            $table->foreignId('caretaker_id')->nullable()->constrained('patients');
            $table->text('allergies')->nullable();
            $table->text('medecine_history')->nullable();
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
        Schema::dropIfExists('patients');
    }
};
