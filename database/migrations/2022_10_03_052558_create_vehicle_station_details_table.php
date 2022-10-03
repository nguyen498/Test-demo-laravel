<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleStationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_station_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vehicle_station_id');
            $table->integer('vehicle_id');
            $table->string('code');
            $table->string('floor');
            $table->string('area');
            $table->string('period');
            $table->integer('status');
            $table->integer('type')->nullable();
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
        Schema::dropIfExists('vehicle_station_details');
    }
}
