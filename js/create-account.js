$(document).ready(function() {
    
    var form = document.getElementById('createAccount');
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
                url: "php/inc.create-account.php",
                data: {
                    name: document.getElementById('name').value,
                    email: document.getElementById('email').value,
                    school: document.getElementById('school').value,
                    class: document.getElementById('class').value,
                    accountType: document.getElementById('accountType').value
                },
                success: function(response) {
                    if(response === "true"){
                        //$('#alertSuccess span').remove();
                        $('#alertSuccess').show();
                        $('#createAccount')[0].reset();
                    }
                    else {
                        //$('#alertFail span').remove();
                        $('#alertFail').show();
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
            
        }
        
        else if(this.options[e.target.selectedIndex].text === "Student"){
            $('#school').removeAttr('disabled');
            $('#email').attr('disabled', 'disabled');
        }

        else if(this.options[e.target.selectedIndex].text === "Administrator"){
            $('#school').attr('disabled', 'disabled');
            $('#email').removeAttr('disabled');
        }
    });
    
    
    $('#school').change(function() {
        //if there are values previously added, remove the values
        if($('#class').val !== "")
            $('#class').empty();
        //debug
        console.log('index changed');
        console.log(document.getElementById('school').value);

        //send ajax request to get the classes in a school
        $.ajax({
            type: "POST",
            url: "php/inc.get-classes.php",
            data: {school: document.getElementById('school').value},
            success: function(response){
                console.log(response);
                addOptions(response);
            }
        });
    });
    //add options to the 'class' select
    function addOptions(result){
            var obj = JSON.parse(result);
            $('#class').removeAttr('disabled');
            $('#class').append(new Option("Select a Class", "None"));
            obj.forEach(function(element) {
                $('#class').append(new Option(element, element));
            }, this);
            
            //debug
            console.log("success");
    }
});
