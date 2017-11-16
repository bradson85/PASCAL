$(document).ready(function () {
    //get class of currently logged in user (if admin no class will be found)

    //populate the table based on the class, show hidden options if admin

    classID = 1;

    $.ajax({
        url: "php/inc.report.php",
        type: "GET",
        success: function(response){
            console.log(response);
            showSchools(response)
        }
    });


    $('#school').change(function () {
        console.log(document.getElementById('school').value);
        $.ajax({
            url: "php/inc.report.php",
            type: "GET",
            data: {
                school: document.getElementById('school').value
            },
            success: function(response){
                showClasses(response)
            }
        });
    });

    $('#class').change(function() {
        $.ajax({
            url: "php/inc.report.php",
            type: "GET",
            data: {
                class: document.getElementById('class').value,
                school: document.getElementById('school').value
            },
            success: function(response){
                buildTable(response)
            }
        });
    })
    

    function showClasses(result) {
        console.log(result);
        var obj = JSON.parse(result);
        $('#class').removeAttr('disabled');
        $('#class').append(new Option("Select a Class", "None"));
        obj.forEach(function(element) {
            $('#class').append(new Option(element.name, element.name));
        }, this);
    }

    function showSchools(result) {
        var obj = JSON.parse(result);
        $('#school').append(new Option("Select a School", "None"));
        obj.forEach(function(element) {
            $('#school').append(new Option(element.name, element.name));
        }, this);
    }

    function buildTable(result) {
        console.log(result);
    }

});