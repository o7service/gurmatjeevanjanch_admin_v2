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
        Schema::create('telegramLinks', function (Blueprint $table) {
            $table->id();
            $table->string('autoId')->nullable(); // required 
            $table->string('title');
            $table->string('link');
            $table->string('isDeleted')->nullable(); 
            $table->string('isBlocked')->nullable();  

            $table->unsignedBigInteger('addedById')->nullable();
            $table->foreign("addedById")->references("id")->on("users");

            $table->unsignedBigInteger('updatedById');
            $table->foreign("updatedById")->references("id")->on("users");

            $table->string('status')->default('active');
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
        Schema::dropIfExists('telegramLinks');
    }
};
