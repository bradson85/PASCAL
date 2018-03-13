$(document).ready(function () {

    if(document.URL.indexOf("studentData.php") >= 0){ 
        getStudentPageData();
        }

$(document).on("change", "#graphstudentoption", function () {
    var studentChoice = $('#studentSelect :selected').val();
    getDashboardPageData("student", studentChoice);

});

$(document).on("change", "#graphschooloption", function () {


});
$(document).on("change", "#graphclassoption", function () {
    var classChoice = $('#graphClassList :selected').val();
    getDashboardPageData('class',classChoice);

});

//getStudentPageData();
function getStudentPageData(){
    $.ajax({
        url: "php/inc-studentData.php",
        method: "POST",
        data:{ 
              studentData: ""
        }, 
        success: function (data) {
            var json = $.parseJSON(data);
            data =[];
            labels =[];
            controlline=[];
            $.each(json, function(key, value){
                   if(key==="name"){
                       name = value;
                   }else {
                       labels.push(key);
                        data.push(value);
                        controlline.push(12.5);
                }

                });
                 
                buildlineChart("#studentChart",labels,data ,name,controlline);
        }
    });

}

function getDashboardPageData(type,choice){
    $.ajax({
        url: "php/inc-studentData.php",
        method: "POST",
        data:{ 
             type: type,
              choice: choice
        }, 
        success: function (data) {
           // console.log(data);
            var json = $.parseJSON(data);
            data =[];
            labels =[];
            controlline=[];
            $.each(json, function(key, value){
                   if(key==="name"){
                       name = value;
                   }else {
                       labels.push(key);
                        data.push(value);
                        controlline.push(12.5);
                }

                });
                
                buildlineChart("#dashboardChart",labels,data ,name,controlline);
        }
    });

}


 function buildlineChart(what,labels,data ,name, control){   
    var ctx = $(what);
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Assessment Results out of 25',
            data: data,
            fill: false, 
            borderWidth: 4,
            borderColor: 'red',
            pointBackgroundColor: 'black',
            pointBorderWidth: 4
        },{
            label: '50 percent line',
            data: control,
            fill: false, 
            borderWidth: 4,
            borderColor: 'blue',
            borderDash: [10,5],
            pointBackgroundColor: 'black',
            pointBorderWidth: 4
        }]
    },
    options: {
        
        title: {
            display: true,
            text: name+'\'s Test Scores',
            fontSize: 24
        },
        scales: {
            xAxes: [{
                scaleLabel: {
                    display: true,
                    labelString: 'Assessment ID',
                    zeroLineWidth: 2
                  },
                  ticks: {
                    min:0,
                    beginAtZero:true
                }
            }],
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
 }
});