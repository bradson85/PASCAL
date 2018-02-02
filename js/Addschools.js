$(document).ready(function () {



    // loads data table when document loads  then has slight delay for select statments so they dont load before everything
    $(document).ready(function () {
        loadDB();
        
    });

    // load the db function asynchronously 
    function loadDB() {
        $.get('/php/inc-addschools-getschools.php', function (data) {
            $("#t_body").html(data);
        });
    }

    

    // this adds a new row for adding more words
    // generates a new row with 0 as id database generates new id.
    $('#addRow').click(function (event) {
        event.preventDefault();
        var rows = $('<tr><td style="display:none;">0</td>><td contenteditable= "true">Enter A New School Name</td>' // term name
        +
        '<td><button class="btn btn-sm deleteRow">Delete</button></td></tr>');
    $('#word_table').append(rows);
        });
   

    // this is for deleteing words and definition 
    $(document).on("click", ".deleteRow", function () {
        var currID = $(this).parent().siblings(":first").text(); // get current id
        $($(this).parents('tr')).remove(); // remove row
        var wordnumber = $('#word_table tr:last-child td:first-child').html();
        $.ajax({ // delete from database
            type: "POST",
            url: "/php/inc-addschools-deleterow.php",
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

    //This fucntion causes a blur when the Enter Key is hit 
    // it blurs the table so it appears that the editing is done
    // Also it doesnt save changes when escape is entered
    $(document).on("focus", '[contenteditable="true"]', function () {
        $(this).on('keydown', function (e) {
            if (e.which == 13 && e.shiftKey == false) {
                $(this).blur();
                return false;
            } else if (e.which == 27) { // to exit editing without saving then reload db
                $("#t_body").empty();
                loadDB();
                return false;
            }
        });
    });

    // saving new stuff to database by clicking save button of editable table
    $("#save").on("click", function () {
        
                $("#sure .modal-title").text("Are You Sure You Want To Save?");
                $("#sure .modal-body").text("If you have changed a School, all classes associtated " +
                    "with the old School will match the new altered School");
                $("#sure").modal('show');
                $("#modalsave").on("click", function () {
                       saveToDB();
                       $("#sure").modal('hide');
                 });
                 $("#modalclose").on("click", function () {
                    $("#sure").modal('hide');
              });
            });

    function saveToDB() {
        var save = true;
        // this iterates throught every value in the table and stores it in an array
        var TableData = new Array();
        //first time throght retrns header so set ing up count
        var count = 0
        $('#word_table tr').each(function (row, tr) {
            // if text fields are empty
            if (count > 0 && ($.trim($(tr).find('td:eq(1)').text()) == "" )) {
                save = false; //false because of blank field somewhere

            } else {
                TableData[row] = {
                    "ID": $.trim($(tr).find('td:eq(0)').text()),
                    "schoolName": $.trim($(tr).find('td:eq(1)').text()),
                   
                }
            }
           
            count++;
        });
        ///  if blank fields havent occurred
        if (save) {
            TableData.shift(); // first row is the table header - so remove
            TableData = JSON.stringify(TableData); // convert array to json
            $.ajax({
                type: "POST",
                url: "/php/inc-addschools-update.php",
                data: {
                    data: TableData
                },
                success: function (data) {
                    if (data == 1) { // this is for if successful query.
                        $('#success').show(); //show success message
                        $('#success strong').html("<h5>Saved Successfully<h5><h6> Any Duplicates Not Saved<\h6>");
                        setTimeout(
                            function () {
                                $('#success').fadeOut(); // hide success messsage after 8 seconds
                            }, 8000);


                        loadDB(); // refresh the newly updated the database

                            
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
            $('#warning strong').text("You have an empty field. \n Please enter data into all fields"); // add the message to html
            setTimeout(
                function () {
                    $('#warning').fadeOut(); //hide woarning mesage after 7 seconds
                }, 5000);
        }
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