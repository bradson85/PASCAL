$(document).ready(function () {

getLogin();


$(document).on("click", "#loginbutton", function (e) {
e.preventDefault();

validateLogin();
 });

function getLogin(){
 $.ajax({
        type: "GET",
        url: "/php/processlogin.php",
        data: {
            data: ""
        },
        success: function (data) {
           $('#mainarea').html(data);
           
           
        }
    });


}




function validateLogin(){

  $.ajax({ 
        type: "POST",
        url: "/php/inc-login.php",
        data: {
            email: $("#email").val(),
            psw:  $("#pwd").val(),
            submit: "true"
        },
        success: function (data) {
            console.log(data);
        if(data == 1){
        window.location = "dashboard.php";
        }else  if(data == 2){
            window.location = "assessment.php?id=1&student=1";
            }else if(data == 3){
                window.location = "#";
                }else{
           $('#mainarea').html(data);
           }
            
           s
        }
    });
    }

});