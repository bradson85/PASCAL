$(document).ready(function(){
    
    $('.messages').hide();
    $('#terms').hide();
    $('#studentAssignments').hide();
    
    //loadStudents(1);
    cats = [];
    checkedNum = 0;
    matchedNum = 0;

    $('#class').change(function() {
        loadStudents(document.getElementById('#class').value());
    });

    $(document).on('hidden.bs.collapse', '#collapse2', function() {
        console.log('i collapsed');
    });
    
    $('#selfSelect').click(function (){
        $('#terms').show();
    });

    $('#random').click(function() {
        $('#terms').hide();
    });

    


    $(document).on("click", "#selectAll", function() {
        let table = $(this).closest('table');
        $('td input:checkbox', table).prop('checked', this.checked);
    });
    
    $(document).on('change', '.check', function() {
        if(this.checked)
            checkedNum++;
        else
            checkedNum--;
        console.log(checkedNum);
    });

    $(document).on("change", "select",function(){
        var catID = $(this).val();
        console.log(catID);
        $('#terms').empty();
        $.ajax({
            type: "POST",
            url: "php/inc.create-assessment.php",
            dataType: "json",
            data: {
                catID: catID
            },
            success: function(response) {
                console.log(response);
                cats = response;
                for(let i = 0; i < response.length; i++) {
                    setTimeout(2500,showTerms(response[i]));
                    
                }
            }
        });
    });
    // this calls funtion loadCategory for top category dropdown on doculment load
                loadCategorySelect();
    //this calls loading existing assessments to open
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
        let preTitle = "<div class=\"card\" style=\"height: 10%\"> <div class=\"card-header\" id=\"heading" + obj.ID + "\"> <h5 class=\"mb-0\"><button class=\"btn btn-link collapsed\" data-toggle=\"collapse\" data-target=\"#collapse" + obj.ID + "\" aria-controls=\"collapse" + obj.ID + "\">";
        let postTitle = "</button> </h5> </div>";
        let preBody = "<div id=\"collapse" + obj.ID + "\" class=\"collapse\" aria-labelledby=\"heading" + obj.ID + "\" data-parent=\"#terms\"> <div class=\"card-body\" style=\"overflow-y: auto\"> <table class=\"table table-striped termTable\"> <thead><tr><th></th><th>Term</th><th>Definition</th></tr></thead><tbody>";
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
        let assessmentID = 0;
        let students = [];
        if($('#selfInput').is(':checked')) {
            processChecks();
            console.log("Self selection has been checked...");
        }    
        else {
            console.log("Self select wasn't checked on submit!");
            console.log($('#startDate').val());
            $.ajax({
                type: "POST",
                url: "php/inc-createassessment-saveAssessment.php",
                data: {
                    catID: $('#categorySelect').val(),
                    classID: 1,
                    startDate: $('#startDate').val()
                },
                success: function(response) {
                    console.log(response);
                    asessmentID = response;
                    for(let i = 0; i < cats.length; i++) {
                        getTerms(cats[i].ID, response);
                    }
                    //submitStudents(response);
                }
            });
        }
        
    });

    function processChecks(){
        let terms = [];
        let assessmentID = 0;
        $.ajax({
            type: "POST",
            url: "php/inc-createassessment-saveAssessment.php",
            data: {
                catID: $('#categorySelect').val(),
                classID: 1,
                startDate: $('#startDate').val()
            },
            success: function(response) {
                console.log(response);
                $('#terms tr').each(function() {
                    $(this).find('td:eq(0) input:checked').each(function () {
                        if($(this).closest('tr').find('td:eq(3) input:checked').length > 0){
                            console.log($(this).val());
                            terms.push({ID: $(this).val(), assessmentID: assessmentID, isMatch: 1});
                        }
                            
                        else {
                            console.log($(this).val());
                            terms.push({ID: $(this).val(), assessmentID: assessmentID, isMatch: 0});
                        }
                    });
                });

                $.ajax({
                    type: "POST",
                    url: "php/inc-createassessment-saveAssessment.php",
                    data: {
                        assessData: JSON.stringify(terms)
                    },
                    success: function(response) {
                        console.log(response);
                    }
                });
                submitStudents(assessmentID);
            }
        });        

    }

    function submitStudents(assessmentID) {
        let students = [];
        $('#studentTable tr').find('td input:checked').each(function() {
            if($(this).val() !== "0")
                students.push({studentID: $(this).val(), assessmentID: assessmentID});
        })

        $.ajax({
            type: "POST",
            url: "php/inc.create-assessment.php",
            data: {
                students: JSON.stringify(students)
            },
            success: function(response) {
                console.log(response);
            }
        });
    }

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
                let checkbox = '<input type="checkbox" class="form-check-input checkbox">';
                $('#studentTable').append('<tbody><tr><td> <input type="checkbox" id="selectAll" class="form-check-input checkbox" value="0"> </td><td>(Check All)</td></tr>');
                for(let i = 0; i < response.length; i++){
                    $('#studentTable').append('<tr><td><input type="checkbox" class="form-check-input checkbox" value="' + response[i].ID + '"></td><td>' + response[i].name + '</td></tr>');
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
            url: "php/inc-createassessment-saveAssessment.php",
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
        if(typeof array[1][7] == 'undefined'){
            console.log("There were less than 28 terms found");
        }     
        else {
            for(let i = 0; i < array[0].length; i++) {
                assessArray[count] = {termID: array[0][i].ID, assessmentID: formResponse, isMatch: 1};
                count++;
            }
            for(let i = 0; i < array[1].length; i++) {
                assessArray[count] = {termID: array[1][i].ID, assessmentID: formResponse, isMatch: 0};
                count++;
            }
            console.log(assessArray);
            $.ajax({
                type: "POST",
                url: "php/inc-createassessment-saveAssessment.php",
                data: {
                    assessData: JSON.stringify(assessArray)
                },
                success: function(response) {
                    console.log(response);
                }
            });
        }
        
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