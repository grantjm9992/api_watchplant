let nodeId;
let myChart;
const colours = [
    '#88c098',
    '#f10b51',
    '#99c236',
    '#2d9358',
    '#3c7dc6',
    '#db9611'
];

$('#node_select, #date_range').change(() => {
   let nodeId = $('#node_select').val();
   let date_range = $('#date_range').val();
   if (myChart) {
       myChart.destroy();
   }
   loadData(nodeId, date_range);
});

function randomRGB() {
    var o = Math.round,
        r = Math.random,
        s = 255;
    return 'rgba(' + o(r() * s) + ',' + o(r() * s) + ',' + o(r() * s) + ')';
}


function loadData(nodeId, date_range)
{
    $.ajax({
        type: 'GET',
        url: `/api/sensordata/${nodeId}?date_range=${date_range}`,
        success: (response) => {
            let ctx = document.getElementById('comparative').getContext('2d');
            myChart = new Chart(ctx, {
                type: 'line',
                data: createData(response),
                options: {
                    scales: {
                        y: {
                            beginAtZero: false,
                            ticks: {
                                color: '#999999',
                                family: 'open sans',
                                weight: 200
                            }
                        },
                        x: {
                            beginAtZero: false,
                            ticks: {
                                color: '#999999',
                                family: 'open sans',
                                weight: 200
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: {
                                font: {
                                    family: 'open sans',
                                    weight: 200
                                }
                            }
                        },
                        zoom: {
                            zoom: {
                                wheel: {
                                    enabled: true,
                                },
                                pinch: {
                                    enabled: true
                                },
                                drag: {
                                    enabled: true
                                },
                                mode: 'xy',
                            }
                        },
                        pan: {
                            enabled: true,
                            modifierKey: 'ctrl',
                        }
                    }
                }
            });
        }
    });
}

function resetZoomForChart()
{
    ctx = document.getElementById('myChart').getContext('2d');
    ctx.resetZoom();
}

function createData(httpResponse) {
    let dataArray = httpResponse['data'];
    let ajaxData = [];
    let dataFields = dataArray['fields'];
    console.log(httpResponse['data']);
    dataFields.forEach((field) => {
        let fieldData = [];
        dataArray['data'].forEach((entry) => {
            fieldData.push({x: entry.date, y: entry.data[field['handle']]});
        });
        ajaxData.push({
            borderColor: randomRGB(),
            tension: 0.1,
            label: field['name'],
            data: fieldData,
            borderWidth: 3
        })
    });
    return {
        datasets: ajaxData
    };
}


$(document).ready(function() {
    $('.js-example-basic-single').select2();
    let nodeId = $('#node_select').val();
    let date_range = $('#date_range').val();
    loadData(nodeId, date_range);
});
