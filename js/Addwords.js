$(document).ready(function(){
    var rowCount;

    // this adds a new row for adding more words
       $('#addRow').click(function(event){
        event.preventDefault();
        rowCount = $('#word_table tr').length; // because of the header row count is already plus 1
        var wordnumber= $('#word_table tr:last-child td:first-child').html();
        var id = parseInt(wordnumber)
        var newRow = $('<tr><td contenteditable= "true" id = "cell'+ (id+1) +'_'+ '1'
          + '">'+ (id+1) +'</td><td contenteditable= "true" id = "cell'+ (id+1) +'_'+ '2'
          + '">new</td><td contenteditable= "true" id = "cell'+ (id+1) +'_'+ '3'
          + '">input</td><td contenteditable= "true" id = "cell'+ (id+1) +'_'+ '4'
          + '">new</td><td contenteditable= "true" id = "cell'+ (id+1) +'_'+ '5'
          + '">2</td><td id = "cell'+ (id+1) +'_'+ '6'
          + '"><button  class="deleteRow" id= "deleteRow'+ (id+1)+'">Delete</button></td?</tr>');
        $('#word_table').append(newRow);
        });
        
        
         // this is for deleteing words and definition 
        $(document).on("click",".deleteRow" ,function(){
           $( $(this).parents('tr')).remove();
            var wordnumber= $('#word_table tr:last-child td:first-child').html();
            var totalnumber = parseInt(wordnumber)
        });

        // reload the origingal data database
        $(document).on("focus",'[contenteditable="true"]' ,function(){
            $(this).on('keydown', function(e) {
                if (e.which == 13 && e.shiftKey == false) {                  
                   $ (this).blur();
                  return false;
                }else if(e.which == 27){  // undo 
                    $.get("../php/inc-addwords.php", function(data, status){
                        alert("Data: " + data + "\nStatus: " + status);
                        $ (this).blur();
                    });
                }
              });
        });
        // saving new stuff to database
        $('[contenteditable="true"]').one('blur', function() {
            $.get("../php/inc-addwords.php", function(data, status){
                alert("Data: " + data + "\nStatus: " + status);
            });
        });

});