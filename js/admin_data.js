$(document).ready(function () {

    //___________B Peradotto ____________________________________________________
    //======= This begins the Code for DOM accessing=================== 

    var count = 0 // for preventing save warning from popping up every time.
    $('#changes').hide();
     var index = 0;
    var currentPage = "categories";
    var lastSort = "All";
    // start out with categories
    getTableData("categories");
    $("#categories").tab("show");
    $("#topTable").hide();
   

    setTimeout(
        function () {
            updateSelected('categories');

        }, 350);



    //for tooltip help
    $('[data-toggle="tooltip"]').tooltip();

   // when search button changes
    $(document).on("change", "#sort",function(){
        // start out with categories
        getTableData(currentPage);
        $("#" + currentPage).tab("show");
        setTimeout(
            function () {
                updateSelected(currentPage); // waits 2 ms to make sure page is loaded before addeing selet values
            }, 300);
    });
      /// for class inport modal
      $(document).on("click", "a#classImportSelect",function(){
        $("#fileHelp").text("Add a list of CSV Info in the FORM OF: Class, Grade Level , School ID");
        $('#fileForm').attr('action', "/php/inc-addclasses-importFile.php");
        $("#importModal").modal();
    });
// for  class export modal
$(document).on("click","a#classExportSelect",function(){
    $("#downloadHelp").text("Click Download To Export Class List to CSV");
    $('#exportbutton').attr("href", "/php/inc-addclasses-exportFile.php");
        $("#exportModal").modal();
    });
 // for school import modal
 $(document).on("click","a#schoolImportSelect",function(){
    $("#fileHelp").text("Add a list of CSV Info in the FORM OF: School Name");
    $('#fileForm').attr('action', "/php/inc-addschools-importFile.php");
        $("#importModal").modal();
    });
// for school export modal
$(document).on("click","a#schoolExportSelect",function(){
    $("#downloadHelp").text("Click Download To Export School List to CSV") ;
    $('#exportbutton').attr("href", "/php/inc-addschools-exportFile.php");
        $("#exportModal").modal();
    });

    // fore help popvoer
    $(document).on("click", "#help", function () {

        $(this).popover('toggle');
    });
    /// sets tab for opening categories db interface
    $(document).on("click", "#categories", function () {
        $(this).tab("show");
        currentPage = "categories";
        $("#topTable").hide();
        getTableData("categories");
        setTimeout(
            function () {
                updateSelected('categories');

            }, 250);

    });
    // sets tab for opening terms db interfacd
    $(document).on("click", "#terms", function () {
        
        $(this).tab("show");
        currentPage = "terms";
        getTableData("terms");
        $("#topTable").show();
        loadSearch(); // laads serach box data at top
        setTimeout(
            function () {
                updateSearch();
                updateSelected('terms');

            }, 250);
    });


    // sets tab for opening School List db interfacd
    $(document).on("click", "#schools", function () {
        $(this).tab("show");
        currentPage = "schools";
        $("#topTable").hide();
        getTableData("schools")
        setTimeout(
            function () {
                updateSelected('schools');

            }, 250);

    });

    // sets tab for opening School List db interfacd
    $(document).on("click", "#classes", function () {
        $(this).tab("show");
        $("#topTable").hide();
        currentPage = "classes";
        getTableData("classes")
        setTimeout(
            function () {
                updateSelected('classes');

            }, 250);
    });


    //whend adding new colums layers
    //// for making new boxes appear to be place holders
    $(document).on("click", "#newbox", function () {
        $(this).empty();
        $(this).css('color', 'black');
        $(this).attr('id', '#oldbox');
    });

    $(document).on("click", "#addRow2", function () {
        addRows(currentPage, "pre");
    });


    $(document).on("click", "#addRow1", function () {
        addRows(currentPage, "post");
    });

    $('body').on("click", "#save2,#save1", function () {

        $("#sure .modal-title").text("Are You Sure You Want To Save?");
        $("#sure .modal-body").text("If you have changed a current Category or Level, all Terms associtated " +
            "with the old Category or Level will match the new altered Category and Level");
        $("#modalsave").text("Save");
        $('#modalsave').removeClass('btn-danger').addClass('btn-warning');
        $("#sure").modal('show');
        $("#modalsave").on("click", function () {
            saveToDB(currentPage);
            $("#sure").modal('hide');
        });
        $("#modalclose").on("click", function () {
            $("#sure").modal('hide');
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
                getTableData(currentPage);
                $("#" + currentPage).tab("show");
                setTimeout(
                    function () {
                        updateSelected(currentPage);
                    }, 350);
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


    // this is for the close button on alerts
    $('body').on("click", '.close', function () {
        $('.alert').hide();
    });

    $(document).on("click", ".deleteRow", function () {
        var tabID = $(this).parent().siblings("td:eq(0)").text().trim(); // get current id
        if (currentPage == "terms") {
            items = $(this).parent().siblings("td:eq(2)").text().trim();
        } else items = $(this).parent().siblings("td:eq(1)").text().trim();
        $("#sure .modal-title").text("Are You Sure You Want To Delete \"" + items + "\" From Database?");
        $("#sure .modal-body").text("You will not be able to undo this action.");
        $("#modalsave").text("Delete");
        $('#modalsave').removeClass('btn-warning').addClass('btn-danger');
        $("#sure").modal('show');
        $("#modalsave").on("click", function () {
            deleteRows(tabID, currentPage);
            $($(this).parents('tr')).remove(); // remove row
            $("#sure").modal('hide');
        });
        $("#modalclose").on("click", function () {
            $("#sure").modal('hide');
        });
    });


    // Special errore messages can be retrieved form the the url GET that alert on load this hides them after 
    // a while from he messages phph section 
    setTimeout(
        function () {
            $('#special').fadeOut();
        }, 5000); // this ensures the alerts from get message  are hidden*/

    //======= End of DOM accessing=================== 



    /// below are fucntions ----------------------------------------------------------------
    function getTableData(type) {

        // terms has search variable
        if(type =='terms'){
        var choice = $("#sort_body").find('td:eq(1)').find('select').val();
        lastSort =choice;
        $.ajax({ // delete from database
            type: "POST",
            url: "/php/inc-admin-data.php",
            data: {
                type: type,
                choice: choice
            },
            success: function (data) {
                $('#steve').html(data);
            }
        });
    }
    else {  
        lastSort = "All";
        $.ajax({ // delete from database
        type: "POST",
        url: "/php/inc-admin-data.php",
        data: {
            type: type
        },
        success: function (data) {
            $('#steve').html(data);
        }
    });
        }
    }
    // alters confirmatrion modal to alert what has taken place.
    function confirmModal(message1) {
        $("#confirm .modal-title").text("Result");
        $("#confirm .modal-body").text(message1);
        $("#confirm").modal('show');
        $("#modalclose").on("click", function () {
            $("#confirm").modal('hide');
        });

    }


    function loadSearch(){
        $.ajax({ // delete from database
            type: "POST",
            url: "/php/inc-admin-db.php",
            data: {
                categoriesSel: ""
            },  
            success: function (data) {
            var rows = "<td> View Words By Category:</td>" + data;
            $("#sort_body").html(rows);
            }
        });
       
    }

    function updateSearch(){
        if (index == 0){ 
            index ++
            $("#sort_body").find('td:eq(1)').find('select').val("All");
        }else $("#sort_body").find('td:eq(1)').find('select').val(lastSort); // make sure retuns to last sort when clicke
    }


    // update what select level is chosen when loading the db 
    // options based up on id:
    // This is what calls the php file to get which
    // option is the selected option.
    function updateSelected(type) {

        var ID = new Array();
        $('#word_table tr').each(function (row, tr) {
            ID[row] = {
                "ID": $(tr).find('td:eq(0)').text()
            }
        });

        ID.shift(); // moves past first row with headings


        switch (type) {
            case "terms":

                $.ajax({
                    type: "POST",
                    url: "/php/inc-admin-data.php",
                    data: {
                        currCat: ID
                    },
                    success: function (data) {
                        answer = (JSON.parse(data));
                        $('#word_table tr').not(":first").each(function (row, tr) {
                            $(tr).find('td:eq(1)').find('select').val(answer[row]);
                        });
                    }
                });

                break;
            case "categories":

                $.ajax({
                    type: "POST",
                    url: "/php/inc-admin-data.php",
                    data: {
                        currLev: ID
                    },
                    success: function (data) {
                        answer = JSON.parse(data);

                        $('#word_table tr').not(":first").each(function (row, tr) {
                            //console.log(data[row]);
                            $(tr).find('td:eq(2)').find('select').val(answer[row]);
                        });
                    },
                });

                break;
            case "schools":

                break;
            case "classes":
                $.ajax({
                    type: "POST",
                    url: "/php/inc-admin-data.php",
                    data: {
                        currClass: ID
                    },
                    success: function (data) {
                        answer = JSON.parse(data);
                        $('#word_table tr').not(":first").each(function (row, tr) {
                            //console.log(data[row]);
                            $(tr).find('td:eq(2)').find('select').val(answer[1][row])
                            $(tr).find('td:eq(3)').find('select').val(answer[0][row]);
                        });
                    },

                });

                break;
            default:

        }

    }

    function deleteRows(currID, type) {
        $.ajax({ // delete from database
            type: "POST",
            url: "/php/inc-admin-data.php",
            data: {
                delete: currID,
                delType: type
            },
            success: function (data) {
                confirmModal(data);
                var currentPage = type;
                // start out with categories
                getTableData(type);
                $("#".type).tab("show");
                setTimeout(
                    function () {
                        updateSelected(currentPage); // waits 2 ms to make sure page is loaded before addeing selet values
                    }, 300);
            }
        });
    }


    function addRows(type, when) {
        $.ajax({
            type: "POST",
            url: "/php/inc-admin-data.php",
            data: {
                get: type
            },
            success: function (data) {
                switch (type) {
                    case "terms":
                        rows = blankTermRow(data);
                        break;
                    case "categories":
                        rows = blankCatRow(data);
                        break;
                    case "schools":
                        rows = blankSchoolRow();
                        break;
                    case "classes":
                        rows = blankClassRow(data);

                        break;
                    default:
                        rows = "error: addRows.admin_data.js";
                }

                if (when == 'pre') {
                    $('#word_table').prepend(rows);
                } else {

                    $('#word_table').append(rows);
                }


            }
        });


    }

    function blankTermRow($categoriesSel) {
        return '<tr><td style="display:none;">0</td>' +
            $categoriesSel +
            '<td id ="newbox" contenteditable= "true" style="color:#778899;">'
            +
            ' Click To Enter A New Word</td>' // term name
            +
            '<td id ="newbox" contenteditable= "true" style="color:#778899;">Click To Enter New Definition</td>' /// for level
            +
            '<td><button class="btn btn-sm deleteRow">Delete</button></td></tr>';

    }

    function blankCatRow($levelSelect) {
        return $('<tr><td style="display:none;">0</td>' +
            '<td id ="newbox" contenteditable= "true" style="color:#778899;">Click To Enter A New Category</td>' // term name
            +
            $levelSelect
            +
            '<td><button class="btn btn-sm deleteRow">Delete</button></td></tr>');

    }
    function blankSchoolRow() {
        return $("<tr><td style='display:none;'"
            +
            ">0</td>><td id ='newbox' contenteditable= 'true' style='color:#778899;'"
            +
            ">Click to Add New School Name</td>' // term name"
            +
            "<td><button class='btn btn-sm deleteRow'>Delete</button></td></tr>");

    }

    function blankClassRow($schoolSelect) {
        return '<tr><td style=\'display:none;\'>0</td>' +
            '<td id ="newbox" contenteditable= "true" style="color:#778899;">Enter A Class Name</td>' // class name
            +
            $schoolSelect
            +
            '<td><button class="btn btn-sm deleteRow">Delete</button></td></tr>';

    }
    function retrieveCurrentData(type) {

        // this iterates throught every value in the table and stores it in an array
        var TableData = new Array();
        //first time throght retrns header so set ing up count
        var count = 0
        $('#word_table tr').each(function (row, tr) {
            // if text fields are empty
            if (count > 0 && ($.trim($(tr).find('td:eq(1)').text()) == "" || $(tr).find('td:eq(2)').find('select').val() == '0')) {
                save = false; //false because of blank field somewhere
            } else {
                switch (type) {
                    case "terms":
                        TableData[row] = {
                            "ID": $(tr).find('td:eq(0)').text(),
                            "category": $(tr).find('td:eq(1)').find('select').val(),
                            "word": $(tr).find('td:eq(2)').text(),
                            "definition": $(tr).find('td:eq(3)').text()
                        }
                        break;
                    case "categories":
                        TableData[row] = {
                            "ID": $.trim($(tr).find('td:eq(0)').text()),
                            "catName": $.trim($(tr).find('td:eq(1)').text()),// trim this data to eliminate trailing spaces
                            "level": $(tr).find('td:eq(2)').find('select').val()
                        }
                        break;
                    case "schools":
                        TableData[row] = {
                            "ID": $.trim($(tr).find('td:eq(0)').text()),
                            "schoolName": $.trim($(tr).find('td:eq(1)').text())
                        }
                        break;
                    case "classes":
                        TableData[row] = {
                            "ID": $.trim($(tr).find('td:eq(0)').text()),
                            "className": $.trim($(tr).find('td:eq(1)').text()),// trim this data to eliminate trailing spaces
                            "gradeLevel": $.trim($(tr).find('td:eq(2)').find('select').val()),
                            "schoolName": $(tr).find('td:eq(3)').find('select').val()
                        }

                        break;
                    default:
                }
            }
            count++;
        });

        return TableData;

    }

    function saveToDB(type) {
        var save = true;
        TableData = retrieveCurrentData(type);
        ///  if blank fields havent occurred
        if (save) {
            TableData.shift(); // first row is the table header - so remove
            TableData = JSON.stringify(TableData);
            $.ajax({
                type: "POST",
                url: "/php/inc-admin-data.php",
                data: {
                    info: TableData,
                    what: type
                },
                success: function (data) {
                    confirmModal(data);
                    var currentPage = type;
                    // start out with categories
                    getTableData(type);
                    $("#" + type).tab("show");
                    setTimeout(
                        function () {
                            updateSelected(currentPage); // waits 2 ms to make sure page is loaded before addeing selet values
                        }, 300);
                }
            });
        } else {

            confirmModal("Data Not Saved", "Empty field or Level not"
                + "selected. \nPlease enter data into all fields and select level");

        }
    }



});