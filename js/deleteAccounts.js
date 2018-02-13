$(document).ready(function () {



    // loads data table when document loads  then has slight delay for select statments so they dont load before everything
    $(document).ready(function () {
        loadAdmin();
        loadTeacher();
        loadStudent();
    });

    // load the db function asynchronously 
    function loadAdmin() {
        $.post("/php/inc-deleteAccounts.php",
        {
            type: "0"
        }, function (data) {
            $("#t_body3").html(data);
        });
    }

     // load the db function asynchronously 
     function loadTeacher() {
        $.post("/php/inc-deleteAccounts.php",
        {
            type: "1"
        }, function (data) {
            $("#t_body2").html(data);
        });
    }
     // load the db function asynchronously 
     function loadStudent() {
        $.post("/php/inc-deleteAccounts.php",
        {
            type: "2"
        }, function (data) {
            $("#t_body1").html(data);
        });
    }
   

    // this is for deleteing words and definition 
    $(document).on("click", ".deleteRowStudent", function () {
        var currID = $(this).parent().siblings(":first").text(); // get current id
        var name = $(this).parent().siblings("td:eq(1)").text(); // get current name

        $("#sure .modal-title").text("Are You Sure You Want To Delete " + name + " From Accounts");
        $("#sure .modal-body").text("You will not be able to undo this action.");
        $("#sure").modal('show');
        $("#modalsave").on("click", function () {
               deleteStudent(currID);
               $($(this).parents('tr')).remove(); // remove row
               $("#sure").modal('hide');
         });
         $("#modalclose").on("click", function () {
            $("#sure").modal('hide');
      });
    });
   function deleteStudent(currID) {
        
        $.ajax({ // delete from database
            type: "POST",
            url: "/php/inc-deleteAccounts.php",
            data: {
                ID: currID
            },
            success: function (data) {
                $('#info').show();
                $('#info strong').text(data);
                setTimeout(
                    function () {
                        $('#info').fadeOut();
                    }, 8000);

                    loadAdmin();
        loadTeacher();
        loadStudent(); // refresh the newly updated the database 
                    
            }
        });

     }
      // this is for deleteing words and definition 
      $(document).on("click", ".deleteRowTeacher", function () {

        var currID = $(this).parent().siblings(":first").text(); // get current id
        var name = $(this).parent().siblings("td:eq(1)").text(); // get current name
        $("#sure .modal-title").text("Are You Sure You Want To Delete " + name + " From Accounts");
        $("#sure .modal-body").text("You will not be able to undo this action.");
        $("#sure").modal('show');
        $("#modalsave").on("click", function () {
               deleteTeacher(currID);
               $($(this).parents('tr')).remove(); // remove row
               $("#sure").modal('hide');
         });
         $("#modalclose").on("click", function () {
            $("#sure").modal('hide');
      });
      });

      function deleteTeacher(currID) {
       
        $.ajax({ // delete from database
            type: "POST",
            url: "/php/inc-deleteAccounts.php",
            data: {
                ID: currID
            },
            success: function (data) {
                $('#info').show();
                $('#info strong').text(data);
                setTimeout(
                    function () {
                        $('#info').fadeOut();
                    }, 8000);

                    loadAdmin();
                    loadTeacher();
                    loadStudent(); // refresh the newly updated the database 
                    
            }
        });

    }

      // this is for deleteing words and definition 
      $(document).on("click", ".deleteRowAdmin", function () { 
        var currID = $(this).parent().siblings(":first").text(); // get current id
        var name = $(this).parent().siblings("td:eq(1)").text(); // get current name
        $("#sure .modal-title").text("Are You Sure You Want To Delete " + name + " From Accounts");
        $("#sure .modal-body").text("You will not be able to undo this action.");
        $("#sure").modal('show');
        $("#modalsave").on("click", function () {
               deleteAdmin(currID);
               $($(this).parents('tr')).remove(); // remove row
               $("#sure").modal('hide');
         });
         $("#modalclose").on("click", function () {
            $("#sure").modal('hide');
      });

      });


      function deleteAdmin(currID){
        
       
        $.ajax({ // delete from database
            type: "POST",
            url: "/php/inc-deleteAccounts.php",
            data: {
                ID: currID
            },
            success: function (data) {
                $('#info').show();
                $('#info strong').text(data);
                setTimeout(
                    function () {
                        $('#info').fadeOut();
                    }, 8000);

                    loadAdmin();
        loadTeacher();
        loadStudent(); // refresh the newly updated the database 
                    
            }
        });

    }
   
    
    

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