$(document).ready(function () {
    var count = 0 // for preventing save warning from popping up every time.
    $('#changes').hide();

    


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
        $.get('/php/inc-addcategories-getcategories.php', function (data) {
            $("#t_body").html(data);
        });
    }

// update what select level is chosen when loading the db 
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
            url: "/php/inc-addcategories-getlevels.php",
            data: {
                IDs: ID,
            },
            dataType: 'json',
            cache: false,
            success: function (data) {
                $('#word_table tr').not(":first").each(function (row, tr) {
                    //console.log(data[row]);
                    $(tr).find('td:eq(2)').find('select').val(data[row]);
                });
            }
        });

    }

     //// for making new boxes appear to be place holders
     $(document).on("click", "#newbox", function () {
        $(this).empty();
        $(this).css('color', 'black');
        $(this).attr('id','#oldbox');
});

$('[data-toggle="tooltip"]').tooltip();

    // this adds a new row for adding more categories to the top
    // generates a new row with 0 as id database generates new id.
    $(' #addRow2').click(function (event) {
        event.preventDefault();

        var rows = $('<tr><td style="display:none;">0</td>' +
            '<td id ="newbox" contenteditable= "true" style="color:#778899;">Click To Enter A New Category</td>' // term name
            +
            '<td><select class=\"form-control\" id=\"selLev\"><<option value = \"0\"> --Select Level--</option>' /// for level
            + 
            '<option value = \"1\"> 1 </option>'
            +
            '<option value = \"2\"> 2 </option>'
            +
            '<option value = \"3\"> 3 </option>'
            +
            '<option value = \"4\"> 4 </option>'
            +
            '<option value = \"5\"> 5 </option>'
            +
            '<option value = \"6\"> 6 </option>'
            +
            '<option value = \"7\"> 7 </option></select></td>'
            +
            '<td><button class="btn btn-sm deleteRow">Delete</button></td></tr>');
        $('#word_table').prepend(rows);
    });
 // this adds a new row for adding more categories to the bottom
    // generates a new row with 0 as id database generates new id.
    $('#addRow1').click(function (event) {
        event.preventDefault();

        var rows = $('<tr><td style="display:none;">0</td>' +
            '<td id ="newbox" contenteditable= "true" style="color:#778899;">Click To Enter A New Category</td>' // term name
            +
            '<td><select class=\"form-control\" id=\"selLev\"><<option value = \"0\"> --Select Level--</option>' /// for level
            + 
            '<option value = \"1\"> 1 </option>'
            +
            '<option value = \"2\"> 2 </option>'
            +
            '<option value = \"3\"> 3 </option>'
            +
            '<option value = \"4\"> 4 </option>'
            +
            '<option value = \"5\"> 5 </option>'
            +
            '<option value = \"6\"> 6 </option>'
            +
            '<option value = \"7\"> 7 </option></select></td>'
            +
            '<td><button class="btn btn-sm deleteRow">Delete</button></td></tr>');
        $('#word_table').append(rows);
    });



    // this is for deleteing categories and levels 
    $(document).on("click", ".deleteRow", function () {
        var currID = $(this).parent().siblings("td:eq(0)").text(); // get current id
        var name = $(this).parent().siblings("td:eq(1)").text().trim(); // get current name
        var level = $(this).parent().siblings("td:eq(2)").find('select').val(); // get current level
        $("#sure .modal-title").text("Are You Sure You Want To Delete \""+name+" Grade "+ level +"\" From Categories");
        $("#sure .modal-body").text("You will not be able to undo this action.");
        $("#modalsave").text("Delete");
        $('#modalsave').removeClass('btn-warning').addClass('btn-danger');
        $("#sure").modal('show');
        $("#modalsave").on("click", function () {
               deleteCategories(currID);
               $($(this).parents('tr')).remove(); // remove row
               $("#sure").modal('hide');
         });
         $("#modalclose").on("click", function () {
            $("#sure").modal('hide');
      });
    });

   function deleteCategories(currID){
        $.ajax({ // delete from database
            type: "POST",
            url: "/php/inc-addcategories-deleterow.php",
            data: {
                data: currID
            },
            success: function (data) {
                $("#confirm .modal-title").text("Confirm");
                $("#confirm .modal-body").text(data);
                $("#confirm").modal('show');
                $("#modalclose").on("click", function () {
                    loadDB();
                    $("#confirm").modal('hide');
              }); 
                    setTimeout(
                        function () {
                            updateSelected(); // waits 2 ms to make sure page is loaded before addeing selet values
                        }, 200);
            }
        });
    }

    $(document).on("change", "#t_body td",function(){
        $('#changes').show();
        $('#changes strong').text("Click \"Save\" to Store Changes");
        setTimeout(
            function () {
                $('#changes').fadeOut();
            }, 3000);
        return false;
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
    // this is hwat happens when blured
    $(document).on("blur", '[contenteditable="true"]', function () {
        count++;
        if(count%7 == 1){
            $("#changes").fadeIn(200);
        $('#changes strong').text("Click \"Save\" to Store Changes");
        setTimeout(
            function () {
                $('#changes').fadeOut();
            }, 3000);
        }
        return false;
});
    // saving new stuff to database by clicking save button of editable table
    $("#save1, #save2").on("click", function () {

        $("#sure .modal-title").text("Are You Sure You Want To Save?");
        $("#sure .modal-body").text("If you have changed a Category or Level, all Terms associtated " +
            "with the old Category or Level will match the new altered Category and Level");
            $("#modalsave").text("OverWrite");
            $('#modalsave').removeClass('btn-danger').addClass('btn-warning');
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
            if (count > 0 && ($.trim($(tr).find('td:eq(1)').text()) == "" || $(tr).find('td:eq(2)').find('select').val() == '0')) {
                save = false; //false because of blank field somewhere

            } else {
                TableData[row] = {
                    "ID": $.trim($(tr).find('td:eq(0)').text()),
                    "catName": $.trim($(tr).find('td:eq(1)').text()),// trim this data to eliminate trailing spaces
                    "level": $(tr).find('td:eq(2)').find('select').val()   
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
                url: "/php/inc-addcategories-update.php",
                data: {
                    data: TableData
                },
                success: function (data) {
                    if (data == 1) { // this is for if successful query.
                        $("#confirm .modal-title").text("Sucessfully Saved");
                        $("#confirm .modal-body").text("The changes have been succsessfully saved.");
                        $("#confirm").modal('show');
                        $("#modalclose").on("click", function () {
                            $("#confirm").modal('hide');
                      });

                        loadDB(); // refresh the newly updated the database
                        setTimeout(
                            function () {
                                updateSelected(); // waits 2 ms to make sure page is loaded before addeing selet values
                            }, 200);
                            
                    } else {

                        $("#confirm .modal-title").text("Data Not Saved");
                        $("#confirm .modal-body").text(data);
                        $("#confirm").modal('show');
                        $("#modalclose").on("click", function () {
                            $("#confirm").modal('hide');
                      });
                    }

                }
            });
        } else {
            $("#confirm .modal-title").text("Data Not Saved");
            $("#confirm .modal-body").text('Empty field or Level not selected. \nPlease enter data into all fields and select level"');
            $("#confirm").modal('show');
            $("#modalclose").on("click", function () {
                $("#confirm").modal('hide');
          });
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