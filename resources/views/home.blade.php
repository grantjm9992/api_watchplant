<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/style.css">
    <title>Dashboard</title>
</head>
<body>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12 col-lg-4">
                            <label for="id_label_multiple" class="mt-1">
                                Nodes
                                <select class="js-example-basic-multiple" name="node[]" multiple="multiple" id="nodes" style="width: 300px;">
                                    <?php
                                    foreach ($nodes as $node) {
                                        ?>
                                    <option value="<?php echo $node['handle']; ?>"><?php echo $node['name']; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </label>
                        </div>
                        <div class="col-12 col-lg-4">
                            <label for="date_range" class="mt-1">
                                Data type
                                <select class="js-example-basic-single" name="data_type" id="data_type" style="width: 300px;">
                                    <option value="humidity_external" selected>Humidity external</option>
                                    <option value="light_external">Light external</option>
                                    <option value="temp_external">Temp external</option>
                                    <option value="differential_potenial_ch1">Differential potential CH1</option>
                                    <option value="differential_potenial_ch2">Differential potential CH2</option>
                                </select>
                            </label>
                        </div>
                        <div class="col-12 col-lg-4">
                            <label for="date_range" class="mt-1">
                                Date range
                                <select class="js-example-basic-single" name="date" id="date_range" style="width: 300px;">
                                    <option value="latest" selected>Latest data</option>
                                    <option value="month">Last month</option>
                                    <option value="six_months">Last 6 months</option>
                                </select>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="myChart" width="400" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Error</strong>
                <small>11 mins ago</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                No data found for this date range
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="/js/main.js"></script>
    <script>

    </script>
</body>

</html>
