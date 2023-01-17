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

function resetZoomForChart()
{
    myChart.resetZoom();
}

Number.prototype.zeroPad = function() {
    return ('0'+this).slice(-2);
};
function getMultipleNodeSingleDatatypeData()
{
    let nodeIds = $('#nodes').val();
    let dataType = $('#data_type').val();
    if (myChart) {
        myChart.destroy();
    }
    $.ajax({
        type: 'POST',
        url:  `/api/sensordata-multiple?data_type=${dataType}`,
        data: {
            node_handles: nodeIds,
            date_range: $('#date_range').val()
        },
        success: (response) => {
            ajaxData = [];
            let labels = [];
            let dataArray = response.data;
            let i = 0;
            dataArray.forEach((dataRow) => {
                let data = [];
                dataRow['data'].forEach((entry) => {
                    let date = new Date(entry.date);
                    date = new Date(date.getTime() - (60 * 1000 * date.getTimezoneOffset()));
                    let formattedDate = `${date.getFullYear()}-${[date.getMonth() + 1].zeroPad()}-${date.getDate()} ${date.getHours()}:${date.getMinutes()}:${date.getSeconds().zeroPad()}`
                    data.push({x: formattedDate, y: entry.data[dataType]});
                });
                ajaxData.push({
                    borderColor: colours[i],
                    tension: 0.1,
                    label: dataRow['node_name'],
                    data: data,
                    borderWidth: 3
                });
                i++;

            });
            for (let nodeKey in dataArray) {
            }

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
                    plugins: {
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
                            },
                            pan: {
                                enabled: true,
                                modifierKey: 'ctrl',
                            }
                        },
                    }
                }
            });
        }
    });

}


$('#data_type, #nodes, #date_range').change(() => {
    getMultipleNodeSingleDatatypeData();
})

$(document).ready(function() {
    $('.js-example-basic-multiple').select2({
        maximumSelectionLength: 8,
        dropdownAutoWidth : true,
        width: 'auto'
    });
    $('.js-example-basic-single').select2({
        dropdownAutoWidth : true,
        width: 'auto'
    });
});
