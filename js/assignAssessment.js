$(document).ready(function () {

    initData();
     
    function initData() {
       $.ajax({ // delete from database
        type: "POST",
        url: "/php/inc-assignAssessment-functions.php",
        data: {
            init: "init"
        },
        dataType:"json",
        success: function (data) {
            $("#school").html(data["school"]);
            $("#classes").html(data["class"]);
            $("#options").html(data["type"]);
            $("#students").html(data["students"]);
            $("#assessments").html(data["assessments"]);
        }
    });
    }

$(document).on("change", "body", function () {

    $('#schoolChoice option').each(function() {
        if ($(this).is(':selected')){
            updateClassList();
          
        }
           
    });

    $('#classNames option').each(function() {
        if ($(this).is(':selected')){
            $('#selStudents').prop("disabled", false);
            $('#selClass').prop("disabled", false);
        }
           
    });

    $('#studentChoice option').each(function() {
        if ($(this).is(':selected')){
            $('#sel4').prop("disabled", false);
          
        }
    });

        
     });

     $(document).on("change", "#selClass",function() {
        if($(this).is(':checked')) { 
            updateToWholeClass();
        }
     });

     $(document).on("change", "#selStudents",function() {
        if($(this).is(':checked')) { 
           updateStudentList();
        }
     });

     $(document).on("change", "#sel4",function() {
         
            $('#assign').prop("disabled", false);
        
     });


function updateToWholeClass(){

    $("#students").html("<form><div class='form-group'>"
    + "<label for='sel3'>Select Student:</label>"
    + "<select multiple class='form-control' id='sel3'>"
    + "<option disabled value = \"0\"> Student Name- Class</option>"
    + "<option value = '"+ -1 +"'>Entire Class</option>"
    + "</select></form><br>");
     
}

function updateClassList(){
    var schoolChoice = $('#schoolChoice :selected').val();
    $.ajax({ 
        type: "POST",
        url: "php/inc-assignAssessment-functions.php",
        data: {
             school: schoolChoice
        },
        success: function (data) {    
            $("#classes").replaceWith(data);
        }
    });


}

function updateStudentList(selected){
    var classChoice = $('#classNames :selected').val();
    $.ajax({ 
        type: "POST",
        url: "php/inc-assignAssessment-functions.php",
        data: {
             class: classChoice
        },
        success: function (data) {    
            $("#students").html(data);
        }
    });

}



    // this is for deleteing words and definition 
    $(document).on("click", "#assign", function () {
       
           saveStudent();
        });


        function saveStudent() {
            var studentChoice = $('#studentChoice :selected').val();
            var assessmentChoice = $('#sel4 :selected').val();
            var classChoice = $('#classNames :selected').val();
                if (studentChoice == -1){
                     saveEntireClass(classChoice,assessmentChoice);
                }else {

                $.ajax({
                    type: "POST",
                    url: "/php/inc-assignAssessment-functions.php",
                    data: {
                        studentChoice: studentChoice, assessmentChoice: assessmentChoice 
                    },
                    success: function (data) {
                        if (data == 1) { // this is for if successful query.
                            $('#success').show(); //show success message
                            $('#success strong').html("<h5>Assessment Assigned<h5><h6><\h6>");
                            setTimeout(
                                function () {
                                    $('#success').fadeOut(); // hide success messsage after 8 seconds
                                }, 5000);
    
    
                                setTimeout(function(){// wait for 5 secs(2)
                                    location.reload(); // then reload the page.(3)
                               }, 6000); 
                                
                        } else {
    
                            $('#warning').show(); // show warning messagees
                            $('#warning strong').text(data); // add that message to html
                            setTimeout(
                                function () {
                                    $('#warning').fadeOut(); //hide woarning mesage after 7 seconds
                                }, 7000);
                        }
    
                    }
                });
            
        }
    }

    function saveEntireClass(classChoice,assessmentChoice){


    }
        function validateEmail($email) {
            var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
            return emailReg.test( $email );
          }
          
          // this is for the close button on alerts
    $('.close').on("click", function () {
        $('.alert').hide();
    });
    
    });