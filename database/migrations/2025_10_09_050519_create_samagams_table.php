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
        Schema::create('samagams', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('autoId')->nullable();
            $table->string('organizerName');
            $table->string('address');
            $table->string('details');
            $table->string('mapLink');
            $table->string('phone');
            $table->string('email');
            $table->date('startDate');
            $table->date('endDate');

            $table->boolean('isDeleted')->default(false);
            $table->boolean('isBlocked')->default(false);
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
        Schema::dropIfExists('samagams');
    }
};
