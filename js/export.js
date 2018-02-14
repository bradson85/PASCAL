$(document).ready(function () {
 
 
       function modalStuff($message1,$message2){

        $("#confirm .modal-title").text($message1);
        $("#confirm .modal-body").text($message2);
        $("#confirm").modal('show');
        $("#modalclose").on("click", function () {
            $("#confirm").modal('hide');
      });
       }
  
});


