$(document).ready(function () {
    var ctx = $("#myChart");
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ["38", "40", "45", "46"],
        datasets: [{
            label: 'Assessment Results out of 25',
            data: [12, 19, 3, 5],
            fill: false, 
            borderWidth: 4,
            borderColor: 'red',
            pointBackgroundColor: 'black',
            pointBorderWidth: 4
        }]
    },
    options: {
        
        title: {
            display: true,
            text: 'Jane Student\'s Test Scores',
            fontSize: 24
        },
        scales: {
            yAxes: [{
                scaleLabel: {
                    display: true,
                    labelString: 'Assessment Results'
                  },
                ticks: {
                    max:25,
                    beginAtZero:true
                }
            }]
        },
        elements: {
            line: {
                tension: 0, // disables bezier curves
            }
        }
    }
});
});