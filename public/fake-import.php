<?php
    $date = new \DateTime();
    $data = array(
        'node_handle' => 'test_node',
        'temp_external' => rand(30, 100),
        'light_external' => rand(30, 100),
        'humidity_external' => rand(30, 100),
        'differential_potenial_ch1' => rand(30, 100),
        'differential_potenial_ch2' => rand(30, 100),
        'rf_power_emission' => rand(30, 100),
        'transpiration' => rand(30, 100),
        'air_pressure' => rand(30, 100),
        'soil_moisture' => rand(30, 100),
        'soil_temperature' => rand(30, 100),
        'date' => $date->format('Y-m-d H:i:s')
    );
    $ch = @curl_init();
    @curl_setopt($ch, CURLOPT_POST, true);
    @curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    @curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    @curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api/sensordata');
	@curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    @curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json'
    ));
    @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    @curl_exec($ch);
    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
        var_dump($error_msg);
    }
curl_close($ch);
?>
