
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

function createData(httpResponse) {
    var labels = [];
    var humidityData = [];
    var lightData = [];
    var tempData = [];
    var diff1Data = [];
    var diff2Data = [];
    var dataArray = Array.from(httpResponse.data);
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
        },]
    };
}