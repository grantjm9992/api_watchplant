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
   let node_handle = $('#node_select').val();
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
                            beginAtZero: false
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
        humidityData.push(entry.humidity_external);
        lightData.push(entry.light_external);
        tempData.push(entry.temp_external);
        diff1Data.push(entry.differential_potenial_ch1);
        diff2Data.push(entry.differential_potenial_ch2);
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
