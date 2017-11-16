$(document).ready(function () {



    // loads data table when document loads  then has slight delay for select statments so they dont load before everything
    $(document).ready(function () {
        loadAdmin();
        loadTeacher();
        loadStudent();
    });

    // load the db function asynchronously 
    function loadAdmin() {
        $.get('/php/inc-deleteAccounts-readstudents.php', function (data) {
            $("#t_body1").html(data);
        });
    }

     // load the db function asynchronously 
     function loadTeacher() {
        $.get('/php/inc-deleteAccounts-readteachers.php', function (data) {
            $("#t_body2").html(data);
        });
    }
     // load the db function asynchronously 
     function loadStudent() {
        $.get('/php/inc-deleteAccounts-readadmins.php', function (data) {
            $("#t_body3").html(data);
        });
    }
   

    // this is for deleteing words and definition 
    $(document).on("click", ".deleteRowStudent", function () {
        var currID = $(this).parent().siblings(":first").text(); // get current id
        $($(this).parents('tr')).remove(); // remove row
        var wordnumber = $('#admin_table tr:last-child td:first-child').html();
        $.ajax({ // delete from database
            type: "POST",
            url: "/php/inc-deleteAccounts-deleteStudent.php",
            data: {
                data: currID
            },
            success: function (data) {
                $('#info').show();
                $('#info strong').text(data);
                setTimeout(
                    function () {
                        $('#info').fadeOut();
                    }, 8000);

                    loadDB(); // refresh the newly updated the database 
                    
            }
        });

    });
      // this is for deleteing words and definition 
      $(document).on("click", ".deleteRowTeacher", function () {
        var currID = $(this).parent().siblings(":first").text(); // get current id
        $($(this).parents('tr')).remove(); // remove row
        var wordnumber = $('#student_table tr:last-child td:first-child').html();
        $.ajax({ // delete from database
            type: "POST",
            url: "/php/inc-deleteAccounts-deleteteacher.php",
            data: {
                data: currID
            },
            success: function (data) {
                $('#info').show();
                $('#info strong').text(data);
                setTimeout(
                    function () {
                        $('#info').fadeOut();
                    }, 8000);

                    loadDB(); // refresh the newly updated the database 
                    
            }
        });

    });

      // this is for deleteing words and definition 
      $(document).on("click", ".deleteRowAdmin", function () {
        var currID = $(this).parent().siblings(":first").text(); // get current id
        $($(this).parents('tr')).remove(); // remove row
        var wordnumber = $('#teacher_table tr:last-child td:first-child').html();
        $.ajax({ // delete from database
            type: "POST",
            url: "/php/inc-deleteAccounts-deleteadmin.php",
            data: {
                data: currID
            },
            success: function (data) {
                $('#info').show();
                $('#info strong').text(data);
                setTimeout(
                    function () {
                        $('#info').fadeOut();
                    }, 8000);

                    loadDB(); // refresh the newly updated the database 
                    
            }
        });

    });

   

    

    // this is for the close button on alerts
    $('.close').on("click", function () {
        $('.alert').hide();
    });

    // Special errore messages can be retrieved form the the url GET that alert on load this hides them after 
    // a while from he messages phph section 
    setTimeout(
        function () {
            $('#special').fadeOut();
        }, 5000); // this ensures the alerts from get message  are hidden

});