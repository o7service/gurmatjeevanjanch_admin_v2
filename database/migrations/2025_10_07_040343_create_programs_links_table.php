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
        Schema::create('programs_links', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('autoId')->nullable();
            $table->string('title');
            $table->text('address');
            $table->text('details');
            $table->text('imageUrl');
            $table->text('mapLink');
            $table->date('startDate');
            $table->date('endDate');
            $table->bigInteger('contactNumber1');
            $table->bigInteger('contactNumber2');

            $table->boolean('isDeleted')->default(false);
            $table->boolean('isBlocked')->default(false);
            $table->unsignedBigInteger('addedById')->nullable();
            $table->foreign("addedById")->references("id")->on("users");
            $table->unsignedBigInteger('updatedById');
            $table->foreign("updatedById")->references("id")->on("users");
            $table->boolean('status')->default(true);
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
        Schema::dropIfExists('programs_links');
    }
};
