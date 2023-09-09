<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFollowersTable extends Migration
{
    public function up()
    {
        Schema::create('followers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('following_id'); // The user who is following
            $table->unsignedBigInteger('follower_id'); // The user who is being followed
            $table->timestamps();
            
            // Unique combination of following_id and follower_id to prevent duplicate follows
            $table->unique(['following_id', 'follower_id']);

            // Define foreign key constraints
            $table->foreign('following_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('follower_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('followers');
    }
}
