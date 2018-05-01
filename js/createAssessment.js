$('#alertSuccess').hide();

$(document).ready(function(){
    
    $('.messages').hide();
    $('#terms').hide();
    $('#studentAssignments').hide();
    
    cats = [];
    checkedNum = 0;
    matchedNum = 0;
    submitSuccess = 0;

    $('#class').change(function() {
        loadStudents(document.getElementById('#class').value());
    });
    
    $('#selfSelect').click(function (){
        $('#terms').show();
    });

    $('#random').click(function() {
        $('#terms').hide();
    });  

    // function to delete assessments. Tries to delete, and will only delete assessments that do not have data. Will fail otherwise.
    $(document).on("click", "#delete", function() {
        let ID = $(this).val();
        $.ajax({
            type: "POST",
            url: "php/inc.create-assessment.php",
            dataType: "json",
            data: {
                deleteID: ID
            },
            success: function(response){
                //console.log(response);
                loadAssessments();
                showAlert("Successfully deleted assessment.", "alert-success small");
            },
            error: function(response){
                //console.log("ERR: " + response);
                showAlert("Error deleting assessment! Data already exists.", "alert-danger small");
            }
        })
    });

    $(document).on("click", "#selectAll", function() {
        let table = $(this).closest('table');
        $('td input:checkbox', table).prop('checked', this.checked);
    });
    
    $(document).on('change', '.check', function() {
        let checkedNum = $(this).closest('table').find('td input[type="checkbox"]:checked').length;
        if(checkedNum < 21)
            $(this).closest('.card').find('span').text(checkedNum+"/20");
        else {
            $(this).prop('checked', false);
        }
        //console.log(checkedNum + "/20");
        //console.log(checkedNum);
    });

    $(document).on("change", "select",function(){
        var catID = $(this).val();
       // console.log(catID);
        $('#terms').empty();
        $.ajax({
            type: "POST",
            url: "php/inc.create-assessment.php",
            dataType: "json",
            data: {
                catID: catID
            },
            success: function(response) {
               // console.log(response);
                cats = response;
                let i = 0;

                showTermsHelper(i, response);
            }
        });
    });

    // function for updating the dates of assessments (allows for start and end date to be edited)
    $("#assess_table").on('change', function(e) {
       // console.log(e.target.parentNode.parentNode);
        let ID = e.target.parentNode.parentNode.firstChild.innerHTML;
        //console.log("ID: " + ID + "\nVAL: " + e.target.value + "\nTYPE: " + e.target.id);
        $.ajax({
            type: "POST",
            url: "php/inc.create-assessment.php",
            data: {
                type: e.target.id,
                value: e.target.value,
                ID: ID
            },
            success: function(response) {
                showAlert("Successfully updated date!", "alert-success small");
            }
        });
    });
    // this calls funtion loadCategory for top category dropdown on doculment load
                loadCategorySelect();
    //this calls loading existing assessments to open
                loadAssessments();
    function showTermsHelper(i, array) {
        if(i < array.length) {
            setTimeout(function() {
                showTerms(array[i]);
                showTermsHelper(i+1, array);
            }, 10);
        }
        else return;
    }
    
    
    
        function loadCategorySelect(){
              $.ajax({
                type: "POST",
                url: "php/inc-createassessment-getcategories.php",
                data: {
                    
                },
                    success: function (response) {
                    $("#categorychoice").append(response);
                    
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
            //console.log("Self selection has been checked...");
        }    
        else {
            //console.log("Self select wasn't checked on submit!");
            //console.log($('#startDate').val());
            if($('#startDate').val() !== ""){
                $.ajax({
                    type: "POST",
                    url: "php/inc-createassessment-saveAssessment.php",
                    data: {
                        catID: $('#categorySelect').val(),
                        startDate: $('#startDate').val()
                    },
                    success: function(response) {
                        console.log(response);
                        if(typeof response !== "number") {
                            //console.log("Error creating assessment because not a number (bad assessment creation)");
                           // showAlert("Error creating assessment.", "alert-danger");
                        }
                            
                        asessmentID = response;
                        for(let i = 0; i < cats.length; i++) {
                            getTerms(cats[i].ID, response);
                        }
                    }
                });
            }
            else {
               // console.log("Error creating assessment because start date was empty.");
                showAlert("Error creating assessment, start date is not set.", "alert-danger small");
            }
            
        }
        
    });

    function processChecks(){
        let terms = [];
        let extra = [];
        let assessmentID = 0;
        let numChecked = 0;
        $.ajax({
            type: "POST",
            url: "php/inc-createassessment-saveAssessment.php",
            data: {
                catID: $('#categorySelect').val(),
                startDate: $('#startDate').val()
            },
            success: function(response) {
                //console.log(response);
                $('#terms tr').each(function() {

                    let termID = $(this).find('td input[type="checkbox"]').val();
                    assessmentID = response;
                    if($(this).find('td input[type="checkbox"]').is(':checked')) {
                        //console.log('Item is checked: ' + termID);
                        terms.push({termID: termID, assessmentID: assessmentID, isMatch: 1});
                        numChecked++;
                    }
                    else {
                        //console.log('pushed term extra is now (+1): ' + extra.length);
                        let ID = $(this).closest('.card').find('.card-header').attr('id');
                        ID = ID.substr(ID.length - 1, ID.length);
                        //console.log(ID);
                        if(typeof extra[ID] == 'undefined')
                            extra[ID] = [];
                        if(typeof termID !== 'undefined')
                            extra[ID].push({termID: termID, assessmentID: assessmentID, isMatch: 0});
                    }
                    
                });
                //console.log(terms);
                //console.log(extra);
                for(let i = 0; i < extra.length; i++) {
                    if(typeof extra[i] !== 'undefined')
                        extra[i] = randomize(extra[i]);
                }
                
                for(let i = 0; i < extra.length; i++)
                {
                    if(typeof extra[i] !== 'undefined') {
                        for(let j = 0; j < 8; j++) {
                            terms.push(extra[i][j]);
                        }
                    }
                    
                }
    
                if(numChecked === (cats.length * 20)) {
                    $.ajax({
                        type: "POST",
                        url: "php/inc-createassessment-saveAssessment.php",
                        data: {
                            assessData: JSON.stringify(terms)
                        },
                        success: function(response) {
                           // console.log(response);
                            if(response === "Success") {
                               // console.log("Success from processChecks");
                                showAlert("Successfully created assessment.", "alert-success small");
                            }
                        }
                    });
                    submitStudents(assessmentID);
                }
                else {
                    //console.log("Fail from processChecks");
                    showAlert("Error creating assessment. Not enough terms were selected.", "alert-danger small");
                }
                
            }
        });        

    }
    // this function is no longer used with the new assignment page.
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
               // console.log(response);
            }
        });
    }
    // this function is no longer used with the new assignment page.
    function loadStudents(classID) {
        $.ajax({
            type: "POST",
            url: "php/inc.create-assessment.php",
            dataType: "json",
            data: {
                classID: classID
            },
            success: function(response) {
               // console.log(response);
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
       // console.log(catID);
        $.ajax({
            type: "POST",
            url: "php/inc-createassessment-saveAssessment.php",
            dataType: "json",
            data: {
                catID: catID
            },
            success: function(response) {
                //console.log(response);
                array = pickTerms(response, 20, 8);
                //console.log(array);
                submit(array, formResponse)
            },
            error: function(response) {
                //console.log(response);
                //console.log("Error creating assessment in getTerms.");
                showAlert("Error creating assessment.", "alert-danger small");
            }
        });

        
    }

    function submit(array, formResponse) {
        let assessArray = [];
        let count = 0;
       // console.log(array);
        if(typeof array[1][7] == 'undefined'){
          //  console.log("There were less than 28 terms found");
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
           // console.log(assessArray);
            $.ajax({
                type: "POST",
                url: "php/inc-createassessment-saveAssessment.php",
                data: {
                    assessData: JSON.stringify(assessArray)
                },
                success: function(response) {
                  //  console.log(response);
                    if(response === "Success") {
                        loadAssessments();
                        submitSuccess++;
                        if(submitSuccess == cats.length) {
                           // console.log("Success from submit function");
                            showAlert("Successfully created assessment.", "alert-success small");
                            submitSuccess = 0;
                        }
                            
                    }
                    else {
                       // console.log("Error in submit function");
                        showAlert("Error creating assessment.", "alert-danger small");
                    }
                }
            });
        }
        
    }

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
        //console.log(result);
        return result;
    }

    function showAlert(message,alertType) {

        $('#alertPlaceholder').append('<div id="alertdiv" class="alert ' +  alertType + '"><a class="close" data-dismiss="alert">×</a><span>'+message+'</span></div>').fadeIn(500);
    
        setTimeout(function() { // this will automatically close the alert and remove this if the users doesnt close it in 5 secs
    
    
          $("#alertdiv").remove();
    
        }, 5000);
      }
});

function randomize(array) {
    // Rearranges terms in a random order
    if(typeof array[0] == 'undefined') {
        return array;
    }
    for (let i = array.length - 1; i > 0; i--) {
        let j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
    }

    return array;
}