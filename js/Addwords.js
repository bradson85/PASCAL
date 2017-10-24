$(document).ready(function () {

    $('.alert').hide();

    // loads table when document loads
    $(document).ready(function () {
        loadDB();
        setTimeout(
            function () {
                updateSelected();
            }, 250);



    });

    function loadDB() {
        $.get('inc-addwords-read.php', function (data) {
            $("#t_body").html(data);
        });
    }

    function updateSelected() {
        var ID = new Array();
        $('#word_table tr').each(function (row, tr) {
            ID[row] = {
                "ID": $(tr).find('td:eq(0)').text()
            }

        });
        ID.shift();
        ID = JSON.stringify(ID);
        $.ajax({
            type: "POST",
            url: "inc-addwords-functions.php",
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

    $("#fileForm").on('submit',(function(e) {
        e.preventDefault();
        console.log(new FormData(jQuery('form')[0]));
        $.ajax({
        url: "inc-addwords-importFile.php", // Url to which the request is send
        type: "POST",             // Type of request to be send, called as method
        data: new FormData($('form')[0]), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
        contentType: false,       // The content type used when sending data to the server.
        cache: false,             // To unable request pages to be cached
        processData:false,        // To send DOMDocument or non processed data file it is set to false
        success: function(data)   // A function to be called if request succeeds
        {
            console.log(data);
        }
        });
        }));

    // this adds a new row for adding more words
    $('#addRow').click(function (event) {
        event.preventDefault();
        $.get('inc-addwords-getcategories.php', function (data) {
            $categoriesSel = data;
            var rows = $('<tr><td>0</td>' +
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
        var currID = $(this).parent().siblings(":first").text();
        $($(this).parents('tr')).remove();
        var wordnumber = $('#word_table tr:last-child td:first-child').html();
        $.ajax({
            type: "POST",
            url: "inc-addwords-deleterow.php",
            data: {
                data: currID
            },
            success: function (data) {
                $('#success').show();
                $('#success strong').text(data);
                setTimeout(
                    function () {
                        $('#success').fadeOut();
                    }, 10000);
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
    // saving new stuff to database on the "blurring " of editable table
    $("#save").on("click", function () {
        var TableData = new Array();
        $('#word_table tr').each(function (row, tr) {
            TableData[row] = {
                "ID": $(tr).find('td:eq(0)').text(),
                "category": $(tr).find('td:eq(1)').find('select').val(),
                "word": $(tr).find('td:eq(2)').text(),
                "definition": $(tr).find('td:eq(3)').text()
            }
        });
        TableData.shift(); // first row is the table header - so remove
        TableData = JSON.stringify(TableData);
        $.ajax({
            type: "POST",
            url: "inc-addwords-update.php",
            data: {
                data: TableData
            },
            success: function (data) {
                if(data== 0){
                    $('#warning').show();
                    $('#warning strong').text("Make Sure You Select A Category");
                    setTimeout(
                        function () {
                            $('#warning').fadeOut();
                        }, 3000);
                }else {if (data== 1){
                $('#success').show();
                $('#success strong').text("Saved Successfully");
                setTimeout(
                    function () {
                        $('#success').fadeOut();
                    }, 3000);
               
               
                loadDB();
                setTimeout(
                    function () {
                        updateSelected();
                    }, 200);
            }
        }}
        });

    });

});