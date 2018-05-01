$(document).ready(function() {

    var form = document.getElementById('createPassword');

    $('.alert').hide();

    form.addEventListener('submit', function(e) {
        if($('#password').val() === '' || $('#confirm').val() === '') {
            showAlert('Fields cannot be blank.', 'alert-danger small');
            e.preventDefault();
            e.stopPropagation();
        }
        else {
            $('.invalid-feedback').hide();
            e.preventDefault();
            e.stopPropagation();
            $.ajax({
                type: "POST",
                url: "php/inc.create-password.php",
                dataType: "json",
                data: {
                    email: document.getElementById('email').value,
                    password: document.getElementById('password').value,
                    confirm: document.getElementById('confirm').value
                },
                success: function(response){
                    showAlert('Successfully updated password.', 'alert-success small');
                    $('#password').val('');
                    $('#confirm').val('');

                        
                },
                error: function(response) {
                    showAlert('Passwords do not match.', 'alert-danger small');
                    //console.log("Error: " + response);
                }
            });
        }

    }, false);

    function showAlert(message,alertType) {

        $('#alertPlaceholder').append('<div id="alertdiv" class="alert ' +  alertType + '"><a class="close" data-dismiss="alert">×</a><span>'+message+'</span></div>').fadeIn(500);
    
        setTimeout(function() { // this will automatically close the alert and remove this if the users doesnt close it in 5 secs
    
    
          $("#alertdiv").remove();
    
        }, 5000);
      }
});