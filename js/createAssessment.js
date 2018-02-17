$(document).ready(function(){
    
    $('.messages').hide();
    
    loadStudents(1);

    $('#class').change(function() {
        loadStudents(document.getElementById('#class').value());
    });

    
    $('#selfSelect').click(function (){
        alert('self select is much harder but here you go....');
    });

    


    $(document).on("change", "select",function(){
        var catID = $(this).val();
        console.log(catID);

        $.ajax({
            type: "POST",
            url: "php/inc.create-assessment.php",
            dataType: "json",
            data: {
                catID: catID
            },
            success: function(response) {
                console.log(response);
                for(let i = 0; i < response.length; i++) {
                    setTimeout(50,showTerms(response[i]));
                    
                }
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
                url: "php/inc-createassessment-getcategories.php",
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
            url: "php/inc-createassessment-getassessments.php",
            data: {
                data:  ""    
            },
            success: function (data) {
                $("#t_body2").html(data);    
            }
        });
    }

    function showTerms(obj) {
        let preTitle = "<div class=\"card\"> <div class=\"card-header\" id=\"heading" + obj.ID + "\"> <h5 class=\"mb-0\"><button class=\"btn btn-link\" data-toggle=\"collapse\" data-target=\"#collapse" + obj.ID + "\" aria-controls=\"collapse" + obj.ID + "\">";
        let postTitle = "</button> </h5> </div>";
        let preBody = "<div id=\"collapse" + obj.ID + "\" class=\"collapse show\" aria-labelledby=\"heading" + obj.ID + "\" data-parent=\"terms\" <div class=\"card-body\"> <table class=\"table table-striped\"> <thead><tr><th></th><th>Term</th><th>Definition</th><th>Match</th></tr></thead><tbody>";
        let postBody = "</tbody></table></div></div>";
        let html = "";
        html += (preTitle + obj.name + " - Grade " + obj.level + postTitle + preBody);
        $.ajax({
            type: "POST",
            url: "php/inc-createassessmentupdatetable.php",
            data: {
                data: obj.ID       
            },
            success: function (data) {
                html += (data);
                html += (postBody);
                $('#terms').append(html);
                $('.messages').show(); 
            }
        });

        
        
    }

    $('#submit').click(function (){
        $.ajax({
            type: "POST",
            url: "../php/inc-createassessment-saveAssessment.php",
            data: {
                catID: $('#categorySelect').val(),
                classID: 1,
                startDate: Date.now()
            },
            success: function(response) {
                console.log(response);
                getTerms($('#categorySelect').val(), response);
            }
        });
    });

    function loadStudents(classID) {
        $.ajax({
            type: "POST",
            url: "php/inc.create-assessment.php",
            dataType: "json",
            data: {
                classID: classID
            },
            success: function(response) {
                console.log(response);
                $('#studentTable').append('<tbody>');
                let checkbox = '<input type="checkbox" class="form-check-input checkbox">';
                for(let i = 0; i < response.length; i++){
                    $('#studentTable').append('<tr><td value="'+ response[i].ID + '">' + checkbox + '</td><td>' + response[i].name + '</td></tr>');
                }
                $('#studentTable').append('</tbody>');

            }
        });
    }

    function getTerms(catID, formResponse) {
        let array = [];
        console.log(catID);
        $.ajax({
            type: "POST",
            url: "../php/inc-createassessment-saveAssessment.php",
            dataType: "json",
            data: {
                catID: catID
            },
            success: function(response) {
                console.log(response);
                array = pickTerms(response, 20, 8);
                console.log(array);
                submit(array, formResponse);
            },
            error: function(response) {
                console.log(response);
            }
        });

        
    }

    function submit(array, formResponse) {
        let assessArray = [];
        let count = 0;
        console.log(array);
        for(let i = 0; i < array[0].length; i++) {
            assessArray[count] = {termID: array[0][i].ID, assessmentID: formResponse, isMatch: 1};
            count++;
        }
        for(let i = 0; i < array[1].length; i++) {
            assessArray[count] = {termID: array[0][i].ID, assessmentID: formResponse, isMatch: 0};
            count++;
        }
        console.log(assessArray);
        $.ajax({
            type: "POST",
            url: "../php/inc-createassessment-saveAssessment.php",
            data: {
                assessData: JSON.stringify(assessArray)
            },
            success: function(response) {
                console.log(response);
            }
        })
    }

    // This should be used when creating the assessment to randomize which words are tested.
    // This function randomizes the set of terms, then chooses the first n terms for matching,
    // with remaining terms used for the extra definitions.
    // Thanks to Laurens Holst for this implementation of Durstenfeld shuffle
    // Link: https://stackoverflow.com/questions/2450954/how-to-randomize-shuffle-a-javascript-array
    function pickTerms(array, numTerms, numDefs) {
        // Rearranges terms in a random order
        for (let i = array.length - 1; i > 0; i--) {
            let j = Math.floor(Math.random() * (i + 1));
            [array[i], array[j]] = [array[j], array[i]];
        }

        let terms = [];
        let result = [];
        let extra = [];
        // Add the terms for matching to a new array terms
        for(let i = 0; i < numTerms; i++) {
            terms[i] = array[i];
        }
        let count = 0;
        // Add the leftover terms to a new "extra" array for remaining definitions
        for(let i = numTerms; i < (numDefs + numTerms); i++) {
            extra[count] = array[i];
            count++;
        }
        // Store these arrays in an array for returning
        result[0] = terms;
        result[1] = extra;
        console.log(result);
        return result;
    }
});