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
                            Comparative Graph
                        </div>
                        <div class="col-12 col-lg-4">
                            <label for="id_label_multiple" class="text-light mt-1">
                                Node
                                <select class="js-example-basic-single" name="node" id="node_select" style="width: 300px;">
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
                            <label for="date_range" class="text-light mt-1">
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
                    <canvas id="comparative"></canvas>
                </div>
            </div>
        </div>
    </div>
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
    <script src="/js/all-data.js"></script>
</body>
