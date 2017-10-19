$(document).ready(function() {
    //var value = document.getElementById('school');
    //console.log(value);
    
    $('#accountType').change(function (e) {
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
        if($('#class').val !== "")
            $('#class').empty();
        //var index = $('select#school').serialize();
        //var inputText = this.children[index].innerHTML.trim();
        console.log('index changed');
        console.log(document.getElementById('school').value);
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

    function addOptions(result){
            //console.log(result);
            var obj = JSON.parse(result);
            obj.forEach(function(element) {
                $('#class').append(new Option(element, element));
            }, this);
            $('#class').removeAttr('disabled');
            //$('#class').append(new Option(obj, obj));
            console.log("success");
    }
});
