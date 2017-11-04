$(document).ready(function() {
    $('.canDrag').draggable({revert: "invalid"});
    $('.canDrop').droppable();

    console.log(document.getElementById('assessmentID').innerText);

    $.ajax({
        type: "POST",
        url: "php/inc.assessment.php",
        dataType: "json",
        data: {
            
            id: document.getElementById('assessmentID').innerText,
            student: document.getElementById('student').innerText
        },
        success: function(response){
            console.log(response);
            // console.log(response[0]);
            // result = pickTerms(response, 5, 6);
            // displayItems(result[0], result[1]);
        },
        error: function(){
            console.log("no work");
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

    function displayItems(terms, defs) {
        console.log(terms[0]['name']);
        for(let i = 0; i < terms.length; i++) {
            termID = 'term' + (i+1);
            console.log(termID);
            document.getElementById(termID).innerHTML = terms[i]['name'];
        }

        for(let i = 0; i < defs.length; i++) {
            
        }
    }

});