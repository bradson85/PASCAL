$(document).ready(function() {
    $('.canDrag').draggable({revert: "invalid"});
    $('.canDrop').droppable();

    console.log(document.getElementById('assessmentID').innerText);

    $.ajax({
        type: "POST",
        url: "php/inc.assessment.php",
        data: {
            
            id: document.getElementById('assessmentID').innerText,
            student: document.getElementById('student').innerText
        },
        success: function(response){
            console.log(response);
        }
    });
});