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

$('#node_select').change(() => {
   let nodeId = $('#node_select').val();
   if (myChart) {
       myChart.destroy();
   }
   loadData(nodeId);
});


function loadData(nodeId)
{
    $.ajax({
        type: 'GET',
        url: `/api/sensordata/${nodeId}`,
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
                        }
                    }
                }
            });
        }
    });
}

function createData(httpResponse) {
    let labels = [];
    let humidityData = [];
    let lightData = [];
    let tempData = [];
    let diff1Data = [];
    let diff2Data = [];
    let dataArray = Array.from(httpResponse.data);
    dataArray.forEach((entry) => {
        humidityData.push(entry.data.humidity_external);
        lightData.push(entry.data.light_external);
        tempData.push(entry.data.temp_external);
        diff1Data.push(entry.data.differential_potenial_ch1);
        diff2Data.push(entry.data.differential_potenial_ch2);
        labels.push(entry.date);
    });
    return {
        labels: labels,
        datasets: [{
            borderColor: colours[0],
            tension: 0.1,
            label: 'Humidity External',
            data: humidityData,
            borderWidth: 1
        }, {
            borderColor: colours[1],
            tension: 0.1,
            label: 'Temp External',
            data: tempData,
            borderWidth: 1
        }, {
            borderColor: colours[2],
            tension: 0.1,
            label: 'Light External',
            data: lightData,
            borderWidth: 1
        }, {
            borderColor: colours[3],
            tension: 0.1,
            label: 'Diff. Potential CH1',
            data: diff1Data,
            borderWidth: 1
        }, {
            borderColor: colours[4],
            tension: 0.1,
            label: 'Diff. Potential CH2',
            data: diff2Data,
            borderWidth: 1
        },]
    };
}


$(document).ready(function() {
    $('.js-example-basic-multiple').select2({
        maximumSelectionLength: 8
    });
    $('.js-example-basic-single').select2();
    $('#date_range').on('select2:select', function (e) {
        if (e.target.value !== 'latest') {
            var toastLiveExample = document.getElementById('liveToast');
            var toast = new bootstrap.Toast(toastLiveExample);
            toast.show();
        }
    });
    loadData();
});
