$(document).ready(function(){
    $('select').on("change",function(){
        var catID = $(this).val();
        console.log(catID);
        $.ajax({
            type: "POST",
            url: "inc-createassessmentupdatetable.php",
            data: {
                data: catID       
            },
                    success: function (data) {
                $(document).find('#t_body').append(data);
              console.log(data);
            }
        });
    });


});