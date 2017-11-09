$(document).ready(function() {
    $('.canDrag').draggable({revert: "invalid"});
    $('.canDrop').droppable();
    terms = [];
    defs = [];
    correct = [];
    currLevel = 1;
    minLevel = 1;
    maxLevel = 3;
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
        displayItems(page);
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
            getTerms(response);
            console.log(terms);
            //randomize(terms);
            getDefs(response);
            //randomize(defs);
            displayItems(page);
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

    function getTerms(array) {
        let curr = 0;
        for(let i = minLevel; i <= maxLevel; i++){
            terms[i] = [];
            curr = 0;
            for(let j = 0; j < array.length; j++){
                if(parseInt(array[j]['level']) === i){
                    terms[i][j] = array[j]['name'];
                    curr++;
                }
                    
            }
        }
    }

    function getDefs(array) {
        let curr = 0;
        for(let i = minLevel; i <= maxLevel; i++){
            defs[i] = [];
            curr = 0;
            for(let j = 0; j < array.length; j++){
                if(parseInt(array[j]['level']) === i){
                    defs[i][curr] = array[j]['definition'];
                    curr++;
                }
                    
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
    function displayItems(n) {
        console.log(terms[1]);
        for(let i = 5*(n-1); i < 5*n; i++) {
            termID = 'term' + ((i+1) - (5 * (n - 1)));
            console.log(termID);
            document.getElementById(termID).innerHTML = terms[currLevel][i];
            defID = 'def' + ((i+1) - (5 * (n - 1)));
            console.log(defID);
            document.getElementById(defID).innerHTML = defs[currLevel][i];
        }

        document.getElementById('def6').innerHTML = defs[currLevel][20 + n - 1];
        document.getElementById('def7').innerHTML = defs[currLevel][20 + n];
    }

    function checkResults() {

    }

});