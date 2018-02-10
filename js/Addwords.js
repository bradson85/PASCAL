$(document).ready(function () {
    // loadSearch();
    // loads data table when document loads  then has slight delay for select statments so they dont load before everything
    $(document).ready(function () {
          loadSearch();
        setTimeout(
            function () {
                loadDB();
            }, 550);
        
    });

    $(document).on("change", "#sort",function(){
        loadDB();
    });


    $(document).on("change", "#t_body td",function(){
        $('#info').show();
        $('#info strong').text("Click \"Save\" to Store Changes");
        setTimeout(
            function () {
                $('#info').fadeOut();
            }, 6000);
    });

    $(document).on("blur", '[contenteditable="true"]', function () {
                $('#info').show();
                $('#info strong').text("Click \"Save\" to Store Changes");
                setTimeout(
                    function () {
                        $('#info').fadeOut();
                    }, 6000);
                return false;
    });


    // load the db function asynchronously 
    function loadDB() {
        var choice = $("#sort_body").find('td:eq(1)').find('select').val();
        console.log(choice);
       $.ajax({ // delete from database
        type: "POST",
        url: "/php/inc-addwords-read.php",
        data: {
            data: choice
        },
        success: function (data) {
            $("#t_body").html(data);
            setTimeout(
                function () {
                    updateSelected();
                }, 250);
           
        }
    });
    }

    function loadSearch(){
        $.get('php/inc-addwords-getsorting.php', function (data) {
            $categoriesSel = data;
            var rows = "<td> View Words By Category:</td>" + $categoriesSel;
            $("#sort_body").html(rows);
        });
       
    }


    // update what select category is chosen when loading the db 
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
            url: "/php/inc-addwords-functions.php",
            data: {
                IDs: ID,
                function: 1
            },
            dataType: 'json',
            cache: false,
            success: function (data) {
                $('#word_table tr').not(":first").each(function (row, tr) {
                    $(tr).find('td:eq(1)').find('select').val(data[row]);
                });
            }
        });

    }

    // this adds a new row for adding more words
    // generates a new row with 0 as id database generates new id.
    $('#addRow').click(function (event) {
        event.preventDefault();
        $.get('php/inc-addwords-getcategories.php', function (data) {
            $categoriesSel = data;
            var rows = $('<tr><td style="display:none;">0</td>' +
                $categoriesSel +
                '<td contenteditable= "true">Enter A New Word</td>' // term name
                +
                '<td contenteditable= "true">Enter New Definition</td>' /// for level
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
            url: "/php/inc-addwords-deleterow.php",
            data: {
                data: currID
            },
            success: function (data) {
                $('#success').show();
                $('#success strong').text(data);
                setTimeout(
                    function () {
                        $('#success').fadeOut();
                    }, 8000);
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
                $('#info').show();
                $('#info strong').text("Click \"Save\" to Store Changes");
                setTimeout(
                    function () {
                        $('#info').fadeOut();
                    }, 6000);
                return false;
            } else if (e.which == 27) { // to exit editing without saving then reload db
                $("#t_body").empty();
                loadDB();
                return false;
            }
        });
    });
    // saving new stuff to database by clicking save button of editable table
    $(document).on("click", "#save", function () {
        var save = true;
         
        // this iterates throught every value in the table and stores it in an array
        var TableData = new Array();
        //first time throght retrns header so set ing up count
        var count = 0
        $('#word_table tr').each(function (row, tr) {
            // if text fields are empty
            if (count > 0 && ($.trim($(tr).find('td:eq(2)').text()) == "" || $.trim($(tr).find('td:eq(3)').text()) == "" )) {
                save = false; //false because of blank field somewhere
              
            } else {
                TableData[row] = {
                    "ID": $(tr).find('td:eq(0)').text(),
                    "category": $(tr).find('td:eq(1)').find('select').val(),
                    "word": $(tr).find('td:eq(2)').text(),
                    "definition": $(tr).find('td:eq(3)').text()
                    
                  
                }
            }
            count ++;
        });
        ///  if blank fields havent occurred
        if (save) {
            TableData.shift(); // first row is the table header - so remove
            TableData = JSON.stringify(TableData); // convert array to json
            $.ajax({
                type: "POST",
                url: "/php/inc-addwords-update.php",
                data: {
                    data: TableData
                },
                success: function (data) {
                    if (data == 0) { // this is for if a category is not selected.
                        $('#warning').show(); // show warning messagees
                        $('#warning strong').text("Make Sure You Select A Category"); // add that message to html
                        setTimeout(
                            function () {
                                $('#warning').fadeOut(); //hide woarning mesage after 7 seconds
                            }, 7000);
                    } else {
                        if (data == 1) { // if a categroy is selected
                            $('#success').show(); //show success message
                            $('#success strong').text("Saved Successfully");
                            setTimeout(
                                function () {
                                    $('#success').fadeOut(); // hide success messsage after 8 seconds
                                }, 8000);


                            loadDB(); // refresh the newly updated the database
                        }
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