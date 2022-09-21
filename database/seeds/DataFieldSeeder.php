<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataFieldSeeder extends Seeder
{
    protected const NAME = 'name';
    protected const HANDLE = 'handle';

    private const INSERT_DATA = [
        [
            self::NAME => 'External temperature',
            self::HANDLE => 'temp_external'
        ],
        [
            self::NAME => 'External light',
            self::HANDLE => 'light_external'
        ],
        [
            self::NAME => 'External humidity',
            self::HANDLE => 'humidity_external'
        ],
        [
            self::NAME => 'Differential potential CH1',
            self::HANDLE => 'differential_potenial_ch1'
        ],
        [
            self::NAME => 'Differential potential CH2',
            self::HANDLE => 'differential_potential_ch2'
        ],
        [
            self::NAME => 'RF Power emission',
            self::HANDLE => 'rf_power_emission'
        ],
        [
            self::NAME => 'Transpiration',
            self::HANDLE => 'transpiration'
        ],
        [
            self::NAME => 'Air pressure',
            self::HANDLE => 'air_pressure'
        ],
        [
            self::NAME => 'Soil moisture',
            self::HANDLE => 'soil_moisture'
        ],
        [
            self::NAME => 'Soil temperature',
            self::HANDLE => 'soil_temperature'
        ]
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        foreach (self::INSERT_DATA as $row) {
            DB::table('data_field')
                ->insert([
                    self::HANDLE => $row[self::HANDLE],
                    self::NAME => $row[self::NAME]
                ]);
        }
    }
}
