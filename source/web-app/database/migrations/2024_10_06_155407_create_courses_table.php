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
        Schema::create('courses', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('course_name'); // Course name
            $table->text('course_description')->nullable(); // Course description (nullable)
            $table->string('duration'); // Duration as varchar
            $table->decimal('fee', 8, 2); // Course fee
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
