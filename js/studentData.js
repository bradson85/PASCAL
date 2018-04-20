$(document).ready(function () {

    if (document.URL.indexOf("studentData.php") >= 0) {
        getStudentPageData();
        getStudentTableData();
    }

    $(document).on("change", "#graphstudentoption", function () {
        var studentChoice = $('#studentSelect :selected').val();
        getDashboardPageData("student", studentChoice);

    });

    $(document).on("change", "#dashboard", function () {
        var classChoice = $('#dashboard :selected').val();
        getDashboardTableData('classTable', classChoice);

    });

    $(document).on("change", "#adminClassList", function () {
        var classChoice = $('#adminClassList :selected').val();
        getDashboardTableData('classTable', classChoice);
    });

    $(document).on("change", "#graphclassoption", function () {
        var classChoice = $('#graphClassList :selected').val();
        getDashboardPageData('classTable', classChoice);

    });

    $(document).on("change", "#selcat", function () {
        var categoryChoice = $('#selcat :selected').val();
       loadWordStatTable(categoryChoice);

    });

    function loadWordStatTable(catChoice) {
        $.ajax({
            url: "php/inc.wordData.php",
            method: "POST",
            data: {
                categoryChoice: catChoice
            },
            success: function (data) {
                console.log(data);
                $("#wordStatTable").html(data); 
            }
        });
    }

        function getDashboardTableData(type, choice) {
            $.ajax({
                url: "php/inc-studentData.php",
                method: "POST",
                data: {
                    type: type,
                    choice: choice
                },
                success: function (data) {
                    var results = JSON.parse(data);
                    var tableString = "";
                    $.each(results, function (key, value) {
                        if (key === "name") {
                            label = value;
                        } else {
                            name = key;
                            tableString += buildDashboardTableHtml(name, results[name]);
                        }
                    });
                    $("#dataTableAdmin tbody").html(tableString);
                    $("#dataTableTeach tbody").html(tableString);
                }
            });

        }
        //  class='studentLink' id='$email'><td><u class='text-success'>$studentName</u></td>
        function buildDashboardTableHtml(student, studentData) {
            string = "<tr class='studentLink' id ='" + studentData['email'] + "'><td <u class='text-success'>" + student + "</u></td>" +
                "<td>" + studentData['gradeLevel'] + "</td>" +
                "<td>" + studentData['average'] + "</td>" +
                "<td>" + studentData['totalmatched'] + "/" + studentData['totaltried'] + "</td>" +
                "<td>" + studentData['bestScore'] + "</td>" +
                "<td>" + studentData['lastTest'] + "</td></tr>";

            return string;
        }

        function buildStudentDataTableHtml(studentData, assessmentName, testScore) {
            string = "<tr><td>" + assessmentName + "</td>" +
                "<td>" + studentData['category'] + "</td>" +
                "<td>" + testScore + "/20 </td></tr>";

            return string;
        }

        function getStudentTableData() {
            $.ajax({
                url: "php/inc-studentData.php",
                method: "POST",
                data: {
                    studentTableData: ""
                },
                success: function (data) {
                    table = "";
                    var json = $.parseJSON(data);
                    tests = json['testScores'];
                    $.each(tests, function (key, value) {
                        table += buildStudentDataTableHtml(json, key, value);
                    });

                    $('#dataTableStudentAssess tbody').html(table);
                    $("#studentName").text(json['name'] + "'s Assessment Data");
                }
            });
        }

        function getStudentPageData() {
            $.ajax({
                url: "php/inc-studentData.php",
                method: "POST",
                data: {
                    studentData: ""
                },
                success: function (data) {
                    // console.log(data);
                    var json = $.parseJSON(data);
                    data = [];
                    labels = [];
                    controlline = [];
                    $.each(json, function (key, value) {
                        if (key === "name") {
                            name = value;
                        } else {
                            labels.push(key);
                            data.push(value);
                            controlline.push(10);
                        }

                    });

                    buildlineChart("#studentChart", labels, data, name, controlline);
                }
            });

        }

        function getDashboardPageData(type, choice) {
            $.ajax({
                url: "php/inc-studentData.php",
                method: "POST",
                data: {
                    type: type,
                    choice: choice
                },
                success: function (data) {
                    // console.log(data);
                    var json = $.parseJSON(data);
                    data = [];
                    labels = [];
                    controlline = [];
                    $.each(json, function (key, value) {
                        if (key === "name") {
                            name = value;
                        } else {
                            labels.push(key);
                            data.push(value);
                            controlline.push(10);
                        }

                    });

                    buildlineChart("#dashboardChart", labels, data, name, controlline);
                }
            });

        }


        function buildlineChart(what, labels, data, name, control) {
            var ctx = $(what);
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Assessment Results out of 20',
                        data: data,
                        fill: false,
                        borderWidth: 4,
                        borderColor: 'red',
                        pointBackgroundColor: 'black',
                        pointBorderWidth: 4
                    }, {
                        label: '50 percent line',
                        data: control,
                        fill: false,
                        borderWidth: 4,
                        borderColor: 'blue',
                        borderDash: [10, 5],
                        pointBackgroundColor: 'black',
                        pointBorderWidth: 4
                    }]
                },
                options: {

                    title: {
                        display: true,
                        text: name + '\'s Assessment Scores',
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
                                min: 0,
                                beginAtZero: true
                            }
                        }],
                        yAxes: [{
                            scaleLabel: {
                                display: true,
                                labelString: 'Assessment Results'
                            },
                            ticks: {
                                max: 20,
                                beginAtZero: true
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