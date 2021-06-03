<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Hello, world!</title>
    <style>
        #myChart {
            max-width: 100vw;
            max-height: 100vh;
        }

    </style>
</head>

<body>
    <h1>Hello, world!</h1>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-lg-6">
                <canvas id="myChart" width="400" height="400"></canvas>
            </div>
            <div class="col-12 col-lg-6">
                <canvas id="comparative"></canvas>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.3.2/dist/chart.min.js"></script>
    <script>
        function randomRGB() {
            var o = Math.round,
                r = Math.random,
                s = 255;
            return 'rgba(' + o(r() * s) + ',' + o(r() * s) + ',' + o(r() * s) + ')';
        }
        $.ajax({
            type: 'GET',
            url: '/api/sensordata/test_node?data_type=humidity_external',
            success: (response) => {
                console.log(response.data);
                var labels = [];
                var data = [];
                var dataArray = Array.from(response.data);
                dataArray.forEach((entry) => {
                    data.push(entry.humidity_external);
                    labels.push(entry.date);
                });
                var ctx = document.getElementById('myChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            borderColor: 'rgb(75, 192, 192)',
                            tension: 0.1,
                            label: 'Humidity External',
                            data: data,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: false
                            }
                        }
                    }
                });
            }
        });
        $.ajax({
            type: 'GET',
            url: '/api/sensordata/test_node',
            success: (response) => {
                console.log(response.data);
                var labels = [];
                var humidityData = [];
                var lightData = [];
                var tempData = [];
                var diff1Data = [];
                var diff2Data = [];
                var dataArray = Array.from(response.data);
                dataArray.forEach((entry) => {
                    humidityData.push(entry.humidity_external);
                    lightData.push(entry.light_external);
                    tempData.push(entry.temp_external);
                    diff1Data.push(entry.differential_potenial_ch1);
                    diff2Data.push(entry.differential_potenial_ch2);
                    labels.push(entry.date);
                });
                var ctx = document.getElementById('comparative').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            borderColor: randomRGB(),
                            tension: 0.1,
                            label: 'Humidity External',
                            data: humidityData,
                            borderWidth: 1
                        }, {
                            borderColor: randomRGB(),
                            tension: 0.1,
                            label: 'Temp External',
                            data: tempData,
                            borderWidth: 1
                        }, {
                            borderColor: randomRGB(),
                            tension: 0.1,
                            label: 'Light External',
                            data: lightData,
                            borderWidth: 1
                        }, {
                            borderColor: randomRGB(),
                            tension: 0.1,
                            label: 'Diff. Potential CH1',
                            data: diff1Data,
                            borderWidth: 1
                        }, {
                            borderColor: randomRGB(),
                            tension: 0.1,
                            label: 'Diff. Potential CH2',
                            data: diff2Data,
                            borderWidth: 1
                        }, ]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: false
                            }
                        }
                    }
                });
            }
        });

    </script>
</body>

</html>
