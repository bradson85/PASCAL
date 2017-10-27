$(document).ready(function() {

    var form = document.getElementById('createPassword');
    
    form.addEventListener('submit', function(e) {
        if(form.checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');

        return false;
    }, false);

});