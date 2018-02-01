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
            assessArray[count] = {termID: array[0][i].ID, assesssmentID: formResponse, isMatch: 0};
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
        // Add the leftover terms to a new "extra" array for remaining definitions
        for(let i = 0; i < numDefs; i++) {
            extra[i] = array[i];
        }
        // Store these arrays in an array for returning
        result[0] = terms;
        result[1] = extra;
        console.log(result);
        return result;
    }
});