$(document).ready(function () {

    $('input.form-check-input').on('change', function() {
        $('input.form-check-input').not(this).prop('checked', false);  
    });

var savetype="";
var datatype= "";
    $("#sendform").click(function(e) {
        e.preventDefault();
        if($("#r").is(':checked')){ 
            restoreDB();
          }else {
        if($("#s").is(':checked')){ 
            savetype =  "Backup";
        } else if($("#sd").is(':checked')){ 
        savetype =  "Delete";
        }
        
        datatype = $('#sel1 :selected').val();

        $("#sure .modal-title").text("Are You Sure You Want To " + savetype+ " Entire Database?");
        if(savetype == "Delete"){
            $("#sure .modal-body").text("If you delete, once that data has been deleted you cannot get that data back.");
        } else $("#sure .modal-body").text("Saving data to "+datatype);
        $("#modalsave").text("" + savetype);
        $('#modalsave').removeClass('btn-danger').addClass('btn-warning');
        $("#modalsave").show();
        $("#sure").modal('show');

             if (savetype == "Backup"){
                       if(datatype == "SQL"){
                        $('#modalsave').attr("href", "/php/inc-backup-toSQL.php");
                       }else   if(datatype == "CSV"){
                        $('#modalsave').attr("href", "/php/inc-backup-toCSV.php");
                       }else   if(datatype == "TEXT"){
                        $('#modalsave').attr("href", "/php/inc-backup-toTXT.php");
                   }
             } else if (savetype == "Delete"){
                $('#modalsave').removeClass('btn-warning').addClass('btn-danger');
                $('#modalsave').attr("href", "/php/inc-backup-delete.php");
             }
            }
    });



    function restoreDB(){
    $.get("/php/inc-backup-restore.php", function(data, status){

        if (status == 'success'){ 
        $("#confirm .modal-title").text(status.charAt(0).toUpperCase() + status.substr(1));
        $("#confirm .modal-body").html("Emergency Restore appears to have worked. <br> Check \"Data Administration\" to see if data has been restored. <br> If not then Emergency Restore failed.");
        $("#confirm").modal('show');
        $("#modalclose").on("click", function () {
            $("#confirm").modal('hide');
        });
    } else  $("#confirm .modal-title").text(statuscharAt(0).toUpperCase() + status.substr(1));
    $("#confirm .modal-body").html("Emergency Restore appears to have failed. <br> Check \"Data Administration\" to see if data has been restored. <br> If not then Emergency Restore failed.");
    $("#confirm").modal('show');
    $("#modalclose").on("click", function () {
        $("#confirm").modal('hide');
    });
    });
}

});