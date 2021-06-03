<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">


    <title>Hello, world!</title>
    <style>
        #myChart {
            max-width: 100vw;
            max-height: 80vh;
        }

        .card {
            background-color: rgb(30, 41, 59);
            border-color: rgb(30, 41, 59);
            color: #fff;
        }

        .card-header {
            background-color: rgb(30, 41, 59);
            border-color: rgb(30, 41, 59);
            color: #fff;
        }

        .card-body {
            background-color: rgb(30, 41, 59);
            border-color: rgb(30, 41, 59);
            color: #fff;
        }

    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">WatchPlant</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02"
            aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
            <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container-fluid pt-5">
        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        Humidity External
                    </div>
                    <div class="card-body">
                        <canvas id="myChart" width="400" height="400"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 mt-5 mt-lg-0">
                <div class="card">
                    <div class="card-header">
                        Comparative Graph
                    </div>
                    <div class="card-body">
                        <canvas id="comparative"></canvas>
                    </div>
                </div>
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
    <script src="https://cdn.jsdelivr.net/npm/hammerjs@2.0.8"></script>
    <script src="/js/chartjs-plugin-zoom.min.js"></script>
    <script>
        function letterCSS(i, length) {
            let hue = Math.floor(i / length * 341); // between 0 and 340
            let saturation = 100;
            let lightness = 50;

            // color adjustment:
            if (hue > 215 && hue < 265) {
                const gain = 20;
                let blueness = 1 - Math.abs(hue - 240) / 25;
                let change = Math.floor(gain * blueness);
                lightness += change;
                saturation -= change;
            }
            return hsl = `hsl(${hue}, ${saturation}%, ${lightness}%)`;
        }

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
                            borderColor: 'rgb(75, 192, 255)',
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
                        },
                        /*
                                                plugins: {
                                                    zoom: {
                                                        zoom: {
                                                            wheel: {
                                                                enabled: true,
                                                            },
                                                            pinch: {
                                                                enabled: true
                                                            },
                                                            mode: 'xy',
                                                        }
                                                    }
                                                }*/
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
                            borderColor: letterCSS(1, 5),
                            tension: 0.1,
                            label: 'Humidity External',
                            data: humidityData,
                            borderWidth: 1
                        }, {
                            borderColor: letterCSS(2, 5),
                            tension: 0.1,
                            label: 'Temp External',
                            data: tempData,
                            borderWidth: 1
                        }, {
                            borderColor: letterCSS(3, 5),
                            tension: 0.1,
                            label: 'Light External',
                            data: lightData,
                            borderWidth: 1
                        }, {
                            borderColor: letterCSS(4, 5),
                            tension: 0.1,
                            label: 'Diff. Potential CH1',
                            data: diff1Data,
                            borderWidth: 1
                        }, {
                            borderColor: letterCSS(5, 5),
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
                        },
                        legend: {
                            fontColor: "white",
                        },
                        scales: {
                            xAxes: [{
                                ticks: {
                                    fontColor: "white",
                                }
                            }],
                            yAxes: [{
                                ticks: {
                                    fontColor: "white",
                                    beginAtZero: true,
                                    maxTicksLimit: 5,
                                    stepSize: Math.ceil(250 / 5),
                                    max: 250
                                }
                            }]
                        }
                    }
                });
            }
        });

    </script>
</body>

</html>
