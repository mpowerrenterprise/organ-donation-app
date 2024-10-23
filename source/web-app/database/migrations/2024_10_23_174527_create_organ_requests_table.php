<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organ_requests', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary key, auto-increment
            $table->unsignedBigInteger('organ_id'); // Foreign key to organs table
            $table->unsignedBigInteger('user_id');  // Foreign key to users table
            $table->date('date');  // Date of the organ request
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending'); // Status of the request
            $table->timestamps(); // Adds created_at and updated_at columns

            // Add foreign key constraints if needed
            $table->foreign('organ_id')->references('id')->on('organs')->onDelete('cascade'); // Assuming there is an organs table
            $table->foreign('user_id')->references('id')->on('mobile_users')->onDelete('cascade'); // Assuming there is a users table
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organ_requests');
    }
}
