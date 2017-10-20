$(document).ready(function() {
    
    $('#accountType').change(function (e) {
        // check account type to disable/enable different fields relevant to account type
        if(this.options[e.target.selectedIndex].text === "Teacher"){
            $('#school').removeAttr('disabled');
            
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
            url: "inc.get-classes.php",
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
            obj.forEach(function(element) {
                $('#class').append(new Option(element, element));
            }, this);
            
            //debug
            console.log("success");
    }
});
