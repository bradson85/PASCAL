$(document).ready(function () {
    var count = 0 // for preventing save warning from popping up every time. 


    $('#changes').hide();

    $("a#importSelect").click(function(){
        $("#importModal").modal();
    });

    $("a#exportSelect").click(function(){
        $("#exportModal").modal();
    });

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
 //// for making new boxes appear to be place holders
    $(document).on("click", "#newbox", function () {
              $(this).empty();
              $(this).css('color', 'black');
              $(this).attr('id','#oldbox');
    });
    // this adds a new row for adding more words
    // generates a new row with 0 as id database generates new id.
    $('#addRow1, #addRow2').click(function (event) {
        event.preventDefault();
        var rows = $("<tr><td style='display:none;'"
        +
       ">0</td>><td id ='newbox' contenteditable= 'true' style='color:#778899;'"
       +
       ">Click to Add New School Name</td>' // term name"
        +
        "<td><button class='btn btn-sm deleteRow'>Delete</button></td></tr>");
    $('#word_table').prepend(rows);
        });
   

    // this is for deleteing words and definition 
    $(document).on("click", ".deleteRow", function () {
        var currID = $(this).parent().siblings("td:eq(0)").text(); // get current id
        var name = $(this).parent().siblings("td:eq(1)").text().trim(); // get current name
        $("#sure .modal-title").text("Are You Sure You Want To Delete \"" + name + "\" From Schools");
        $("#sure .modal-body").text("You will not be able to undo this action.");
        $("#modalsave").text("Delete");
        $('#modalsave').removeClass('btn-warning').addClass('btn-danger');
        $("#sure").modal('show');
        $("#modalsave").on("click", function () {
               deleteSchool(currID);
               $($(this).parents('tr')).remove(); // remove row
               $("#sure").modal('hide');
         });
         $("#modalclose").on("click", function () {
            $("#sure").modal('hide');
      });
    });
    function deleteSchool(currID){
        $.ajax({ // delete from database
            type: "POST",
            url: "/php/inc-addschools-deleterow.php",
            data: {
                currID: currID
            },
            success: function (data) {
                $("#confirm .modal-title").text("Confirm");
                        $("#confirm .modal-body").text(data);
                        $("#confirm").modal('show');
                        $("#modalclose").on("click", function () {
                            $("#confirm").modal('hide');
                      });

                    loadDB(); // refresh the newly updated the database  
            }
        });
    }

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
                $("#sure .modal-body").text("If you have changed a School, all classes associtated " +
                    "with the old School will match the new altered School");
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
                        $("#confirm .modal-title").text("Sucessfully Saved");
                        $("#confirm .modal-body").text("The changes have been succsessfully saved.");
                        $("#confirm").modal('show');
                        $("#modalclose").on("click", function () {
                            $("#confirm").modal('hide');
                      });


                        loadDB(); // refresh the newly updated the database

                            
                    } else {

                        $("#confrim .modal-title").text("Data Not Saved");
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