$(document).ready(function() {

    var form = document.getElementById('createPassword');

    $('.alert').hide();

    form.addEventListener('submit', function(e) {
        if(form.checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
        }
        else {
            $('.invalid-feedback').hide();
            event.preventDefault();
            event.stopPropagation();
            $.ajax({
                type: "POST",
                url: "php/inc.create-password.php",
                data: {
                    email: document.getElementById('email').value,
                    password: document.getElementById('password').value,
                    confirm: document.getElementById('confirm').value
                },
                success: function(response){
                    // Encountered a bug here where an extra space was being added to the return value when returning
                    // from the PHP function. The problem was an extra space after the ?> at the end of the php code.
                    // Fixed by removing the extra space and return value returned as expected.
                    if(response === "true"){
                        $('#alertSuccess').show();
                        $('#createPassword')[0].reset();
                    }
                    // Returns error messages if not true, could integrate those messages in the alert in the future.
                    else {
                        $('#alertFail').show();
                        $('#createPassword')[0].reset();
                    }
                        
                }
            });
        }
        form.classList.add('was-validated');

    }, false);

});