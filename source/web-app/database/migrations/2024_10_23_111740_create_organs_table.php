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
        Schema::create('organs', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('organ_name'); // Name of the organ
            $table->string('blood_type'); // Blood type (A+, O-, etc.)
            $table->string('donor_name'); // Donor's name
            $table->integer('donor_age'); // Donor's age
            $table->enum('donor_gender', ['male', 'female', 'other']); // Donor's gender
            $table->string('organ_type'); // Type of organ (Vital, Non-vital)
            $table->string('organ_condition'); // Condition of the organ (Fresh, Stored, Preserved)
            $table->timestamps(); // Timestamps for created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organs');
    }
};
