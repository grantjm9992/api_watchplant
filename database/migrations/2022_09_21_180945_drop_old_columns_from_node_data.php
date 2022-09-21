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
        Schema::table('node_data', function (Blueprint $table) {
            $table->dropColumn('temp_external');
            $table->dropColumn('light_external');
            $table->dropColumn('humidity_external');
            $table->dropColumn('differential_potenial_ch1');
            $table->dropColumn('differential_potenial_ch2');
            $table->dropColumn('rf_power_emission');
            $table->dropColumn('transpiration');
            $table->dropColumn('air_pressure');
            $table->dropColumn('soil_moisture');
            $table->dropColumn('soil_temperature');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('node_data', function (Blueprint $table) {
            //
        });
    }
};
