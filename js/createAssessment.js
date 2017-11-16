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
    
        $('#addRow').click(function (event) {
            event.preventDefault();
            $.get('../php/inc-createassessment-getcategories.php', function (data) {
                $categoriesSel = data;
                var rows = $('<tr><td>0</td>' +
                    $categoriesSel +
                    '<td contenteditable= "true">Enter A New Word</td>' // term name
                    +
                    '<td contenteditable= "true">Enter New Definition</td>' /// for level
                    +
                    '<td><button class="btn btn-sm deleteRow">Delete</button></td></tr>');
                $('#assessment_table').append(rows);
            });
        });

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