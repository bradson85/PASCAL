 
 $(document).ready(function () {
 
    $('#fileForm').on("submit",function(e){  
        e.preventDefault(); //form will not submitted
        $.ajax({  
            url:"php/inc-import.php",  
            method:"POST",  
            data:new FormData(this),  
            contentType:false,          // The content type used when sending data to the server.  
            cache:false,                // To unable request pages to be cached  
            processData:false,          // To send DOMDocument or non processed data file it is set to false  
            success: function(data){  
                var json = $.parseJSON(data);
                if(json[0]== true ){
                    modalStuff("Error",json[3]);
                }else{
                    modalStuff("Success",json[1]);
                    $('div#results').html(json[2]);
                }
            }  
       })  
  }); 
       function modalStuff($message1,$message2){

        $("#confirm .modal-title").text($message1);
        $("#confirm .modal-body").text($message2);
        $("#confirm").modal('show');
        $("#modalclose").on("click", function () {
            $("#confirm").modal('hide');
      });
       }


       $("input:file").change(function (){
        var fileName = $(this).val();
        if(fileName) { // returns true if the string is not empty
            $('#fileup').removeAttr('disabled');
        } else { // no file was selected
            
        }
      });
  
});


