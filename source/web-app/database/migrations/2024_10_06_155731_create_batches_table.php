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
        Schema::create('batches', function (Blueprint $table) {
            $table->id(); // Primary key
            // Batch number (no unique constraint since it's per course)
            $table->unsignedBigInteger('batch_no'); // Batch number per course
            $table->string('batch_name'); // Batch name
            $table->date('start_date'); // Start date
            $table->date('end_date'); // End date
            $table->unsignedBigInteger('course_id'); // Foreign key for course
            $table->enum('status', ['incomplete', 'completed']); // Status
    
            // Foreign key constraint
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};
