<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('driver_id');
            $table->string('start_point');
            $table->string('end_point');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->tinyInteger('seats_available');
            $table->string('car_description');
            $table->string('trip_info');
            $table->boolean('pets_allowed');
            $table->boolean('kids_allowed');
            $table->timestamps();

            $table->foreign('driver_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trips');
    }
}
