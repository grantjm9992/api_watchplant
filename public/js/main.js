let ajaxData = null;
let ctx;
let myChart;

const colours = [
    '#88c098',
    '#f10b51',
    '#99c236',
    '#2d9358',
    '#3c7dc6',
    '#db9611'
];

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

function getMultipleNodeSingleDatatypeData()
{
    let nodeIds = $('#nodes').val();
    if (myChart) {
        myChart.destroy();
    }
    $.ajax({
        type: 'POST',
        url: '/api/sensordata-multiple?data_type=humidity_external',
        data: {
            node_ids: nodeIds
        },
        success: (response) => {
            ajaxData = [];
            let labels = [];
            let dataArray = response.data;
            let dataType = $('#data_type').val();
            let i = 0;
            for (let nodeKey in dataArray) {
                let data = [];
                dataArray[nodeKey].forEach((entry) => {
                    data.push(entry[dataType]);
                    if (i === 0) {
                        labels.push(entry.date);
                    }
                });
                console.log(data);
                ajaxData.push({
                    borderColor: colours[i],
                    tension: 0.1,
                    label: nodeKey,
                    data: data,
                    borderWidth: 1
                });
                i++;
            }

            console.log(ajaxData);

            ctx = document.getElementById('myChart').getContext('2d');

            myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: ajaxData
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: false
                        }
                    },
                }
            });
        }
    });

}


$('#data_type, #nodes').change(() => {
    getMultipleNodeSingleDatatypeData();
})

$.ajax({
    // type: 'GET',
    // url: '/api/sensordata/test_node',
    // success: (response) => {
    //     var ctx = document.getElementById('comparative').getContext('2d');
    //     var myChart = new Chart(ctx, {
    //         type: 'line',
    //         data: createData(response),
    //         options: {
    //             scales: {
    //                 y: {
    //                     beginAtZero: false
    //                 }
    //             }
    //         }
    //     });
    // }
});
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
});
