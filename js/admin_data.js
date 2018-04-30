$(document).ready(function () {

    //___________B Peradotto ____________________________________________________
    //======= This begins the Code for DOM accessing=================== 


    var getAllSelectTypes = new Array();;
    var count = 0 // for preventing save warning from popping up every time.
    $('#changes').hide();
    var types = ["categories", "terms", "schools", "classes"];
    var index = 0;
    var previousData = {}; //for keeping old table before changes;
    var changeArray = {};  // for marking changed tables;
    var savedArray = {}; // for saving changes of entire table
    var savedSelect = new Array();
    var savedTableHeaders = new Array();
    var currentPage = "categories";
    var lastSort = "All";
    var currentStartIndex;
    var currentEndIndex;
    var searchActive = 0;
    var sortNumber = 10;
    var curPageNum = 1;   // current selected page
    var totalNumOfPages = 1;   // total pages
    var sortBy = 0;  // assennding items =  1, 2 is table head 2 3 is table head 3, 0 is default sort 
    // descending item =  4 is table head 1, 5 is table head 2, etc.
    // there are at most 3 tables headers to be sorted.

    // on page load must delete old cookies by expiring them ORDER IMPORTANT HERE
    eraseAllCookies();

    //laod all the select types for use later
    getAllSelectsTypes();


    // start out with categories
    getTableData("categories");
    $("#categories").tab("show");
    $("#topTable").hide();





    /// for clicking the next page
    $(document).on("click", "#pageNext", function (e) {
        e.preventDefault();
        var newData = getValidData(currentPage);
        if (newData) {
            saveToJson(currentPage,newData);
            if (curPageNum < totalNumOfPages) {
                curPageNum++;
                createdTableData = updateTableFromJson(changeArray[currentPage], currentPage);
                tableSorting(changeArray[currentPage]);
            }
        }
    });
    // for clicking the previous page
    $(document).on("click", "#pagePrev", function (e) {
        e.preventDefault();
        var newData = getValidData(currentPage);
        if (newData) {
            saveToJson(currentPage,newData);
            if (curPageNum > 1) {
                curPageNum--;
                createdTableData = updateTableFromJson(changeArray[currentPage], currentPage);
                tableSorting(changeArray[currentPage]);
            }
        }
    });

    $(document).on("change", "#sortSize", function () {
        var newData = getValidData(currentPage);
        if (newData) {
            saveToJson(currentPage,newData);
            sortNumber = $("#sortSize").val();
            createdTableData = updateTableFromJson(changeArray[currentPage], currentPage);
            tableSorting(changeArray[currentPage]);
        }
    });

    $(document).on("click", ".pagesnumber", function () {
        var newData = getValidData(currentPage);
        if (newData) {
            saveToJson(currentPage,newData);
            curPageNum = parseInt($(this).text());
            createdTableData = updateTableFromJson(changeArray[currentPage], currentPage);
            tableSorting(changeArray[currentPage]);
        }
    });

    $(document).on("click", "#sort_1", function (e) {
        var newData = getValidData(currentPage);
        if (newData) {
            saveToJson(currentPage,newData);
            if (sortBy == 1) {
                sortBy = 4;
            } else sortBy = 1;
            getTableData(currentPage);
            markSorted(1);
        }
    });
    $(document).on("click", "#sort_2", function (e) {
        var newData = getValidData(currentPage);
        if (newData) {
            saveToJson(currentPage,newData);
            if (sortBy == 2) {
                sortBy = 5;
            } else sortBy = 2;
            getTableData(currentPage);
            markSorted(2);
        }
    });
    $(document).on("click", "#sort_3", function (e) {
        var newData = getValidData(currentPage);
        if (newData) {
            saveToJson(currentPage,newData);
            if (sortBy == 3) {
                sortBy = 6;
            } else sortBy = 3;
            getTableData(currentPage);
            markSorted(3);
        }
    });

    $(document).on("change", "#word_table td", function () {
        // start out with categories
        $(this).parent().find('td:last #changebox1').prop('checked', true);
        count++;
        if (count % 7 == 1) {
            $("#changes").fadeIn(200);
            $('#changes strong').text("Click \"Save\" to Store Changes");
            setTimeout(
                function () {
                    $('#changes').fadeOut();
                }, 3000);
        }

    });
// for if keys are presssed int textbox to mark change
    $(document).on("keypress", '[contenteditable="true"]', function () {
       
        $(this).parent().find('td:last #changebox1').prop('checked', true);
    });
// for  if text box is edited
    $(document).on("input", '[contenteditable="true"]', function () {
       
        $(this).parent().find('td:last #changebox1').prop('checked', true);
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
        var newData = getValidData(currentPage);
        if (newData) {
            saveToJson(currentPage, newData);
            // start out with categories
            getTableData(currentPage);
            $("#" + currentPage).tab("show");
        }
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
        var newData = getValidData(currentPage);
        if (newData) {
            saveToJson(currentPage,newData);
            $(this).tab("show");
            currentPage = "categories";
            $("#topTable").hide();
            getTableData("categories");
        }
    });
    // sets tab for opening terms db interfacd
    $(document).on("click", "#terms", function () {
        var newData = getValidData(currentPage);
        if (newData) {
            saveToJson(currentPage,newData);
            $(this).tab("show");
            currentPage = "terms";
            getTableData("terms");
            $("#topTable").show();
            loadSearch(); // laads serach box data at top
            setTimeout(
                function () {
                    updateSearch();
                }, 250);
        }
    });

    // sets tab for opening School List db interfacd
    $(document).on("click", "#schools", function () {
        var newData = getValidData(currentPage);
        if (newData) {
            saveToJson(currentPage,newData);
            $(this).tab("show");
            currentPage = "schools";
            $("#topTable").hide();
            getTableData("schools");
        }
    });

    // sets tab for opening School List db interfacd
    $(document).on("click", "#classes", function () {
        var newData = getValidData(currentPage);
        if (newData) {
            saveToJson(currentPage,newData);
            $(this).tab("show");
            $("#topTable").hide();
            currentPage = "classes";
            getTableData("classes");
        }
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
        $("#modalsave").removeClass("delete").removeClass("save").addClass("presave");
        $("#modalsave").text("Continue");
        $('#modalsave').removeClass('btn-danger').addClass('btn-warning');
        $("#modalsave").show();
        $("#sure").modal('show');
    });
    // when modal is on save
    $("body").on("click", '.presave', function (e) {
        var newData = getValidData(currentPage);
       // console.log(newData);
        if (newData) {
            saveToJson(currentPage,newData);
            
            setTimeout(
                function () {
                    finalizeChanges(previousData);
                }, 100);

        } else $("#sure").modal('hide');
    });

    $("body").on("click", '.save', function (e) {
       // console.log(savedArray);
        saveToDB(savedArray,previousData);
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
                    $(this).parent().find('td:last #changebox1').prop('checked', true);
                return false;
            } else if (e.which == 27) { // to exit editing without saving then reload db
                $("#t_body").empty();
                getTableData(currentPage);
                $("#" + currentPage).tab("show");
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
        if (currentPage == "terms") {
            items = [$(this).parent().siblings("td:eq(0)").find('select').val(),
            $(this).parent().siblings("td:eq(1)").text().trim(),
            $(this).parent().siblings("td:eq(2)").text().trim()

            ];
            //console.log(items);
            itemName = $(this).parent().siblings("td:eq(1)").text().trim();
        } else if (currentPage == "classes") {
            items = [$(this).parent().siblings("td:eq(0)").text().trim(),
            $(this).parent().siblings("td:eq(1)").find('select').val(),
            $(this).parent().siblings("td:eq(2)").find('select').val()
            ];
            itemName = $(this).parent().siblings("td:eq(0)").text().trim();
        } else if (currentPage == "categories") {
            items = [$(this).parent().siblings("td:eq(0)").text().trim(),
            $(this).parent().siblings("td:eq(1)").find('select').val()
            ];

            itemName = $(this).parent().siblings("td:eq(0)").text().trim();
        } else if (currentPage == "schools") {
            items = [$(this).parent().siblings("td:eq(0)").text().trim(),
            ];
            itemName = $(this).parent().siblings("td:eq(0)").text().trim();
        }
        $("#sure .modal-title").text("Are You Sure You Want To Delete \"" + itemName + "\" From Database?");
        $("#sure .modal-body").text("You will not be able to undo this action.");
        $("#modalsave").removeClass("save").addClass("delete");
        $("#modalsave").text("Delete");
        $('#modalsave').removeClass('btn-warning').addClass('btn-danger');
        $("#modalsave").show();
        $("#sure").modal('show');
    });

    //when modal is ready on delete
    $("body").on("click", ".delete", function () {
        deleteRows(items, currentPage);
        $("#sure").modal('hide');
    });
    $("#modalclose").on("click", function () {
        $("#sure").modal('hide');
    });

    //when clicking search box
    $(document).on("click", "#searchGo", function () {
        searchActive = 1;
        var searchstring = $('#wordsearch').val();
        searchSorting(searchstring);

    });
// when hitting enter in searchbox
    $(document).on("keypress", '#wordsearch', function (e) {
        if (e.which == 13 && e.shiftKey == false) {
            searchActive = 1;
            var searchstring = $('#wordsearch').val();
            searchSorting(searchstring);
        }
       
    });



    // Special errore messages can be retrieved form the the url GET that alert on load this hides them after 
    // a while from he messages phph section 
    setTimeout(
        function () {
            $('#special').fadeOut();
        }, 5000); // this ensures the alerts from get message  are hidden*/

    //======= End of DOM accessing=================== 

    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    /// below are fucntions ----------------------------------------------------------------
    function searchSorting(wordSearch) {
        if (wordSearch.length < 1) {
            alert('Seach box left blank');
        } else if ($.trim($('#wordsearch').val()) === '') {
            alert('Seach box left blank');
        } else {
            var pattern = new RegExp('^' + wordSearch + '.*$', 'i');
            var searchResults = searchTable(currentPage, pattern);
            createdTableData = updateTableFromJson(searchResults, currentPage);
            if (createdTableData.length > 0) {
                tableSorting(searchResults);
            } else $("#t_body_" + currentPage).html("Search Result Not Found.");
        }
    }

    function searchTable(type, searchExpression) {
        var result;
        switch (type) {
            case "terms":
                result = searchTerms(searchExpression);
                break;
            case "categories":
                result = searchCategories(searchExpression);
                break;
            case "schools":
                result = searchSchools(searchExpression);
                break;
            case "classes":
                result = searchClasses(searchExpression);

                break;
            default:
                result = "No" + type + "found."

        }
        return result;
    }

    function searchTerms(searchExpression) {
        var tempArray = new Array();
        var tempCount = 0;
        for (let index = 0; index < changeArray['terms'].length; index++) {
            if (searchExpression.test(changeArray["terms"][index]['word'])) {
                tempArray[tempCount] = changeArray["terms"][index];
                tempCount++;
            }
        }
        return tempArray;
    }
    function searchCategories(searchExpression) {
        var tempArray = new Array();
        var tempCount = 0;
        for (let index = 0; index < changeArray['categories'].length; index++) {

            if (searchExpression.test(changeArray["categories"][index]['catName'])) {
                tempArray[tempCount] = changeArray["categories"][index];
                tempCount++;
            }
        }
        return tempArray;
    }
    function searchSchools(searchExpression) {
        var tempArray = new Array();
        var tempCount = 0;
        for (let index = 0; index < changeArray['schools'].length; index++) {
            if (searchExpression.test(changeArray["schools"][index]['schoolName'])) {
                tempArray[tempCount] = changeArray["schools"][index];
                tempCount++;
            }
        }
        return tempArray;
    }
    function searchClasses(searchExpression) {
        var tempArray = new Array();
        var tempCount = 0;
        for (let index = 0; index < changeArray['classes'].length; index++) {
            if (searchExpression.test(changeArray["classes"][index]['className'])) {
                tempArray[tempCount] = changeArray["classes"][index];
                tempCount++;
            }
        }
        return tempArray;
    }


    function getAllSelectsTypes() {
        getSelectTypes("terms");
        getSelectTypes("categories");
        getSelectTypes("schools");
        getSelectTypes("classes");

    }

    function tableSorting(item) {
        if (item.length < 1) {
            throw "no table data";
        }
        $("#sortSize").val(sortNumber);
        totalNumOfPages = Math.ceil(parseInt(item.length, 10) / parseInt(sortNumber, 10)); // total pages number of items total divide by items per page is number of pages.
        var endIndex = currentEndIndex = curPageNum * sortNumber;  // end index will be currpage multipied by how many per page.
        var startIndex = currentStartIndex = endIndex - sortNumber; // start index will be number per page minus end index;
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
        for (let index = startIndex; index < endIndex; index++) {   // for adding current page data to table
            $("#t_body_" + currentPage).append(createdTableData[index]);  // append to table
        }

    }

    function getTableData(type) {
        sortByName = getOrderByName(type); // when sortign with database
        cookieString = 'fresh' + type;
        if (checkCookie(cookieString)) {
            changeArray[type] = sortLocal(type);
            createdTableData = updateTableFromJson(changeArray[type], type);
            $("#steve").html(savedTableHeaders[type]);
            setTimeout(
                function () {
                    tableSorting(changeArray[type]);
                    previousData[type] = JSON.parse(JSON.stringify($.extend({}, changeArray[type])));
                }, 50);

        } else {
            setCookie(cookieString, false, 1);
            // terms has search variable
            if (type == 'terms') {
                var choice = $("#sort_body").find('td:eq(1)').find('select').val();
                lastSort = choice;

                $.ajax({ // get info from db
                    type: "POST",
                    url: "/php/inc-admin-data.php",
                    data: {
                        type: type,
                        choice: choice,
                        sortBy: sortByName
                    },
                    success: function (data) {

                        answer = JSON.parse(data);
                        freshDBData = answer[1][0];
                        changeArray[type] = freshDBData;
                        savedSelect[type] = answer[1][1];
                        savedTableHeaders[type] = answer[0];
                        createdTableData = createTableFromJSON(answer[1][0], type, answer[1][1]);
                        $("#steve").html(answer[0]);
                        setTimeout(
                            function () {
                                tableSorting(freshDBData);
                                previousData[type] = JSON.parse(JSON.stringify($.extend({}, freshDBData)));
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
                        freshDBData = answer[1][0];
                        changeArray[type] = freshDBData;
                        savedSelect[type] = answer[1][1];
                        savedTableHeaders[type] = answer[0];
                        createdTableData = createTableFromJSON(answer[1][0], type, answer[1][1]);
                        //  console.log(changeArray[type]);
                        $("#steve").html(answer[0]);

                        setTimeout(
                            function () {
                                tableSorting(freshDBData);
                                previousData[type] = JSON.parse(JSON.stringify($.extend({}, freshDBData)));
                            }, 50);

                    }
                });
            }

        }
    }
    // alters confirmatrion modal to alert what has taken place.
    function confirmModal(message1) {
        $("#confirm .modal-title").text("Result");
        $("#confirm .modal-body").html(message1);
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



    function deleteRows(currItems, type) {
        $.ajax({ // delete from database
            type: "POST",
            url: "/php/inc-admin-data.php",
            data: {
                delete: currItems,
                delType: type
            },
            success: function (data) {
                confirmModal(data);
                currentPage = type;
                // start out with categories
                eraseAllCookies(); // this allows a fresh database screening
                getTableData(type);
                $("#".type).tab("show");
            }
        });
    }


    function addRows(type, when) {
        data = getAllSelectTypes[type];
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


    function blankTermRow($categoriesSel) {
        return '<tr>' + $categoriesSel +
            '<td id ="newbox" contenteditable= "true" style="color:#778899;">'
            +
            ' Click To Enter A New Word</td>' // term name
            +
            '<td id ="newbox" contenteditable= "true" style="color:#778899;">Click To Enter New Definition</td>' /// for level
            +
            '<td><button class="btn btn-sm deleteRow">Delete</button></td>'
            + '<td style="display:none;"> <input type="checkbox" value="check1" id="changebox1"></td>'
            + '<td style="display:none;">' +
            '<input type="checkbox" value="newchecked" id="newCheckbox1" checked></td></tr>';

    }

    function blankCatRow($levelSelect) {
        return $('<tr><td id ="newbox" contenteditable= "true" style="color:#778899;">Click To Enter A New Category</td>' // term name
            +
            $levelSelect
            +
            '<td><button class="btn btn-sm deleteRow">Delete</button></td>'
            + '<td style="display:none;"> <input type="checkbox" value="check1" id="changebox1"></td>'
            + '<td style="display:none;">' +
            '<input type="checkbox" value="newchecked" id="newCheckbox1" checked></td></tr>');

    }
    function blankSchoolRow() {
        return $("<tr><td id ='newbox' contenteditable= 'true' style='color:#778899;'"
            +
            ">Click to Add New School Name</td>' // term name"
            +
            "<td><button class='btn btn-sm deleteRow'>Delete</button></td>"
            + '<td style="display:none;"> <input type="checkbox" value="check1" id="changebox1"></td>'
            + '<td style="display:none;">' +
            '<input type="checkbox" value="newchecked" id="newCheckbox1" checked></td></tr>');

    }

    function blankClassRow($schoolSelect) {
        return '<tr><td id ="newbox" contenteditable= "true" style="color:#778899;">Enter A Class Name</td>' // class name
            +
            $schoolSelect
            +
            '<td><button class="btn btn-sm deleteRow">Delete</button></td>'
            + '<td style="display:none;"> <input type="checkbox" value="check1" id="changebox1"></td>'
            + '<td style="display:none;">' +
            '<input type="checkbox" value="newchecked" id="newCheckbox1" checked></td></tr>';

    }
    function saveEntireJson(type,data) {
        for (let findex = 0; findex < data[type].length; findex++) {
            if (data[type][findex]['newCheck'] || data[type][findex]['checked']) {
                for (let index = 0; index < changeArray[type].length; index++) {
                    switch (type) {
                        case "terms":
                            var tempComp1 = changeArray[type][index]['word'];
                            var tempComp2 = changeArray[type][index]['definition'];
                            var tempComp3 = data[type][findex]['definition'];
                            var tempComp4 = data[type][findex]['word'];
                            if (tempComp1 == tempComp4 || tempComp2 == tempComp3) {
                                changeArray["terms"][index] = data[type][findex];
                            }
                            break;
                        case "categories":
                            var tempComp1 = changeArray[type][index]['catName'];
                            var tempComp2 = changeArray[type][index]['level'];
                            var tempComp3 = data[type][findex]['level'];
                            var tempComp4 = data[type][findex]['catName'];
                            if (tempComp1 == tempComp4 || tempComp2 == tempComp3) {
                                changeArray["categories"][index] = data[type][findex];
                            }

                            break;
                        case "schools":
                            var tempComp1 = changeArray[type][index]['schoolName'];
                            var tempComp2 = data[type][findex]['schoolName'];
                            if (tempComp1 == tempComp2) {
                                changeArray["schools"][index] = data[type][findex];
                            }


                            break;
                        case "classes":
                            var tempComp1 = changeArray[type][index]['className'];
                            var tempComp2 = changeArray[type][index]['gradeLevel'];
                            var tempComp3 = data[type][findex]['gradeLevel'];
                            var tempComp4 = data[type][findex]['className'];
                            if (tempComp1 == tempComp4 || tempComp2 == tempComp3) {
                                changeArray["classes"][index] = data[type][findex];
                            }

                            break;
                        default:

                    }
                }
            }
        }


    }
    // needs validation from checkifDataExists if not this will fail;
    function saveToJson(type,data) {
        var workingLength;
        var finalLength = 0;
        var offset = 0;
        var specialLength = 0; // for when curent page of data is less than sortNumber.
        if (searchActive == 1) {
            saveEntireJson(type,data);
        } else {
            workingLength = data[type].length - sortNumber;
            if (workingLength < sortNumber) {
                for (let findex = 0; findex < data[type].length; findex++) {
                    if (data[type][findex]['newCheck']) {
                        specialLength++;
                        offset++;
                        previousData[type] = insertBlankDataIntoObject(0,previousData[type],data[type][findex] );   // this fills in the blank space for the oldData stuff
                    }
                }
                finalLength = data[type].length - offset;
            } else {
                specialLength = workingLength;
                finalLength = currentEndIndex;
            }
            var counterIndex = 0;
            // this will overwrite current table with value 1-10 of new table
            for (let dindex = currentStartIndex; dindex < finalLength; dindex++) {
                if (!(typeof data[type][counterIndex] == 'undefined')) { // prevents undefinded stuff on shortened last html page of short first page
                    changeArray[type][dindex] = data[type][counterIndex];
                }
                counterIndex++;
            }
            //if there is extra by adding new table entries this will add that to the end
            for (let index = (changeArray[type].length); index < (changeArray[type].length + specialLength); index++) {
                
                if (!(typeof data[type][counterIndex] == 'undefined')) { // prevents undefinded stuff on shortened last html page of short first page
                    changeArray[type][index] = data[type][counterIndex];
                    if(type !="schools"){
                        console.log(getAllSelectTypes[type]);
                    savedSelect[type][index] = getAllSelectTypes[type];
                    }
                }
                counterIndex++;
            }
            //savedSelect[type] = tempArray;
           // console.log(tempArray);
            //console.log(savedSelect[type]);
          //  console.log(previousData[type]);
        }


    }
    function getValidData(type) {
        var TableData = new Array();
        TableData[type] = retrieveCurrentData(type);
        if (TableData[type] != "fail") {
            TableData[type].shift(); // first row is the table header and didnt ad to array - so remove blank row.
            return TableData;
        } else {
            confirmModal("Data Issue <br> 1. Empty field<br> OR: <br>2. Select box not "
                + "selected. <br>Please enter data into all fields and choose an option for all select boxes.");
            return false;
        }
    }


    function finalizeChanges(oldData) {  
      //  console.log(oldData);
       // console.log(changeArray);
        var changeStringNew = "";
        var changeStringOld = "";
        var finalString = "";
       
        for (value in types) {
            type = types[value];
            var tempArray = new Array(); // new array for each type
            if (types.hasOwnProperty(value) && (!(typeof changeArray[type] == 'undefined'))) {
                for (let cindex = 0; cindex < changeArray[type].length; cindex++) {
                   
                    // prevents undefinded stuff on shortened last html page of short first page and if checked then ther are changes
                    if ((!(typeof changeArray[type][cindex] == 'undefined')) && changeArray[type][cindex]['checked']) { //if changes
                        // then then add those chages to final array 
                       tempArray [cindex] = changeArray[type][cindex];
            
                        //-------- for reporting changes to string
                        for (key in changeArray[type][cindex]) {
                            if (changeArray[type][cindex].hasOwnProperty(key) && key != 'checked' && key != 'newCheck') {
                                changeStringNew += "" + changeArray[type][cindex][key] + " ";
                            }
                        }

                        for (key in oldData[type][cindex]) {
                            if (oldData[type][cindex].hasOwnProperty(key)) {
                                changeStringOld += "" + oldData[type][cindex][key] + " ";
                            }
                        }
                        // end of to string stuff -----------------------------

                        finalString += ("<br>Changed " + changeStringOld + "(Old) To: " + changeStringNew + "(New) \n");
                    } else if((!(typeof changeArray[type][cindex] == 'undefined')) && changeArray[type][cindex]['newCheck']) { // for new addtions
                        tempArray [cindex] = changeArray[type][cindex];
            
                        //-------- for reporting new additons to string
                        for (key in changeArray[type][cindex]) {
                            if (changeArray[type][cindex].hasOwnProperty(key) && key != 'checked' && key != 'newCheck') {
                                changeStringNew += "" + changeArray[type][cindex][key] + " ";
                            }
                        }
                        // end of to string stuff -----------------------------

                        finalString += ("<br>Adding " + changeStringNew + "(New). \n");
                    }else tempArray [cindex] = oldData[type][cindex]; //else just resend old data because it's proven;

                }
            } else console.log("NO " + type +" loaded");

            savedArray[type] = tempArray;
        }
    
        $("#sure .modal-body").html("The following changes will be attempted:" + finalString);
        $("#modalsave").removeClass("delete").removeClass("presave").addClass("save");
        $("#modalsave").text("Save");
        $('#modalsave').removeClass('btn-danger').addClass('btn-warning');
        $("#modalsave").show();
        $("#sure").modal('show');

    }
    function retrieveCurrentData(type) {
        var save = true; //for if save can happen
        // this iterates throught every value in the table and stores it in an array
        var TableData = new Array();
        //first time throght retrns header so set ing up count
        var tableCount = 0;
        $('#word_table tr').each(function (row, tr) {

            // count is greater than the first row which is the header then return values;
            if (tableCount <= 0) {
                // do nothing
                tableCount++;
            } else {
                switch (type) {
                    case "terms":
                        if (($.trim($(tr).find('td:eq(1)').text()) == "" || $(tr).find('td:eq(2)').text()) == "" ||
                            $(tr).find('td:eq(0)').find('select').val() == '0'
                            || $(tr).find('td:eq(0)').find('select').val() == null) {
                            save = false;
                            return;
                        } else {
                            categoryAndLevel = $(tr).find('td:eq(0)').find('select').val().trim();
                            level = categoryAndLevel.substring(categoryAndLevel.length - 1);
                            category = categoryAndLevel.substring(0, categoryAndLevel.length - 1);
                            TableData[row] = {
                                "word": $(tr).find('td:eq(1)').text(),
                                "definition": $(tr).find('td:eq(2)').text(),
                                "category": category,
                                "gradeLevel": level,
                                "checked": $(tr).find('td:eq(4) #changebox1').is(':checked'),
                                "newCheck": $(tr).find('td:eq(5) #newCheckbox1').is(':checked')
                            }
                        }
                        break;
                    case "categories":
                        if (($.trim($(tr).find('td:eq(0)').text()) == "" ||
                            $(tr).find('td:eq(1)').find('select').val() == '0'
                            || $(tr).find('td:eq(1)').find('select').val() == null)) {
                            save = false;
                            return;
                        } else {
                            TableData[row] = {
                                "catName": $.trim($(tr).find('td:eq(0)').text()),// trim this data to eliminate trailing spaces
                                "level": $(tr).find('td:eq(1)').find('select').val(),
                                "checked": $(tr).find('td:eq(3) #changebox1').is(':checked'),
                                "newCheck": $(tr).find('td:eq(4) #newCheckbox1').is(':checked')
                            }
                        }
                        break;
                    case "schools":
                        if ($.trim($(tr).find('td:eq(0)').text()) == "") {
                            save = false;
                            return;
                        } else {
                            TableData[row] = {
                                "schoolName": $.trim($(tr).find('td:eq(0)').text()),
                                "checked": $(tr).find('td:eq(2) #changebox1').is(':checked'),
                                "newCheck": $(tr).find('td:eq(3) #newCheckbox1').is(':checked')
                            }
                        }
                        break;
                    case "classes":
                        if (($.trim($(tr).find('td:eq(0)').text()) == "" ||
                            $(tr).find('td:eq(1)').find('select').val() == '0'
                            || $(tr).find('td:eq(1)').find('select').val() == null ||
                            $(tr).find('td:eq(2)').find('select').val() == '0'
                            || $(tr).find('td:eq(2)').find('select').val() == null)) {
                            save = false;
                            return;
                        } else {
                            TableData[row] = {
                                "className": $.trim($(tr).find('td:eq(0)').text()),// trim this data to eliminate trailing spaces
                                "gradeLevel": $.trim($(tr).find('td:eq(1)').find('select').val()),
                                "schoolName": $(tr).find('td:eq(2)').find('select').val(),
                                "checked": $(tr).find('td:eq(4) #changebox1').is(':checked'),
                                "newCheck": $(tr).find('td:eq(5) #newCheckbox1').is(':checked')
                            }
                        }
                        break;
                    default:
                }

            }
        });

        if (save) {
            return TableData;
        } else return "fail";
    }

    function saveToDB( tableInfo, oldData) {

        tableInfo = JSON.stringify(tableInfo);
        oldInfo = JSON.stringify(oldData);
        $.ajax({
            type: "POST",
            url: "/php/inc-admin-data.php",
            data: {
                info: tableInfo,
                oldData: oldInfo
            },
            success: function (data) {
                confirmModal(data);
                //console.log(data);
               // currentPage = type;
                // start out with categories
                eraseAllCookies(); // this allows a fresh db screening
                getTableData(currentPage);
                $("#" + currentPage).tab("show");
            }
        });

    }

    function updateTableFromJson(jsonData, pageType) {
        var newSelectData = new Array(); // array for adding new list of selecteds to update 
        for (let jindex = 0; jindex < jsonData.length; jindex++) {
            switch (pageType) {
                case "terms":
                    catAndLev = jsonData[jindex]['category'].trim() + " " + jsonData[jindex]['gradeLevel'].trim();

                    newSelected = $(savedSelect[pageType][jindex]);
                    newSelected.find('#selcat option[value="' + catAndLev + '"]').attr("selected", true).siblings()
                        .removeAttr("selected");
                    newSelectData[jindex] = ("<td>" + newSelected.html() + "</td>");
                    break;
                case "categories":
                    lev = jsonData[jindex]['level'];
                    newSelected = $(savedSelect[pageType][jindex]);
                    newSelected.find('#sellev option[value="' + lev + '"]').attr("selected", true).siblings()
                        .removeAttr("selected");
                    newSelectData[jindex] = ("<td>" + newSelected.html() + "</td>");
                    break;
                case "schools":

                    break;
                case "classes":
                    lev = jsonData[jindex]['gradeLevel'];
                    school = jsonData[jindex]['schoolName'];
                    //elemitate the inner td used in other parts for the table and split stirngs
                    newSelectedArr = savedSelect[pageType][jindex].split("</td>  <td>");
                    newSelected1 = $(newSelectedArr[0] + "</td>");
                    newSelected2 = $("<td>" + newSelectedArr[1]);
                    newSelected1.find('#sellev option[value="' + lev + '"]').attr("selected", true).siblings()
                        .removeAttr("selected");
                    newSelected2.find('#selschool option[value="' + school + '"]').attr("selected", true).siblings()
                        .removeAttr("selected");
                    //   console.log(newSelected1.find('select').val());
                    //   console.log(newSelected2.find('select').val());
                    newSelectData[jindex] = newSelected1[0].outerHTML + "" + newSelected2[0].outerHTML;
                    break;
                default:
                    rows = "error: addRows.admin_data.js";
            }
        }

        return createTableFromJSON(jsonData, pageType, newSelectData);

    }

    function createTableFromJSON(jsonData, pageType, selectData) {
        var jsonList = new Array();
        switch (pageType) {
            case "terms":
                for (let jindex = 0; jindex < jsonData.length; jindex++) {
                    jsonList.push("<tr>" + selectData[jindex] +
                        '<td contenteditable= "true">' + jsonData[jindex]["word"] + '</td>' // term name
                        +
                        '<td contenteditable= "true" >' + jsonData[jindex]["definition"] + '</td>' /// for level
                        +
                        '<td><button class="btn btn-sm deleteRow">Delete</button></td><td style="display:none;">' +
                        '<input type="checkbox" value="check1" id="changebox1"></td></tr>');
                }
                break;
            case "categories":
                for (let jindex = 0; jindex < jsonData.length; jindex++) {
                    jsonList.push("<tr>" +
                        '<td contenteditable= "true">' + jsonData[jindex]["catName"] + '</td>' // term name
                        +
                        selectData[jindex]
                        +
                        '<td><button class="btn btn-sm deleteRow">Delete</button></td><td style="display:none;">' +
                        '<input type="checkbox" value="check1" id="changebox1"></td></tr>');

                }
                break;
            case "schools":
                for (let jindex = 0; jindex < jsonData.length; jindex++) {
                    jsonList.push("<tr>" + '<td contenteditable= "true">' + jsonData[jindex]["schoolName"] + '</td>' // term name
                        +
                        '<td><button class="btn btn-sm deleteRow">Delete</button></td><td style="display:none;">' +
                        '<input type="checkbox" value="check1" id="changebox1"></td></tr>');
                }
                break;
            case "classes":
                for (let jindex = 0; jindex < jsonData.length; jindex++) {
                    jsonList.push("<tr>" +
                        '<td contenteditable= "true">' + jsonData[jindex]["className"] + '</td>' // term name
                        +
                        selectData[jindex]
                        +
                        '<td><button class="btn btn-sm deleteRow">Delete</button></td><td style="display:none;">' +
                        '<input type="checkbox" value="check1" id="changebox1"></td></tr>');
                }

                break;
            default:
        }

        return jsonList;
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
                        $returnString = "word ASC";
                        break;
                    case 3:
                        $returnString = "definition ASC";
                        break;
                    case 4:
                        $returnString = "category DESC";
                        break;
                    case 5:
                        $returnString = "word DESC";
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
                        $returnString = "catName ASC";
                        break;
                    case 2:
                        $returnString = "level ASC";
                        break;
                    case 4:
                        $returnString = "catName DESC";
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
                        $returnString = "schoolName ASC";
                        break;
                    case 4:
                        $returnString = "schoolName DESC";
                        break;
                    default:
                        $returnString = "ID";
                }
                break;
            case "classes":
                switch (orderBy) {
                    case 1:
                        $returnString = "className ASC";
                        break;
                    case 2:
                        $returnString = "gradeLevel ASC";
                        break;
                    case 3:
                        $returnString = "schoolName ASC";
                        break;
                    case 4:
                        $returnString = "className DESC";
                        break;
                    case 5:
                        $returnString = "gradeLevel DESC";
                        break;
                    case 6:
                        $returnString = "schoolName DESC";
                        break;
                    default:
                        $returnString = "ID";
                }
                break;
            default:


        }
        return $returnString;
    }

    function sortLocal(type) {
        orderBy = sortBy;

        masterList = new Array()

        $returnString = "ID";
        switch (type) {
            case "terms":
                switch (orderBy) {
                    case 1:
                        masterList = changeArray[type].sort(function (a, b) {
                            return a.category.localeCompare(b.category);
                        });
                        break;
                    case 2:
                        masterList = changeArray[type].sort(function (a, b) {
                            return a.word.localeCompare(b.word);
                        });
                        break;
                    case 3:
                        masterList = changeArray[type].sort(function (a, b) {
                            return a.definition.localeCompare(b.definition);
                        });
                        break;
                    case 4:
                        masterList = changeArray[type].sort(function (a, b) {
                            return b.category.localeCompare(a.category);
                        });
                        break;
                    case 5:
                        masterList = changeArray[type].sort(function (a, b) {
                            return b.word.localeCompare(a.word);
                        });
                        break;
                    case 6:
                        masterList = changeArray[type].sort(function (a, b) {
                            return b.definition.localeCompare(a.definition);
                        });
                        break;
                    default:
                        return changeArray[type];
                }
                break;
            case "categories":
                switch (orderBy) {
                    case 1:
                        masterList = changeArray[type].sort(function (a, b) {
                            return a.catName.localeCompare(b.catName);
                        });
                        break;
                    case 2:
                        masterList = changeArray[type].sort(function (a, b) {
                            return a.level.localeCompare(b.level);
                        });
                        break;
                    case 4:
                        masterList = changeArray[type].sort(function (a, b) {
                            return b.catName.localeCompare(a.catName);
                        });
                        break;
                    case 5:
                        masterList = changeArray[type].sort(function (a, b) {
                            return b.level.localeCompare(a.level);
                        });
                        break;
                    default:
                        return changeArray[type];
                }
                break;
            case "schools":
                switch (orderBy) {
                    case 1:
                        masterList = changeArray[type].sort(function (a, b) {
                            return a.schoolName.localeCompare(b.schoolName);
                        });
                        break;
                    case 4:
                        masterList = changeArray[type].sort(function (a, b) {
                            return b.schoolName.localeCompare(a.schoolName);
                        });
                        break;
                    default:
                        return changeArray[type];
                }
                break;
            case "classes":
                switch (orderBy) {
                    case 1:
                        masterList = changeArray[type].sort(function (a, b) {
                            return a.className.localeCompare(b.clasName);
                        });
                        break;
                    case 2:
                        masterList = changeArray[type].sort(function (a, b) {
                            return a.gradeLevel.localeCompare(b.gradeLevel);
                        });
                        break;
                    case 3:
                        masterList = changeArray[type].sort(function (a, b) {
                            return a.schoolName.localeCompare(b.schoolName);
                        });
                        break;
                    case 4:
                        masterList = changeArray[type].sort(function (a, b) {
                            return b.className.localeCompare(a.className);
                        });
                        break;
                    case 5:
                        masterList = changeArray[type].sort(function (a, b) {
                            return b.gradeLevel.localeCompare(a.gradeLevel);
                        });
                        break;
                    case 6:
                        masterList = changeArray[type].sort(function (a, b) {
                            return b.schoolName.localeCompare(a.schoolName);
                        });
                        break;
                    default:
                        return changeArray[type];
                }
                break;
            default:


        }
        return masterList;
    }


    function getSelectTypes(type) {
        $.ajax({
            type: "POST",
            url: "/php/inc-admin-data.php",
            data: {
                get: type,
                selected: ""
            },
            success: function (data) {
                getAllSelectTypes[type] = data;
            }

        });

    }

    function markSorted(index) {
        $(".removeLater").remove();
        if (index == 1) {
            if (sortBy == 1) {
                $("#word_table").find('th:eq(0)').append('<span class="removeLater">&#9650</span>');
            } else $("#word_table").find('th:eq(0)').append('<span class="removeLater">&#9660</span>');
        } else if (index == 2) {
            if (sortBy == 2) {
                $("#word_table").find('th:eq(1)').append('<span class="removeLater">&#9650</span>');
            } else $("#word_table").find('th:eq(1)').append('<span class="removeLater">&#9660</span>');

        } else if (index == 3) {
            if (sortBy == 3) {
                $("#word_table").find('th:eq(2)').append('<span class="removeLater">&#9650</span>');
            } else $("#word_table").find('th:eq(2)').append('<span class="removeLater">&#9660</span>');

        }
    }

    // from w3schools
    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        var expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }
    //from w3school
    function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }
    function checkCookie(cname) {
        var freshData = getCookie(cname);
        if (freshData != "") {
            return true;
        } else {
            return false;
        }
    }

    function eraseAllCookies() {
        setCookie('freshcategories', true, 0);
        setCookie('freshterms', true, 0);
        setCookie('freshschools', true, 0);
        setCookie('freshclasses', true, 0);

    }

    // for splice into object
    function insertBlankDataIntoObject(index, currObject,newItem){
      
        tempItem = Object.values(currObject);
        tempItem.splice(index,0, newItem);
        return tempItem;
           
    }

});