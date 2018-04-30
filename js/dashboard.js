$(document).ready(function () {

    loadNumberCompleted();
    loadCategorySelectForWordStats();
    $("tbody").css( 'cursor', 'pointer' );

    if ($('#dataTableTeach').length) {
        loadClassListSearch("dashboard");
        loadGraphClassSearchTeach();
    } else if ($('#classListTable').length) {
        loadClassListSearch("classlist");

    } else {loadSchoolListSearch();
        loadGraphSchoolListSearch();
    }

    $(document).on("click", ".assessmentLink", function () {
        location.href = 'assessment.php?id='+this.id;
    });

    $(document).on("click", ".studentLink", function (e) {
       $("<form id='form-id' method='post' action='studentData.php'>"
       + "<input type='hidden'name='email' value='"+ this.id +"'>"
       +" </form>").appendTo('body').submit();
    });
    
    // for school export modal
    $(document).on("click", "a#classListExportSelect", function () {
        var listChoice = $('#classlist :selected').val();
           // in case delete mode is mopen
           $('#undeleteStudent').text("Delete Mode").removeClass("btn-danger active").addClass("btn-secondary");;
           $('#classListTable tr').not(":first").each(function (row, tr) {
               $(tr).find('td:eq(6)').attr('hidden', true);
           });
           $("#edithead").prop('hidden', true);
           $('#undeleteStudent').attr("id","deleteStudent");
        if(listChoice ==0){
            $("#confirm .modal-title").text("Select Class");
        $("#confirm .modal-body").text("Please select a class before you export.");
        $("#confirm").modal('show');
      
        }else{
        $("#downloadHelp").text("Click Download To Export Class List to CSV in the FORM OF: Student Name, Email, Student Password, Grade Level, Class, School Name, Password Hashed Or Not");
        var input = $("<input>")
               .attr("type", "hidden")
               .attr("name", "classNum").val(listChoice);
        $('#exportForm').append(input);
        $('#exportForm').attr("action","php/inc-classlist-exportFile.php");
        $("#exportModal").modal('show');
        }
    });
    // for import claass list
    $(document).on("click", "a#classListImportSelect", function () {
        var listChoice = $('#classlist :selected').val();
           // in case delete mode is mopen
           $('#undeleteStudent').text("Delete Mode").removeClass("btn-danger active").addClass("btn-secondary");;
           $('#classListTable tr').not(":first").each(function (row, tr) {
               $(tr).find('td:eq(6)').attr('hidden', true);
           });
           $("#edithead").prop('hidden', true);
           $('#undeleteStudent').attr("id","deleteStudent");
        if(listChoice ==0){
            $("#confirm .modal-title").text("Select Class");
        $("#confirm .modal-body").text("Please select a class before you import.");
        $("#confirm").modal('show');
        }else{
        $("#fileHelp").text("Add a list of CSV Info in the FORM OF:  Student Name, Email, Student Password, Grade Level, Class, School Name, Password Hashed or Not");
        var output = $("<input>")
        .attr("type", "hidden")
        .attr("name", "classNum").val(listChoice);
        $('#fileForm').append(output);
        $("#importModal").modal('show');
    }
    });
  // for submitting import file
    $('#fileForm').on("submit", function (e) {
        e.preventDefault(); //form will not submitted
        $.ajax({
            url: "php/inc-classlist-importFile.php",
            method: "POST",
            data: new FormData(this),
            contentType: false,          // The content type used when sending data to the server.  
            cache: false,                // To unable request pages to be cached  
            processData: false,          // To send DOMDocument or non processed data file it is set to false  
            success: function (data) {
                $("#importModal").modal('hide');
                var json = $.parseJSON(data);
                if (json[0] == true) {
                    importSuccessModal("Error", json[3]);
                } else {
                    importSuccessModal(json[1], json[2]);
                }
                loadClassList();
               
            }
        })
    });

    $(document).on("click", "#exportbutton", function () {
        $("#exportModal").modal('hide');
    });

    $(document).on("change", "#classlist", function () {
        loadClassList();
    });

    $(document).on("change", "#schoolSelect", function () {
        loadClassListSearchAdmin();
    });

   

    $(document).on("change", "#graphclassoption", function () {
       loadGraphStudentList();
    });

    $(document).on("change", "#graphschooloption", function () {
        loadGraphClassSearch();
    });

    // This is on the graph js page studentData !!!!!!
   // $(document).on("change", "#graphstudentoption", function () {



    $(document).on("click", "a#deleteStudent", function () {
        var listChoice = $('#classlist :selected').val();
        if(listChoice ==0){
        $("#confirm .modal-title").text("Select Class");
        $("#confirm .modal-body").text("Please select a class before you delete students.");
        $("#confirm").modal('show');
        $("#modalclose").on("click", function () {
            $("#confirm").modal('hide');
        });
        }else{
        $(this).text("Close Delete Mode").removeClass("btn-secondary").addClass("btn-danger active");
        $('#classListTable tr').not(":first").each(function (row, tr) {
            $(tr).find('td:eq(6)').removeAttr('hidden');
        });
        $("#edithead").removeAttr("hidden");
        $('#deleteStudent').attr("id","undeleteStudent");
        $("#confirm .modal-title").text("Select Who To Delete");
        $("#confirm .modal-body").text("Click appropriate delete button in the row you want to delete student" +
        " from. If a student already has assessment data, you cannot delete them.");
        $("#confirm").modal('show');
        $("#modalclose").on("click", function () {
            $("#confirm").modal('hide');
        });
    }
    });

    $(document).on("click", "a#undeleteStudent", function () {
        $(this).text("Delete Mode").removeClass("btn-danger active").addClass("btn-secondary");;
        $('#classListTable tr').not(":first").each(function (row, tr) {
            $(tr).find('td:eq(6)').attr('hidden', true);
        });
        $("#edithead").prop('hidden', true);
        $('#undeleteStudent').attr("id","deleteStudent");
    });

    $(document).on("click", "a#deleteChoice", function () {
        name= $(this).parent().siblings("td:eq(0)").text().trim(); // get rows name
       email = $(this).parent().siblings("td:eq(1)").text().trim(); // get rows email
      
       $("#sure .modal-title").text("Are You Sure You Want To Delete \"" + name + "\" From Class List?");
       $("#sure .modal-body").text("You will not be able to undo this action.");
       $("#modalsave").text("Delete");
       $('#modalsave').removeClass('btn-warning').addClass('btn-danger');
       $("#modalsave").show();
       $("#sure").modal('show');
   });

   $("#modalsave").on("click", function () {
    deleteRows(name, email);
    $("#sure").modal('hide');
});

$("#modalclose").on("click", function () {
    $("#sure").modal('hide');
});

$("#modalclose").on("click", function () {
    $("#confirm").modal('hide');
});


//loadAssessmentData();
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
                console.log(data);
               $("#sort_table").html(rows);
            }
        });

    }

    function loadClassListSearchAdmin() {
        var schoolChoice = $('#schoolSelect :selected').val();
        $.ajax({ // delete from database
            type: "POST",
            url: "php/inc-dashboard-functions.php",
            data: {
                schoolChoice: schoolChoice
            },
            success: function (data) {
                $("#classOptions").html(data);
            }
        });

    }

    function loadCategorySelectForWordStats() {
        $.ajax({ // delete from database
            type: "POST",
            url: "php/inc-dashboard-functions.php",
            data: {
                categorySelect: ""
            },
            success: function (data) {
                $("#categoryoption").html(data);
                $("#categoryoption select").append("<option value='all'>All Categories</option>")
            }
        });

    }

    function loadGraphClassSearch() {
        var schoolChoice = $('#graphSchoolSelect :selected').val();
        $.ajax({ // delete from database
            type: "POST",
            url: "php/inc-dashboard-functions.php",
            data: {
                graphSchoolChoice: schoolChoice
            },
            success: function (data) {
                $("#graphclassoption").html(data);
            }
        });

    }

    function loadGraphClassSearchTeach() {
        $.ajax({ // delete from database
            type: "POST",
            url: "php/inc-dashboard-functions.php",
            data: {
                graphTeahClass: ""
            },
            success: function (data) {
                $("#graphclassoption").html(data);
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
                $("#schoolOptions").html(data);
            }
        });

    }

    function loadGraphSchoolListSearch() {

        $.ajax({ // delete from database
            type: "POST",
            url: "php/inc-dashboard-functions.php",
            data: {
                graphSchoolSelect: "selectSchool"
            },
            success: function (data) {
                var rows = "<td> Select Which School:</td>" + data;
                $("#sort_body").html(rows);
                $("#graphschooloption").html(data);
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

    function loadAdminClassList() {

        var classChoice = $('#adminClassList :selected').val();
        $.ajax({ // delete from database
            type: "POST",
            url: "php/inc-dashboard-functions.php",
            data: {
                classChoice: classChoice,

            },
            success: function (data) {
                $("#dataTableAdmin tbody").html(data);
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

    function loadGraphStudentList() {

        var graphClassChoice = $('#graphclassoption :selected').val();
        $.ajax({ // delete from database
            type: "POST",
            url: "php/inc-dashboard-functions.php",
            data: {
                graphClassChoice: graphClassChoice
            },
            success: function (data) {
                $("#graphstudentoption").html(data);
            }
        });

    }
    function deleteRows(name,email) {
        var classID = $('#classlist :selected').val();
        $.ajax({ // delete from database
            type: "POST",
            url: "/php/inc-dashboard-functions.php",
            data: {
                name: name,
                email: email,
                classID: classID
            },
            success: function (data) {
                $("#confirm .modal-title").text("Result");
                $("#confirm .modal-body").text(data);
                $("#confirm").modal('show');
                $("#modalclose").on("click", function () {
                    $("#confirm").modal('hide');
                });
                loadClassList();
                setTimeout(
                    function () {
                $('#classListTable tr').not(":first").each(function (row, tr) {
                    $(tr).find('td:eq(6)').removeAttr('hidden');
                });
                $("#edithead").removeAttr("hidden");
            }, 350);
            }
        });
    }

    // alters confirmatrion modal to alert what has taken place.
    function importSuccessModal(message1,message2) {
        $("#modalsave").hide();
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