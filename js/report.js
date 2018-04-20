$(document).ready(function () {
    $('#schoolArea').hide();
    $('#classArea').hide();
    $('#reportCustomiztion').hide();
    $('#studentEntireArea').hide();
    $('#categoryArea').hide();
    loadTypeListSearch();

    $(document).on("change", "#datatype", function () {
        $('#classArea').hide();
        $('#studentEntireArea').hide();
        $('#reportCustomiztion').hide();
        var typeChoice = $('#datatype :selected').val();
        $('#student :selected').val(0);
        if (typeChoice == "studentwordScores") {
            $('#schoolArea').hide();
            $('#categoryArea').show();
            loadCatListSearch();
        } else if (typeChoice == "allwordScores") {
            $('#schoolArea').hide();
            $('#categoryArea').show();
            loadCatListSearch();
        } else if (typeChoice == "studentScores") {
            $('#schoolArea').show();
            $('#categoryArea').hide();
            loadSchoolListSearch();
        } else if (typeChoice == "assessmentScores") {
            $('#schoolArea').show();
            $('#categoryArea').hide();
            loadSchoolListSearch();
        }

    });

    $(document).on('change', ".check2", function () {
        var studentChoice = $('#student :selected').val();
        if (studentChoice == -1) {
            $('.check2').not(this).prop('checked', false);
        }
    });

    $(document).on("change", "#selcat", function () {
        choice = $('#datatype :selected').val();
        $('#classArea').hide();
        $('#studentEntireArea').hide();
        $("#genReport").prop("disabled", true);
        $('#reportCustomiztion').hide();
        if (choice == "studentwordScores") {
            $('#schoolArea').show();
            loadSchoolListSearch();
        } else if (choice == "allwordScores") {
            $('#schoolArea').hide();
            $('#reportCustomiztion').show();
            loadCheckBoxes();
        } else if (choice == "studentScores") {

        }

    });

    $(document).on("change", "#school", function () {
        loadClassListSearch();
        $('#classArea').show();
        $('#studentEntireArea').hide();
        $('#reportCustomiztion').hide();
    });

    $(document).on("change", "#class", function () {
        $('#reportCustomiztion').hide();
        $('#studentEntireArea').show();
        loadStudentListSearch();
    });

    $(document).on("change", "#student", function () {
        var studentOption = $('#student :selected').val();
        var option = $('#datatype :selected').val();
        if(option == "assessmentScores"){
            $('#reportCustomiztion').hide();
            $("#genReport").prop("disabled", false);
        }else if(studentOption != -1 && option == "studentwordScores"){
            $('#reportCustomiztion').hide();
            $("#genReport").prop("disabled", false);
        }else {$('#reportCustomiztion').show();
        $("#checkBoxes").show();
        $("#genReport").prop("disabled", true);
        if ($('.check1').is(':checked')) {  // if atleas one box is chekced
            $("#genReport").prop("disabled", false); // Activate Button
        } else $("#genReport").prop("disabled", true);
        }
        loadCheckBoxes();
        unlockChoices();
    });

    $(document).on("change", "#secCheckBoxes", function () {
          if($("#secondCheckbox1").is(':checked')){ 
            $("#checkBoxes").hide() ;
            $("#genReport").prop("disabled", false);
        }else {$("#checkBoxes").show();
        if ($('.check1').is(':checked')) {  // if atleas one box is chekced
            $("#genReport").prop("disabled", false); // Activate Button
        } else $("#genReport").prop("disabled", true);
        }
    });

    $("#checkBoxes, #secCheckBoxes").on("change", function () {
        if ($('.check1').is(':checked') && $('.check2').is(':checked')) {  // if atleas one box is chekced

            $("#genReport").prop("disabled", false); // Activate Button
        }
        if (!$('.check1').is(':checked')|| !$('.check2').is(':checked')) {  //if no box is checked

            $("#genReport").prop("disabled", true); // De-Activate Button
        
       }
    });


    $(document).on("click", "#genReport", function () {
        outputData();
    });

   


    function loadCheckBoxes() {
        var optionchoice = $('#datatype :selected').val();
        var studentChoice = $('#student :selected').val();
        var checkboxTypes;
        var checkBoxValues;
        if (optionchoice == "studentwordScores") {
            checkBoxTypes = ['Show Average Score', 'Show Total Matches/Attempts', ' Show Category-Level', 'Show Defintion'];
            checkBoxValues = ['Average Score', 'Total Matches/Attempts', 'Category-Level', 'Defintion'];
        } else if (optionchoice == "allwordScores") {
            checkBoxTypes = ['Show Average Score', 'Show Total Matches/Attempts', 'Show Category-Level', 'Show Defintion'];
            checkBoxValues = ['Average Score', 'Total Matches/Attempts', 'Category-Level', 'Defintion'];
        } else if (optionchoice == "studentScores") {
            checkBoxTypes = ['Show Average Score', 'Show Total Matches/Attempts', 'Show GradeLevel', 'Show Best Score', 'Show Last Completed Assessment'];
            checkBoxValues = ['Average Score', 'Total Matches/Attempts', 'GradeLevel', 'Best Score', 'Last Completed Assessment'];
        } else if (optionchoice == "assessmentScores") {
            checkBoxTypes = [];
            checkBoxValues = [];
        }
        $.ajax({
            type: "POST",
            url: "php/inc.report.php",
            data: {
                checkBoxType: checkBoxTypes,
                checkBoxValues: checkBoxValues
            },
            success: function (data) {
                //console.log(data);
                $("#checkBoxes").html(data);
                $('#secCheckBoxes').html("<h6>Select One: &nbsp;</h6>" + loadSpecialWordCheckBox());
                if (studentChoice == -1 && optionchoice == "studentwordScores") {
                    $('#checkBoxes').prepend("<h6><br>AND<br>Select One or More:</h6> &nbsp;");
                    $('#secCheckBoxes').show();
                } else {
                    $('#checkBoxes').prepend("<h6><br>Select One or More:</h6> &nbsp;");
                    $('#secCheckBoxes').hide();
                }
            }
        });
    }

    function loadSpecialWordCheckBox() {

        return '<div class="form-check form-check-inline">' +
            '<input class="form-check-input check2" type="checkbox" id="secondCheckbox0" value="0" checked>' +
            ' <label class="form-check-label" for="secondCheckbox0">Show Words Without Student Names (Words in One Vertical Column)</label></div>' +
            '<div class="form-check form-check-inline">' +
            '<input class="form-check-input check2" type="checkbox" id="secondCheckbox1" value="1">' +
            ' <label class="form-check-label" for="secondCheckbox1">Show Words Per Student (Words in Horizotal Row with Student Names in 1 Column)</label></div>';


    }

    function loadTypeListSearch() {

        $.ajax({
            type: "POST",
            url: "php/inc.report.php",
            data: {
                typeSelect: "selectType"
            },
            success: function (data) {
                $("#datatype").html(data);
            }
        });


    }

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

    function loadCatListSearch() {
        $.ajax({ // delete from database
            type: "POST",
            url: "php/inc-dashboard-functions.php",
            data: {
                categorySelect: ""
            },
            success: function (data) {
                $("#categoryArea").html(data);
                $("#categoryArea").prepend("All or Specific Categories and Levels:");
                $("#categoryArea select").append("<option value='all'>All Categories</option>")
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


    function outputData() {
        var typeChoice = $('#datatype :selected').val();
        var studentChoice = $('#student :selected').val();
        var schoolChoice = $('#school :selected').val();
        var category = $('#selcat :selected').val();
        var formatChoice = $('#format :selected').val();
        var classNumber = $('#class :selected').val();
        var checkValues = [];
        var secCheckValue = $('#secCheckBoxes :checked').val();
        var sendArray = {};
        $('#checkBoxes :checked').each(function () {
            checkValues.push($(this).val());
        });
        sendArray['format'] = formatChoice;
        sendArray['type'] = typeChoice;
        if (typeof schoolChoice != 'undefined') {
            sendArray['schoolID'] = schoolChoice;
        }
        if (typeof category != 'undefined') {
            sendArray['category'] = category;
        }
        if (typeof classNumber != 'undefined') {
            sendArray['classID'] = classNumber
        }
        if (typeof studentChoice != 'undefined') {
            sendArray['studentEmail'] = studentChoice;
        } if (checkValues.length > 0) {
            sendArray['checkArray'] = checkValues;
        } if (typeof secCheckValue != 'undefined') {
            if (secCheckValue.length > 0 ) {
                sendArray['specialCheck'] = secCheckValue;
            }
        }
        // console.log(sendArray);
        var sendData = JSON.stringify(sendArray);
        // console.log(sendData);
        $.ajax({
            type: "POST",
            url: "php/inc.report-export.php",
            data: {
                dataChoice: sendData
            },
            success: function (data) {
                $("#sure .modal-title").text("The Following Report Will Be Generated");
                $("#sure .modal-body").html(data);
                $("#modalsave").text("Generate Report");
                $('#modalsave').removeClass('btn-danger').addClass('btn-primary');
                $("#modalsave").hide();
                $("#sure").modal('show');
            }
        });
    }




    function unlockChoices() {
        for (let index = 1; index < 5; index++) {
            $("#inlineCheckbox" + index).prop("disabled", false); // Element(s) are now enabled.
        }

    }

});