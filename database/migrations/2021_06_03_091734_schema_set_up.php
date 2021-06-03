<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SchemaSetUp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nodes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('handle', 255)->unique();
            $table->string('name')->nullable();
            $table->string('location')->nullable();
            $table->timestamps();
        });

        Schema::create('node_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('node_id');
            $table->decimal('temp_external', 20, 10)->nullable();
            $table->decimal('light_external', 20, 10)->nullable();
            $table->decimal('humidity_external', 20, 10)->nullable();
            $table->decimal('differential_potenial_ch1', 20, 10)->nullable();
            $table->decimal('differential_potenial_ch2', 20, 10)->nullable();
            $table->decimal('rf_power_emission', 20, 10)->nullable();
            $table->decimal('transpiration', 20, 10)->nullable();
            $table->decimal('air_pressure', 20, 10)->nullable();
            $table->decimal('soil_moisture', 20, 10)->nullable();
            $table->decimal('soil_temperature', 20, 10)->nullable();
            $table->dateTime('date')->nullable();
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
        Schema::dropIfExists('nodes');
        Schema::dropIfExists('node_data');
    }
}
