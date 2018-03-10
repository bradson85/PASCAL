$(document).ready(function () {

    loadNumberCompleted();

    if ($('#dataTableTeach').length) {

        loadClassListSearch("dashboard");
    } else if ($('#classListTable').length) {
        loadClassListSearch("classlist");

    } else loadSchoolListSearch();

    // for modals on class list page
    $(document).on("click", "a#classListImportSelect", function () {
        $("#fileHelp").text("Add a list of CSV Info in the FORM OF: Student Name, Student Password, Grade Level, Class, SchoolID");
        //  $('#fileForm').attr('action', "/php/inc-classlist-importFile.php");
        $("#importModal").modal();
    });
    // for school export modal
    $(document).on("click", "a#classListExportSelect", function () {
        var listChoice = $('#classlist :selected').val();
        if(listChoice ==0){
            $("#confirm .modal-title").text("Select Class");
        $("#confirm .modal-body").text("Please select which class you want to export.");
        $("#confirm").modal('show');
        $("#modalclose").on("click", function () {
            $("#confirm").modal('hide');
        });
        }else{
        $("#downloadHelp").text("Click Download To Export Class List to CSV in the FORM OF: Student Name, Student Password, Grade Level, Class, SchoolID");
        var input = $("<input>")
               .attr("type", "hidden")
               .attr("name", "classNum").val(listChoice);
        $('#exportForm').append(input);
        $('#exportForm').attr("action","php/inc-classlist-exportFile.php");
        $("#exportModal").modal('show');
        }
    });

    $(document).on("click", "#exportbutton", function () {
        $("#exportModal").modal('hide');
    });

    $(document).on("change", "#dashboard", function () {
        loadDashboardClassList();
    });

    $(document).on("change", "#classlist", function () {
        loadClassList();
    });


    loadAssessmentData();
    loadNumberAvailable();

    function loadClassListSearch(which) {

        $.ajax({ // delete from database
            type: "POST",
            url: "php/inc-dashboard-functions.php",
            data: {
                classSelect: which
            },
            success: function (data) {
                var rows = "<td> Select Which Class:</td>" + data;
                $("#sort_body").html(rows);
            }
        });

    }


    function loadNumberCompleted() {

        $.ajax({ // delete from database
            type: "POST",
            url: "php/inc-dashboard-functions.php",
            data: {
                numComplete: ""
            },
            success: function (data) {
                row = data + " Total Assessment(s) Taken."
                $("#completed").text(row);
            }
        });

    }

    function loadNumberAvailable() {

        $.ajax({ // delete from database
            type: "POST",
            url: "php/inc-dashboard-functions.php",
            data: {
                numAvailable: ""
            },
            success: function (data) {
                row = data + " Assessment(s) Available."
                $("#available").text(row);
            }
        });

    }

    function loadAssessmentData() {
        $.ajax({ // delete from database
            type: "POST",
            url: "php/inc-dashboard-functions.php",
            data: {
                assessment: "assessment"
            },
            success: function (data) {
                $("#assessTableTeach tbody").html(data);
            }
        });


    }

    function loadSchoolListSearch() {

        $.ajax({ // delete from database
            type: "POST",
            url: "php/inc-dashboard-functions.php",
            data: {
                schoolSelect: "selectSchool"
            },
            success: function (data) {
                var rows = "<td> Select Which School:</td>" + data;
                $("#sort_body").html(rows);
            }
        });

    }


    function loadDashboardClassList() {

        var classChoice = $('#dashboard :selected').val();
        $.ajax({ // delete from database
            type: "POST",
            url: "php/inc-dashboard-functions.php",
            data: {
                classChoice: classChoice,

            },
            success: function (data) {
                $("#dataTableTeach tbody").html(data);
            }
        });

    }

    function loadClassList() {

        var listChoice = $('#classlist :selected').val();
        $.ajax({ // delete from database
            type: "POST",
            url: "php/inc-dashboard-functions.php",
            data: {
                listChoice: listChoice
            },
            success: function (data) {
                $("#classListTable tbody").html(data);
            }
        });

    }


    function importClassList() {

        var listChoice = $('#classlist :selected').val();
        $.ajax({ // delete from database
            type: "POST",
            url: "php/inc-classlist-importFile.php",
            data: {
                listChoice: listChoice
            },
            success: function (data) {
                $("#classListTable tbody").html(data);
            }
        });

    }
    // alters confirmatrion modal to alert what has taken place.
    function importSuccessModal(message1,message2) {
        $("#sure .modal-title").text(message1);
        $("#sure .modal-body").html(message2);
        $("#sure").modal('show');
        $("#sure").modal('handleUpdate');
        $("#modalclose").on("click", function () {
            $("#modalsave").show();
            $("#save").modal('hide');
            
      });

    }
});