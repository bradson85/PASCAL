$(document).ready(function() {

    var form = document.getElementById('createPassword');

    $('.alert').hide();

    form.addEventListener('submit', function(e) {
        if(form.checkValidity() === false) {
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
                    if(response === "true"){
                        $('#alertSuccess').show();
                        $('#createPassword')[0].reset();
                    }
                    // Returns error messages if not true, could integrate those messages in the alert in the future.
                    else {
                        $('#alertFail').show();
                        $('#createPassword')[0].reset();
                    }
                        
                },
                error: function(response) {
                    console.log("Error: " + response);
                }
            });
        }
        form.classList.add('was-validated');

    }, false);

});