$(document).ready(function () {
    loadSchoolListSearch();

    $(document).on("change", "#school", function () {
        loadClassListSearch();
    });

    $(document).on("change", "#class", function () {
        loadStudentListSearch();
    });

    $(document).on("change", "#student", function () {
        unlockChoices(); 
    });

    $("#checkBoxes").on("change", function () {
        if ($('.form-check-input').is(':checked')) {  // if atleas one box is chekced

            $("#genReport").prop("disabled", false); // Activate Button
        }
        if (!$('.form-check-input').is(':checked')) {  //if no box is checked

            $("#genReport").prop("disabled", true); // De-Activate Button
        }
    });

    $(document).on("click", "#genReport", function () {
       outputStudentData(); 
    });

    function loadSchoolListSearch() {

        $.ajax({ 
            type: "POST",
            url: "php/inc.report.php",
            data: {
                schoolSelect: "selectSchool"
            },
            success: function (data) {
                $("#school").html(data);
            }
        });

    }

    function loadClassListSearch() {
        var schoolChoice = $('#school :selected').val();
        $.ajax({ 
            type: "POST",
            url: "php/inc.report.php",
            data: {
                classSelect: schoolChoice
            },
            success: function (data) {
                $("#class").html(data);
            }
        });

    }


    function loadStudentListSearch() {
        var classChoice = $('#class :selected').val();
        $.ajax({ 
            type: "POST",
            url: "php/inc.report.php",
            data: {
                studentSelect: classChoice
            },
            success: function (data) {
                $("#student").html(data);
            }
        });

    }


    function outputStudentData() {

        var studentChoice = $('#student :selected').val();
        var formatChoice =$('#format :selected').val();
        var classNumber =  $('#class :selected').val();
        var checkValues = [];
        $('#checkBoxes :checked').each(function() {
            checkValues.push($(this).val());
        });
        $.ajax({ // delete from database
            type: "POST",
            url: "php/inc.report.php",
            data: {
                scoreChoice: studentChoice,
                classNumber: classNumber,
                checkValues: checkValues,
                format: formatChoice
            },
            success: function (data) {
                console.log(data);
               // var json = $.parseJSON(data);
                
    
    }

});
    }


    function unlockChoices(){
for (let index = 1; index < 5; index++) {
    $("#inlineCheckbox"+index).prop("disabled", false); // Element(s) are now enabled.
}
        
    }

});