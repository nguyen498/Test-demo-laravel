<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleTranferDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_tranfer_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vehicle_tranfer_id');
            $table->integer('vehicle_station_detail_id');
            $table->integer('vehicle_id');
            $table->integer('type')->nullable();
            $table->integer('status');
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
        Schema::dropIfExists('vehicle_tranfer_details');
    }
}
