$(document).ready(function(){
    
        $('.messages').hide();
    
        $(document).on("change", "select",function(){
            var catID = $(this).val();
            console.log(catID);
            $.ajax({
                type: "POST",
                url: "../php/inc-createassessmentupdatetable.php",
                data: {
                    data: catID       
                },
                        success: function (data) {
                    $('#t_body').html(data);
                    $('.messages').show();
                 
                }
            });
        });
    // this calls funtion loadCategory for top category dropdown on doculment load
                loadCategorySelect();
    //this calls loading existing assessments to open\
                loadAssessments();
    
    
        function loadCategorySelect(){
              $.ajax({
                type: "POST",
                url: "../php/inc-createassessment-getcategories.php",
                data: {
                    data:  ""    
                },
                        success: function (data) {
                    $("#categorychoice").append(data);
                    
                }
            });
    
    
        }
    
    
        function loadAssessments(){
            $.ajax({
              type: "POST",
              url: "../php/inc-createassessment-getassessments.php",
              data: {
                  data:  ""    
              },
                      success: function (data) {
                  $("#t_body2").html(data);
                  
              }
          });
    
    
      }
    
    });