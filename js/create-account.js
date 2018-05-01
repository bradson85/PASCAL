$(document).ready(function() {
    
    var form = document.getElementById('createAccount');
    $('.alert').hide();
    $('#submit').click(function(e) {
        if(form.checkValidity() === false) {
            e.preventDefault();
            e.stopPropagation();
        }
        else {
            $('.invalid-feedback').hide();
            e.preventDefault();
            e.stopPropagation();
            // Submits the account information to be processed
            $.ajax({
                type: "POST",
                url: "php/inc.create-account.php",
                data: {
                    name: document.getElementById('name').value,
                    email: document.getElementById('email').value,
                    school: document.getElementById('school').value,
                    class: document.getElementById('class').value,
                    accountType: document.getElementById('accountType').value,
                    password: document.getElementById('password').value
                },
                success: function(response) {
                    // console.log(response);
                    if(response === "true"){
                        //$('#alertSuccess span').remove();
                        showAlert("Successfully created account.", "alert-success");
                        $('#createAccount')[0].reset();
                    }
                    else {
                        //$('#alertFail span').remove();
                        showAlert("Error creating account.", "alert-danger");
                        
                    }
                    
                }
            });
        }

    });


    $('#accountType').change(function (e) {
        // check account type to disable/enable different fields relevant to account type
        if(this.options[e.target.selectedIndex].text === "Teacher"){
            $('#school').removeAttr('disabled');
            $('#email').removeAttr('disabled');
            $('#password').attr('disabled', 'disabled');
        }
        else if(this.options[e.target.selectedIndex].text === "Student"){
            $('#school').removeAttr('disabled');
            $('#password').removeAttr('disabled');
        }
        else if(this.options[e.target.selectedIndex].text === "Administrator"){
            $('#school').attr('disabled', 'disabled');
            $('#class').attr('disabled', 'disabled');
            $('#email').removeAttr('disabled');
            $('#password').attr('disabled', 'disabled');
        }
    });
    
    
    $('#school').change(function() {
        // if there are values previously added, remove the values
        if($('#class').val !== "")
            $('#class').empty();
        // debug
        //// console.log('index changed');
        //// console.log(document.getElementById('school').value);

        // send ajax request to get the classes in a school
        $.ajax({
            type: "POST",
            url: "php/inc.get-classes.php",
            data: {school: document.getElementById('school').value},
            success: function(response){
                // console.log(response);
                addOptions(response);
            }
        });
    });
    // add options to the 'class' select
    function addOptions(result){
            var obj = JSON.parse(result);
            $('#class').removeAttr('disabled');
            $('#class').append(new Option("Select a Class", "None"));
            obj.forEach(function(element) {
                $('#class').append(new Option(element, element));
            }, this);
            
            //debug
            //// console.log("success");
    }

    function showAlert(message,alertType) {

        $('#alertPlaceholder').append('<div id="alertdiv" class="alert ' +  alertType + '"><a class="close" data-dismiss="alert">×</a><span>'+message+'</span></div>')
    
        setTimeout(function() { // this will automatically close the alert and remove this if the users doesnt close it in 5 secs
          $("#alertdiv").remove();
        }, 5000);
      }
});
