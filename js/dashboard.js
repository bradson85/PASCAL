$(document).ready(function () {


if($('#dataTableTeach').length){

    loadClassListSearch();
}else loadSchoolListSearch();


$(document).on("change", "#classChoice",function(){
    loadClassList();
});



    function loadClassListSearch(){

        $.ajax({ // delete from database
            type: "POST",
            url: "php/inc-dashboard-functions.php",
            data: {
                classSelect: "selectClass"
            },
            success: function (data) {
                var rows = "<td> Select Which Class:</td>" + data;
                $("#sort_body").html(rows);
            }
        });
       
    }


    function loadSchoolListSearch(){

        $.ajax({ // delete from database
            type: "POST",
            url: "php/inc-dashboard-functions.php",
            data: {
                schoolSelect: "selectSchool"
            },
            success: function (data) {
                console.log(data);
                var rows = "<td> Select Which School:</td>" + data;
                $("#sort_body").html(rows);
            }
        });
       
    }


    function loadClassList(){
        
    var classChoice = $('#classChoice :selected').val();
        $.ajax({ // delete from database
            type: "POST",
            url: "php/inc-dashboard-functions.php",
            data: {
                classChoice: classChoice
            },
            success: function (data) {
                console.log(data);
                $("#dataTableTeach tbody").html(data);
            }
        });
       
    }



    
});