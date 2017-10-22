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
        success: function (termdata) {
            // second call to get categories
            $.each(termdata, function(i, value){
                var rows = $('<tr><td>'+ value.ID +'</td>'+
                '<td contenteditable= "true">'
                +'<select class="form-control" id="selcat">'
               + '</select></td>' 
              + '<td contenteditable= "true">'+ value.name+'</td>'  // term name
                + '<td contenteditable= "true">'+ value.definition +'</td>'
                +'<td contenteditable= "true">'        /// for level
                +'<select class="form-control" id="sellev">'
                + '</select></td>' 
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
      $.ajax({
                url: 'inc-addwords-getcategories.php',
                type: "GET",
                dataType: "json",
                success: function (catdata) {
                    
                        var rows = $('<tr><td>#newID</td>'+
                        '<td contenteditable= "true">'
                        +'<select class="form-control" id="selcat">'
                       + '</select></td>' 
                      + '<td contenteditable= "true">Enter New Word</td>'  // term name
                        + '<td contenteditable= "true">Enter New Definition</td>'
                        +'<td contenteditable= "true">'        /// for level
                        +'<select class="form-control" id="sellev">'
                        + '</select></td>' 
                        + '<td><button class="btn btn-sm deleteRow">Delete</button></td></tr>');
                      $('#word_table').append(rows);
                    
                      $.each(catdata, function(i, value){
                        $('#selcat').append($('<option>', { 
                            value: value.catName,
                            text : value.CatName 
                        }));
                    
                      });
                  
                }
                });
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

        //This fucntion causes a blur when the Enter Key is hit 
        // it blurs the table so it appears that the editing is done
        // Also it doesnt save changes when escape is entered
        $(document).on("focus",'[contenteditable="true"]' ,function(){
            $(this).on('keydown', function(e) {
                if (e.which == 13 && e.shiftKey == false) {                  
                   $ (this).blur();
                  return false;
                }else if(e.which == 27){  // to exit editing without saving then reload db
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