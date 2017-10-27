$(document).ready(function() {

    var form = document.getElementById('createPassword');

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
                    password: document.getElementById('password').value,
                    confirm: document.getElementById('confirm').value
                },
                success: function(response){
                    alert(response);
                }
            });
        }
        form.classList.add('was-validated');

    }, false);

});