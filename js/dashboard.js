$(document).ready(function () {

    loadClassListSearch();


    function loadClassListSearch(){

        $.ajax({ // delete from database
            type: "POST",
            url: "php/inc-dashboard-functions.php",
            data: {
                option: "selectClass"
            },
            success: function (data) {
                console.log(data);
                var rows = "<td> Select Which Class:</td>" + data;
                $("#sort_body").html(rows);
            }
        });
       
    }



    
});