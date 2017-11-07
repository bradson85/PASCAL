$(document).ready(function() {
    $('.canDrag').draggable({revert: "invalid"});
    $('.canDrop').droppable();
    terms = [[]];
    defs = [[]];
    correct = [];
    currLevel = 0;
    minLevel = 0;
    maxLevel = 0;
    page = 1;
    assessmentID = document.getElementById('assessmentID').innerText;
    studentID = document.getElementById('student').innerText;

    $('#next').click(function() {
        console.log('Next was clicked');
        page++;
        if(checkResults() == 5){
            if(currLevel < maxLevel)
                currLevel++;
        }
        else if(checkResults() == 0){
            if(currLevel > minLevel )
                currLevel--;
        }
        displayItems(5*page, 7*page);
    });
    
    $.ajax({
        type: "POST",
        url: "php/inc.assessment.php",
        dataType: "json",
        data: {
            
            id: assessmentID,
            student: studentID
        },
        success: function(response){
            console.log(response);
            console.log(response[0]['name']);
            getTerms(response, 0);
            console.log(terms);
            terms = randomize(terms);
            defs = getDefs(response, 0);
            defs = randomize(defs);
            displayItems(terms, defs);
        },
        error: function(){
            console.log("Error!");
        }
    });
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
        for(let i = numTerms; i < numTerms+numDefs; i++) {
            extra[i] = array[i];
        }
        // Store these arrays in an array for returning
        result[0] = terms;
        result[1] = extra;
        return result;
    }

    function getTerms(array, start) {
        for(let i = minLevel; i < maxLevel; i++){
            for(let j = 0; j < array.length; j++){
                if(array[j]['level'] === i)
                    terms[i][j] = array[j]['name'];
            }
        }
    }

    function getDefs(array, start) {
        for(let i = minLevel; i < maxLevel; i++){
            for(let j = 0; j < array.length; j++){
                if(array[j]['level'] === i)
                    defs[i][j] = array[j]['definition'];
            }
        }
    }

    function randomize(array) {
        console.log(array);
        // Rearranges terms in a random order
        for (let i = array.length - 1; i > 0; i--) {
            let j = Math.floor(Math.random() * (i + 1));
            [array[i], array[j]] = [array[j], array[i]];
        }

        return array;
    }
    // Takes an array of terms and an array of definitions and displays them
    // Currently it will only accept the exact number of terms and definitions
    // specified in the project specifcation, though that could be changed.
    function displayItems(terms, defs) {
        console.log(terms[0]['name']);
        for(let i = 0; i < terms.length; i++) {
            termID = 'term' + (i+1);
            console.log(termID);
            document.getElementById(termID).innerHTML = terms[currLevel][i];
        }

        for(let i = 0; i < defs.length; i++) {
            defID = 'def' + (i+1);
            document.getElementById(defID).innerHTML = defs[currLevel][i];
        }
    }

    function checkResults() {

    }

});