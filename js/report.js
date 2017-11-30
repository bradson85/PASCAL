$(document).ready(function () {
    //get class of currently logged in user (if admin no class will be found)

    //populate the table based on the class, show hidden options if admin

    classID = 1;

    $.ajax({
        url: "php/inc.report.php",
        type: "GET",
        dataType: "json",
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
            dataType: "json",
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
            dataType: "json",
            data: {
                class: document.getElementById('class').value,
                school: document.getElementById('school').value
            },
            success: function(response){
                console.log(response[0])
                buildTable(response)
            }
        });
    })
    

    function showClasses(result) {
        console.log(result);
        var obj = result;
        $('#class').removeAttr('disabled');
        $('#class').append(new Option("Select a Class", "None"));
        obj.forEach(function(element) {
            $('#class').append(new Option(element.name, element.name));
        }, this);
    }

    function showSchools(result) {
        var obj = result;
        $('#school').append(new Option("Select a School", "None"));
        obj.forEach(function(element) {
            $('#school').append(new Option(element.name, element.name));
        }, this);
    }

    function buildTable(result) {
        
        console.log(result);
        let tempID = -1;
        let tempStr = "";
        result.push({ID: -1});
        for(let i = 0; i < result.length - 1; i++) {
            console.log(i);
            if(i === 0){
                tempID = result[i].ID;
                tempStr = "<tr><td>" + result[i].ID + "</td>";
            }

            if(result[i].ID === tempID){ 
                tempStr += "<td>" + result[i].correct + "</td>";
                
            }
            console.log(i === result.length - 1);
            if(result[i+1].ID !== tempID || i === (result.length - 1)){
                tempStr += "</tr>";
                $("#results").append(tempStr);
                tempID = result[i+1].ID;
                tempStr = "<tr><td>" + result[i+1].ID + "</td>";
            }
            
        }
    }

});