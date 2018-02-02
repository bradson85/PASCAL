$(document).ready(function () {



    // loads data table when document loads  then has slight delay for select statments so they dont load before everything
    $(document).ready(function () {
        loadDB();
        setTimeout(
            function () {
                updateSelected();
            }, 250);
    });

    // load the db function asynchronously 
    function loadDB() {
        $.get('/php/inc-addclasses-getclasses.php', function (data) {
            $("#t_body").html(data);
        });
    }

    // update what select school is chosen when loading the db 
    // options based up on id:
    // This is what calls the php file to get which
    // option is the selected option.
    function updateSelected() {
        var ID = new Array();
        $('#word_table tr').each(function (row, tr) {
            ID[row] = {
                "ID": $(tr).find('td:eq(0)').text()
            }
        });
    
        ID.shift(); // moves past first row with headings
        ID = JSON.stringify(ID);
        $.ajax({
            type: "POST",
            url: "/php/inc-addclasses-getschools.php",
            data: {
                IDs: ID,
            },
            dataType: 'json',
            cache: false,
            success: function (data) {
                $('#word_table tr').not(":first").each(function (row, tr) {
                    $(tr).find('td:eq(3)').find('select').val(data[row]);
                });
            }
        });

    }
    

    // this adds a new row for adding more words
    // generates a new row with 0 as id database generates new id.
    $('#addRow').click(function (event) {
        event.preventDefault();
        $.get('php/inc-addclasses-getclassselected.php', function (data) {
            $schoolsel = data;
            var rows = $('<tr><td style=\'display:none;\'>0</td>' +
                '<td contenteditable= "true">Enter A Class Name</td>' // class name
                +
                '<td contenteditable= "true">Enter Grade Level</td>' /// for level
                +
                $schoolsel
                +
                '<td><button class="btn btn-sm deleteRow">Delete</button></td></tr>');
            $('#word_table').append(rows);
        });
    });
   

    // this is for deleteing words and definition 
    $(document).on("click", ".deleteRow", function () {
        var currID = $(this).parent().siblings(":first").text(); // get current id
        $($(this).parents('tr')).remove(); // remove row
        var wordnumber = $('#word_table tr:last-child td:first-child').html();
        $.ajax({ // delete from database
            type: "POST",
            url: "/php/inc-addclasses-deleterow.php",
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
                    setTimeout(
                        function () {
                            updateSelected();
                        }, 250); 
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
                $("#sure .modal-body").text("If you have changed a Class, all teachers and students associtated " +
                    "with the old Class will match the new altered Class");
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
            if (count > 0 && ($.trim($(tr).find('td:eq(1)').text()) == ""||($.trim($(tr).find('td:eq(2)').text()) == "" || $(tr).find('td:eq(3)').find('select').val() == "0" ))) {
                save = false; //false because of blank field somewhere

            } else {
                TableData[row] = {
                    "ID": $.trim($(tr).find('td:eq(0)').text()),
                    "className": $.trim($(tr).find('td:eq(1)').text()),// trim this data to eliminate trailing spaces
                    "gradeLevel": $.trim($(tr).find('td:eq(2)').text()),
                    "schoolName": $(tr).find('td:eq(3)').find('select').val() 
                   
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
                url: "/php/inc-addclasses-update.php",
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
                        setTimeout(
                            function () {
                                updateSelected();
                            }, 250);

                            
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