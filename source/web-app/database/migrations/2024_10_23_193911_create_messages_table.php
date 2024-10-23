<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id(); // Auto-increment ID
            $table->unsignedBigInteger('user_id'); // Foreign key for user
            $table->string('organ_name'); // Store organ name as text
            $table->string('blood_type'); // Store blood type
            $table->text('message'); // Message content
            $table->timestamps(); // Created at and Updated at timestamps
            
            // Add foreign key constraint to link messages to users
            $table->foreign('user_id')->references('id')->on('mobile_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
