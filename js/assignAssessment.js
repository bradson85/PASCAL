$(document).ready(function () {
     


    // this is for deleteing words and definition 
    $(document).on("click", "#assign", function () {
       
           saveToDB();
        });


        function saveToDB() {
            var save = true;
            // this iterates throught every value in the table and stores it in an array
            var TableData = new Array();
             var date = new Date( $("#exdate").val());
             var datestring = date.toISOString().substring(0, 10);
               
           
           
                // if text fields are empty
                if ((($('#sort_body td:eq(0)').find('select').val() == "0" || $('#sort_body td:eq(1)').find('select').val() == "0" ))) {
                    save = false; //false because of blank field somewhere
    
                } else {
                    TableData = {
                        "category":$('#sort_body td:eq(0)').find('select').val(),
                        "student":$('#sort_body td:eq(1)').find('select').val(),
                        "date": datestring,
                        "email":$('#mailAd').val()
                    }
                   
                }
               
              
           
            ///  if blank fields havent occurred
            if (save) {
               
                TableData = JSON.stringify(TableData); // convert array to json
                $.ajax({
                    type: "POST",
                    url: "/php/inc-assignAssessment-sendLink.php",
                    data: {
                        data: TableData
                    },
                    success: function (data) {
                        if (data == 1) { // this is for if successful query.
                            $('#success').show(); //show success message
                            $('#success strong').html("<h5>Link Sent To Student<h5><h6><\h6>");
                            setTimeout(
                                function () {
                                    $('#success').fadeOut(); // hide success messsage after 8 seconds
                                }, 5000);
    
    
                                setTimeout(function(){// wait for 5 secs(2)
                                    location.reload(); // then reload the page.(3)
                               }, 6000); 
                                
                        } else {
    
                            $('#warning').show(); // show warning messagees
                            $('#warning strong').text(data); // add that message to html
                            setTimeout(
                                function () {
                                    $('#warning').fadeOut(); //hide woarning mesage after 7 seconds
                                }, 7000);
                        }
    
                    }
                });
            } else {
                $('#warning').show(); // show warning messagees
                $('#warning strong').text("You have an empty field or unselected options. \n Please enter all data"); // add the message to html
                setTimeout(
                    function () {
                        $('#warning').fadeOut(); //hide woarning mesage after 7 seconds
                    }, 5000);
            }
        }
        function validateEmail($email) {
            var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
            return emailReg.test( $email );
          }
          
          // this is for the close button on alerts
    $('.close').on("click", function () {
        $('.alert').hide();
    });
    
    });