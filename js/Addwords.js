$(document).ready(function(){
    
    // loads table when document loads
    $(document).ready(function(){
          loadDB();
     });
function loadDB(){
     $.ajax({
        url: 'inc-addwords-read.php',
        type: "GET",
        dataType: "json",
        success: function (data) {
            $.each(data, function(i, value){
                var rows = $('<tr><td>'+ value.ID +'</td>'+
                '<td contenteditable= "true">'+ value.category +'</td>' 
              + '<td contenteditable= "true">'+ value.name+'</td>'
                + '<td contenteditable= "true">'+ value.definition +'</td>'
                + '<td>'+value.level+'</td>'
                + '<td><button class="btn btn-sm deleteRow">Delete</button></td></tr>');
              $('#word_table').append(rows);
              });
        
        }
        });
    }
    // this adds a new row for adding more words
       $('#addRow').click(function(event){
        event.preventDefault();
       var rowCount = $('#word_table tr').length -1; // because of the header row count is already plus 1
        if($.isNumeric(rowCount)){
        var id = rowCount
        }else{
           var id = 0;
        }
        var newRow = $('<tr><td>'+ (id+1) +'</td>'+
          '<td contenteditable= "true">Science</td>' 
        + '<td contenteditable= "true">Example Word</td>'
          + '<td contenteditable= "true">New Defintion Goes Here</td>'
          + '<td>N/A</td>'
          + '<td><button class="btn btn-sm deleteRow">Delete</button></td></tr>');
        $('#word_table').append(newRow);
        });
        
        
         // this is for deleteing words and definition 
        $(document).on("click",".deleteRow" ,function(){
           var currID =  $(this).parent().siblings(":first").text();
           $( $(this).parents('tr')).remove();
            var wordnumber= $('#word_table tr:last-child td:first-child').html();
          console.log(currID);
            $.ajax({
                type: "POST",
                url: "inc-addwords-deleterow.php",
                data: {data: currID},
                success: function(data) {
                    console.log(data);
                }
            });
            
        });

        // reload the original data database
        $(document).on("focus",'[contenteditable="true"]' ,function(){
            $(this).on('keydown', function(e) {
                if (e.which == 13 && e.shiftKey == false) {                  
                   $ (this).blur();
                  return false;
                }else if(e.which == 27){  // undo 
                   
                        $(this).blur();
                        $("#t_body").empty();
                        loadDB();
                        return false;
                }
              });
        });
        // saving new stuff to database on the "blurring " of editable table
        $("#word_table").on("blur" , '[contenteditable="true"]', function(){
            var TableData = new Array();         
        $('#word_table tr').each(function(row, tr){
            TableData[row]={
                    "ID" : $(tr).find('td:eq(0)').text() 
                , "category" :$(tr).find('td:eq(1)').text()
                , "word" : $(tr).find('td:eq(2)').text()
                , "definition" : $(tr).find('td:eq(3)').text()
                , "level" : $(tr).find('td:eq(4)').text()    // level may not be used since for now is dependent on category.
            }
        }); 
        TableData.shift();  // first row is the table header - so remove
        TableData = JSON.stringify(TableData);
           console.log(TableData);
           $.ajax({
                type: "POST",
                url: "inc-addwords-update.php",
                data: {data: TableData},
                success: function(data) {
                    console.log(data);
                }
            });
            
        });

});