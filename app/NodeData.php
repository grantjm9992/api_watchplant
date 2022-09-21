<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NodeData extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'node_id',
        'temp_external',
        'light_external',
        'humidity_external',
        'differential_potenial_ch1',
        'differential_potenial_ch2',
        'rf_power_emission',
        'transpiration',
        'air_pressure',
        'soil_moisture',
        'soil_temperature',
        'data',
        'date'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array'
    ];
}
