$(document).ready(function () {

    //___________B Peradotto ____________________________________________________
    //======= This begins the Code for DOM accessing=================== 

    var count = 0 // for preventing save warning from popping up every time.
    $('#changes').hide();
    var index = 0;
    var currentPage = "categories";
    var lastSort = "All";
    var sortNumber =10;
    var curPageNum = 1;   // current selected page
    var totalNumOfPages = 1;   // total pages
    var sortBy = 0;  // assennding items =  1, 2 is table head 2 3 is table head 3, 0 is default sort 
    // descending item =  4 is table head 1, 5 is table head 2, etc.
    // there are at most 3 tables headers to be sorted.
    // start out with categories
    getTableData("categories");
    $("#categories").tab("show");
    $("#topTable").hide();



    // loading selected on start
    setTimeout(
        function () {
            updateSelected('categories');
        }, 350);


    /// for clicking the next page
    $(document).on("click", "#pageNext", function (e) {
        e.preventDefault();
        if (curPageNum < totalNumOfPages) {
            curPageNum++;
            tableSorting();
            setTimeout(
                function () {
                    updateSelected(currentPage);
                }, 50);
        }

    });
    // for clicking the previous page
    $(document).on("click", "#pagePrev", function (e) {
        e.preventDefault();

        if (curPageNum > 1) {
            curPageNum--;
            tableSorting();
            setTimeout(
                function () {
                    updateSelected(currentPage);

                }, 50);
        }
    });

    $(document).on("change", "#sortSize", function () {
        sortNumber =  $("#sortSize").val();
        tableSorting();
        setTimeout(
            function () {
                updateSelected(currentPage);
            }, 50);

    });
 
    $(document).on("click", ".pagesnumber", function () {
          curPageNum = parseInt($(this).text());
          tableSorting();
            setTimeout(
                function () {
                    updateSelected(currentPage);
                }, 50);

    });

    $(document).on("click", "#sort_1", function (e) {
        if (sortBy == 1) {
            sortBy = 4;
        } else sortBy = 1;
        getTableData(currentPage);
        setTimeout(
            function () {
                updateSelected(currentPage);
                markSorted(1);
            }, 150);
        
    });
    $(document).on("click", "#sort_2", function (e) {
        if (sortBy == 2) {
            sortBy = 5;
        } else sortBy = 2;
        getTableData(currentPage);
        setTimeout(
            function () {
                updateSelected(currentPage);
                markSorted(2);
            }, 150);
    });
    $(document).on("click", "#sort_3", function (e) {
        if (sortBy == 3) {
            sortBy = 6;
        } else sortBy = 3;
        getTableData(currentPage);
        setTimeout(
            function () {
                updateSelected(currentPage);
                markSorted(3);
            }, 150);  
    });



    //for tooltip help
    $('[data-toggle="tooltip"]').tooltip();

    $(document).on("change", "input:file", function () {
        var fileName = $(this).val();
        if (fileName) { // returns true if the string is not empty
            $('#fileup').removeAttr('disabled');
        } else { // no file was selected

        }
    });

    // when search button changes
    $(document).on("change", "#sort", function () {
        // start out with categories
        getTableData(currentPage);
        $("#" + currentPage).tab("show");
        setTimeout(
            function () {
                updateSelected(currentPage); // waits 2 ms to make sure page is loaded before addeing selet values

            }, 300);
    });
    /// for class inport modal
    $(document).on("click", "a#classImportSelect", function () {
        $("#fileHelp").text("Add a list of CSV Info in the FORM OF: Class, Grade Level , School ID");
        $("#importModal").modal('show');
        $('#fileForm').on("submit", function (e) {
            e.preventDefault(); //form will not submitted
            $.ajax({
                url: "php/inc-addclasses-importFile.php",
                method: "POST",
                data: new FormData(this),
                contentType: false,          // The content type used when sending data to the server.  
                cache: false,                // To unable request pages to be cached  
                processData: false,          // To send DOMDocument or non processed data file it is set to false  
                success: function (data) {
                    $("#importModal").modal('hide');
                    var json = $.parseJSON(data);
                    if (json[0] == true) {
                        importSuccessModal("Error", json[3]);
                    } else {
                        importSuccessModal(json[1], json[2]);
                    }
                    getTableData(currentPage);
                    $("#".currentPage).tab("show");
                    setTimeout(
                        function () {
                            updateSelected(currentPage); // waits 2 ms to make sure page is loaded before addeing selet values 

                        }, 300);

                }
            })
        });

    });

    // for  class export modal
    $(document).on("click", "a#classExportSelect", function () {
        $("#downloadHelp").text("Click Download To Export Class List to CSV");
        $('#exportbutton').attr("href", "/php/inc-addclasses-exportFile.php");
        $("#exportModal").modal();
    });

    // for school import modal
    $(document).on("click", "a#schoolImportSelect", function () {
        $("#fileHelp").text("Add a list of CSV Info in the FORM OF: Class, Grade Level , School ID");
        $("#importModal").modal('show');
        $('#fileForm').on("submit", function (e) {
            e.preventDefault(); //form will not submitted
            $.ajax({
                url: "php/inc-addschools-importFile.php",
                method: "POST",
                data: new FormData(this),
                contentType: false,          // The content type used when sending data to the server.  
                cache: false,                // To unable request pages to be cached  
                processData: false,          // To send DOMDocument or non processed data file it is set to false  
                success: function (data) {
                    $("#importModal").modal('hide');
                    var json = $.parseJSON(data);
                    if (json[0] == true) {
                        importSuccessModal("Error", json[3]);
                    } else {
                        importSuccessModal(json[1], json[2]);
                    }
                }
            })
        });
    });
    // for school export modal
    $(document).on("click", "a#schoolExportSelect", function () {
        $("#downloadHelp").text("Click Download To Export School List to CSV");
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
        getTableData("schools");

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
        getTableData("classes");

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
        $("#modalsave").removeClass("delete").addClass("save");
        $("#modalsave").text("Save");
        $('#modalsave').removeClass('btn-danger').addClass('btn-warning');
        $("#modalsave").show();
        $("#sure").modal('show');
    });
    // when modal is on save
    $("body").on("click", '.save', function (e) {
        saveToDB(currentPage);
        $("#sure").modal('hide');
    });
    $("#modalclose").on("click", function () {
        $("#sure").modal('hide');
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
        if (count % 7 == 1) {
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
        tabID = $(this).parent().siblings("td:eq(0)").text().trim(); // get current id
        if (currentPage == "terms") {
            items = $(this).parent().siblings("td:eq(2)").text().trim();
        } else items = $(this).parent().siblings("td:eq(1)").text().trim();
        $("#sure .modal-title").text("Are You Sure You Want To Delete \"" + items + "\" From Database?");
        $("#sure .modal-body").text("You will not be able to undo this action.");
        $("#modalsave").removeClass("save").addClass("delete");
        $("#modalsave").text("Delete");
        $('#modalsave').removeClass('btn-warning').addClass('btn-danger');
        $("#modalsave").show();
        $("#sure").modal('show');
    });

    //when modal is ready on delete
    $("body").on("click", ".delete", function () {
        deleteRows(tabID, currentPage);
        $("#sure").modal('hide');
    });
    $("#modalclose").on("click", function () {
        $("#sure").modal('hide');
    });
   
    

    // Special errore messages can be retrieved form the the url GET that alert on load this hides them after 
    // a while from he messages phph section 
    setTimeout(
        function () {
            $('#special').fadeOut();
        }, 5000); // this ensures the alerts from get message  are hidden*/

    //======= End of DOM accessing=================== 



    /// below are fucntions ----------------------------------------------------------------


    function tableSorting() {
        if (item.length < 1) {
            throw "no table data";
        }
        $("#sortSize").val(sortNumber);
        totalNumOfPages = Math.ceil(parseInt(item.length, 10) / parseInt(sortNumber, 10)); // total pages number of items total divide by items per page is number of pages.
        var endIndex = curPageNum * sortNumber;  // end index will be currpage multipied by how many per page.
        var startIndex = endIndex - sortNumber; // start index will be number per page minus end index;
        if (endIndex > item.length) {
            endIndex = item.length;
        }
        var minPage = parseInt(curPageNum) - 5;
        var maxPage = parseInt(curPageNum) + 5;
        if (maxPage > totalNumOfPages) {
            maxPage = totalNumOfPages;
            minPage = totalNumOfPages - 10;
        }
        if (minPage < 0) {
            minPage = 0;
            if (totalNumOfPages < 10) {
                maxPage = totalNumOfPages;
            } else maxPage = 10;
        }
        $("#pages").empty();  // empyt pagingation box
        // add previosue to pagination
        $("#pages").append('<li id="pagePrev" class="page-item"> <a class="page-link" href="#">Previous</a> </li>');

        for (let index = minPage; index < maxPage; index++) {
            // check fo currentpage and add active class
            if (index == curPageNum - 1) {
                $("#pages").append('<li class="page-item active"> <a class="page-link pagesnumber" href="#">' + (index + 1) + '</a> </li>');
            } else $("#pages").append('<li  class="page-item"> <a class="page-link pagesnumber" href="#">' + (index + 1) + '</a> </li>');
        }
        // add next to paginaion
        $("#pages").append('<li id="pageNext" class="page-item"> <a class="page-link" href="#">Next</a> </li>');
        $("#t_body_" + currentPage).empty();
        for (let index = startIndex; index < endIndex; index++) {
            $("#t_body_" + currentPage).append(item[index]);
        }

    }

    function getTableData(type) {
      sortByName = getOrderByName(type);
        // terms has search variable
        if (type == 'terms') {
            var choice = $("#sort_body").find('td:eq(1)').find('select').val();
            lastSort = choice;
            
            $.ajax({ // delete from database
                type: "POST",
                url: "/php/inc-admin-data.php",
                data: {
                    type: type,
                    choice: choice,
                    sortBy: sortByName
                },
                success: function (data) {
                    answer = JSON.parse(data);
                    item = JSON.parse(answer[1]);
                    $("#steve").html(answer[0]);

                    setTimeout(
                        function () {
                          tableSorting();

                        }, 50);

                    setTimeout(
                        function () {
                            updateSelected('terms');

                        }, 50);


                }
            });
        }
        else {
            lastSort = "All";
            $.ajax({ // delete from database
                type: "POST",
                url: "/php/inc-admin-data.php",
                data: {
                    type: type,
                    sortBy: sortByName
                },
                success: function (data) {
                    answer = JSON.parse(data);
                    item = JSON.parse(answer[1]);
                    $("#steve").html(answer[0]);

                    setTimeout(
                        function () {
                            tableSorting();

                        }, 50);

                    setTimeout(
                        function () {
                            updateSelected(currentPage);

                        }, 50);

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

    // alters confirmatrion modal to alert what has taken place.
    function importSuccessModal(message1, message2) {
        $("#sure .modal-title").text(message1);
        $("#sure .modal-body").html(message2);
        $("#modalsave").hide();
        $("#sure").modal('show');
        $("#sure").modal('handleUpdate');
        $("#modalclose").on("click", function () {
            $("#modalsave").show();
            $("#save").modal('hide');

        });

    }


    function loadSearch() {
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

    function updateSearch() {
        if (index == 0) {
            index++
            $("#sort_body").find('td:eq(1)').find('select').val("All");
        } else $("#sort_body").find('td:eq(1)').find('select').val(lastSort); // make sure retuns to last sort when clicke
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
                currentPage = type;
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
                    $('table#word_table').prepend(rows);
                } else {

                    $('table#word_table').append(rows);
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
        console.log("test");
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
                    currentPage = type;
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
    function getOrderByName(type) {
        orderBy = sortBy;
        $returnString = "ID";
        switch (type) {
            case "terms":
                switch (orderBy) {
                    case 1:
                        $returnString = "category ASC";
                        break;
                    case 2:
                        $returnString = "name ASC";
                        break;
                    case 3:
                        $returnString = "definition ASC";
                        break;
                    case 4:
                        $returnString = "category DESC";
                        break;
                     case 5:
                        $returnString = "name DESC";
                        break;
                    case 6:
                        $returnString = "definition DESC";
                        break;
                    default:
                        $returnString = "ID";
                }
                break;
            case "categories":
            switch (orderBy) {
                case 1:
                    $returnString = "name ASC";
                    break;
                case 2:
                    $returnString = "level ASC";
                    break;
                case 4:
                    $returnString = "name DESC";
                    break;
                 case 5:
                    $returnString = "level DESC";
                    break;
                default:
                    $returnString = "ID";
            }
                break;
            case "schools":
            switch (orderBy) {
                case 1:
                    $returnString = "name ASC";
                    break;
                case 4:
                    $returnString = "name DESC";
                    break;
                default:
                    $returnString = "ID";
            }
                break;
            case "classes":
            switch (orderBy) {
                case 1:
                    $returnString = "name ASC";
                    break;
                case 2:
                    $returnString = "gradeLevel ASC";
                    break;
                case 3:
                    $returnString = "school ASC";
                    break;
                case 4:
                    $returnString = "name DESC";
                    break;
                 case 5:
                    $returnString = "gradeLevel DESC";
                    break;
                case 6:
                    $returnString = "school DESC";
                    break;
                default:
                    $returnString = "ID";
            }
                break;
            default:


        }
        return $returnString;
    }
    function markSorted(index) {
        $(".removeLater").remove();
        if (index == 1) {
            if (sortBy == 1) {
                $("#word_table").find('th:eq(1)').append('<span class="removeLater">&#9650</span>');
            } else $("#word_table").find('th:eq(1)').append('<span class="removeLater">&#9660</span>');
        } else if (index == 2) {
            if (sortBy == 2) {
                $("#word_table").find('th:eq(2)').append('<span class="removeLater">&#9650</span>');
            } else $("#word_table").find('th:eq(2)').append('<span class="removeLater">&#9660</span>');

        } else if (index == 3) {
            if (sortBy == 3) {
                $("#word_table").find('th:eq(3)').append('<span class="removeLater">&#9650</span>');
            } else $("#word_table").find('th:eq(3)').append('<span class="removeLater">&#9660</span>');

        }
    }


});