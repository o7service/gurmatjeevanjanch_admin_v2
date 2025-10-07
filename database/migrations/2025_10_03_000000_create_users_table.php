<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('autoId')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->bigInteger('phone');
            $table->integer('userType')->default(2);
            $table->boolean('isDeleted')->default(false);
            $table->boolean('isBlocked')->default(false);
            $table->boolean('status')->default(true);

            $table->unsignedBigInteger('addedById')->nullable();
            $table->foreign("addedById")->references("id")->on("users");

            $table->unsignedBigInteger('updatedById')->nullable();
            $table->foreign("updatedById")->references("id")->on("users");

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
        Schema::dropIfExists('users');
    }
};
