$(document).ready(function () {

getLogin();

// show modal on forgot password link click
$(document).on("click", "#resetPW", function() {
    $('#resetPWModal').modal('show');
});
// Submits the user entered e-mail to the database.
$(document).on("click", "#resetSubmit", function() {
    //console.log($('#resetPWModal').find('input').val());
    $.ajax({
        type: "POST",
        url: "php/inc-login.php",
        data: {
            email: $('#resetPWModal').find('input').val()
        },
        success: function(response) {
            //console.log(response);
            $('#resetSuccess').attr('hidden', false);
        }
    })
    
});

$(document).on("click", "#loginbutton", function (e) {
e.preventDefault();

validateLogin();
 });

function getLogin(){
 $.ajax({
        type: "GET",
        url: "php/processlogin.php",
        data: {
            data: ""
        },
        success: function (data) {
           $('#mainarea').html(data);  
        }
    });
}

function getModalLogin(){
    $.ajax({
           type: "GET",
           url: "php/processlogin.php",
           data: {
               data: ""
           },
           success: function (data) {
              $('#addform').html(data);  
           }
       });
   }

   $("#topbarlogin").click(function(e){
    e.preventDefault();
    getModalLogin();
    $("#myModal").modal();
});

$("#middlelogin").click(function(e){
    e.preventDefault();
    getModalLogin();
    $("#myModal").modal();
});


function validateLogin(){

  $.ajax({ 
        type: "POST",
        url: "php/inc-login.php",
        data: {
            email: $("#email").val(),
            psw:  $("#pwd").val(),
            submit: "true"
        },
        success: function (data) {
            try
            {
                    var json = $.parseJSON(data);
                    if(json[0]==2 ){
                        window.location.href = json[1];
                       } else  $('#mainarea').html(data);
                    
            }
            catch(err)
            {
                    $('#mainarea').html(data);
                    $('#addform').html(data); 
            }                
           
          
           
           }
            
    });
    }

});