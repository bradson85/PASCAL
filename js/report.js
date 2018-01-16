$(document).ready(function () {
    //get class of currently logged in user (if admin no class will be found)

    //populate the table based on the class, show hidden options if admin

    var classID = 1;
    var pages = 1;
    var currPage = pages;
    var headers = [];
    var retData = [];
    // Special thanks to arminrosu for this code from StackOverflow.
    // https://stackoverflow.com/questions/3514784/what-is-the-best-way-to-detect-a-mobile-device-in-jquery
    var mobile = /Mobi/i.test(navigator.userAgent);
    $.ajax({
        url: "php/inc.report.php",
        type: "GET",
        dataType: "json",
        success: function(response){
            console.log(response);
            showSchools(response)
        }
    });

    $('#back').click(function() {
        if(currPage > 1){
            currPage--;
            addTableHead(headers);
            buildTable(retData);
        }
    });

    $('#forward').click(function() {
        if(currPage < pages){
            currPage++;
            addTableHead(headers);
            buildTable(retData);
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
                header: "true"
            },
            success: function(response){
                // set the number of pages for breaking up the table content.
                // Shows 4 assessment results per page for mobile, 6 for everything else.
                if(mobile)
                    pages = Math.ceil(response.length / 4);
                else
                    pages = Math.ceil(response.length / 6);
                console.log(response[0]);
                headers = response;
                addTableHead(response);
            }
        });

        $.ajax({
            url: "php/inc.report.php",
            type: "GET",
            dataType: "json",
            data: {
                class: document.getElementById('class').value,
                school: document.getElementById('school').value
            },
            success: function(response){
                console.log(response[0]);
                retData = response;
                buildTable(response);
            }
        });
    });
    

    function showClasses(result) {
        $("#class").empty();
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

    function addTableHead(arr) {
        $("results-head").empty();
        console.log(arr);
        let tempStr = "<td>Student ID</td>";
        let num = 0;
        if(mobile)
            num = 4;
        else
            num = 6;
        let end = arr.length;
        if(num * currPage < arr.length)
            end = (num * currPage);
        console.log("end: " + end);
        for(let i = num * (currPage-1); i < end; i++) {
            tempStr += "<td>" + arr[i].start + "</td>";
        }

        
        console.log(tempStr);
        $("#results-head").html(tempStr);
    }

    function buildTable(result) {
        $("#results").empty();
        console.log(result);
        let tempID = -1;
        let tempStr = "";

        let num = 0;
        if(mobile)
            num = 4;
        else
            num = 6;
        let end = result.length - 1;
        if(num * currPage < result.length - 1)
            end = (num * currPage);

        result.push({ID: -1});
        for(let i = num * (currPage - 1); i < end; i++) {
            console.log(i);
            if(i === 0){
                tempID = result[i].ID;
                tempStr = "<tr><td>" + result[i].ID + "</td>";
            }

            if(result[i].ID === tempID){ 
                tempStr += "<td>" + ((result[i].correct / 20) * 100) + "%</td>";
                
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