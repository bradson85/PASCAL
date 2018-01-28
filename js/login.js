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
console.log($("#email").val());
console.log( $("#pwd").val());
  $.ajax({ 
        type: "POST",
        url: "/php/inc-login.php",
        data: {
            email: $("#email").val(),
            psw:  $("#pwd").val(),
            submit: "true"
        },
        success: function (data) {
        if(data == "1"){
        window.location = "dashboard.php";
        }else {
           $('#mainarea').html(data);
           }
           
        }
    });
    }

});