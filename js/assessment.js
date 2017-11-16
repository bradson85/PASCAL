$(document).ready(function() {
    $('.canDrag').draggable({revert: "invalid"});
    $('.canDrop').droppable();

    terms = [];
    defs = [];
    results = [];
    correct = [];

    // Dynamic system is not fully implemented, but it will be easy to implement soon.
    // It will be finished by the end of the sprint. All that needs to be done is get the starting level,
    // and calculate the min level and max level based on the starting level.
    currLevel = 1;
    minLevel = 1;
    maxLevel = 1;
    page = 1;
    assessmentID = document.getElementById('assessmentID').innerText;
    studentID = document.getElementById('student').innerText;

    // initializes the results array
    setResults();
    // set up droppable areas that save the term that is dropped when an item is dropped
    // There are a couple known bugs with the drag and drop system.
    // 1. Dragging an element to another area does not remove the previous set element
    // 2. Multiple items are able to be dragged into a single drop location
    // 3. Next can be clicked without placing all of the terms.
    $('#drop1').droppable({
        drop: function(event, ui) {
            results[1].dropID = 'drop1';
            results[1].termID = ui.draggable.find('span').attr('id');
            console.log(results);
        }
    });

    $('#drop2').droppable({
        drop: function(event, ui) {
            results[2].dropID = 'drop2';
            results[2].termID = ui.draggable.find('span').attr('id');
            console.log(results);
        }
    });

    $('#drop3').droppable({
        drop: function(event, ui) {
            results[3].dropID = 'drop3';
            results[3].termID = ui.draggable.find('span').attr('id');
            console.log(results);
        }
    });

    $('#drop4').droppable({
        drop: function(event, ui) {
            results[4].dropID = 'drop4';
            results[4].termID = ui.draggable.find('span').attr('id');
            console.log(results);
        }
    });

    $('#drop5').droppable({
        drop: function(event, ui) {
            results[5].dropID = 'drop5';
            results[5].termID = ui.draggable.find('span').attr('id');
            console.log(results);
        }
    });

    $('#drop6').droppable({
        drop: function(event, ui) {
            results[6].dropID = 'drop6';
            results[6].termID = ui.draggable.find('span').attr('id');
            console.log(results);
        }
    });

    $('#drop7').droppable({
        drop: function(event, ui) {
            results[7].dropID = 'drop7';
            results[7].termID = ui.draggable.find('span').attr('id');
            console.log(results);
        }
    });


    $('#next').click(function() {
        console.log('Next was clicked');
        page++;
        let correct = checkResults();

        // If all correct or none correct, switch level
        // if the current level is not at max level or min level.
        if(correct == 5){
            if(currLevel < maxLevel)
                currLevel++;
        }
        else if(correct == 0){
            if(currLevel > minLevel )
                currLevel--;
        }
        // If the page is less than 5, reset the draggable elements and the results array.
        // otherwise, the assessment is over, the results are displayed
        if(page < 5){
            displayItems(page);
            $('.canDrag').css({'top':'', 'left':''});
            setResults();
        }
        else {
            // Go to results page
            showResults();
            submitResults();
        }
        
    });
    
    $.ajax({
        type: "POST",
        url: "php/inc.assessment.php",
        dataType: "json",
        data: { 
            id: assessmentID
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

    function setResults() {
        for(let i = 1; i <= 7; i++){
            results[i] = {dropID: '', termID: ''}
        }
    }
    // set terms in the terms array for each level
    function getTerms(array) {
        let curr = 0;
        for(let i = minLevel; i <= maxLevel; i++){
            terms[i] = [];
            curr = 0;
            for(let j = 0; j < array.length; j++){
                if(parseInt(array[j]['level']) === i){
                    terms[i][curr] = {name: '', id: ''};
                    terms[i][curr]['name'] = array[j]['name'];
                    terms[i][curr]['id'] = array[j]['ID'];
                    curr++;
                }
                    
            }
        }
    }
    // set definitions in the definitions array for each level
    function getDefs(array) {
        let curr = 0;
        for(let i = minLevel; i <= maxLevel; i++){
            defs[i] = [];
            curr = 0;
            for(let j = 0; j < array.length; j++){
                if(parseInt(array[j]['level']) === i){
                    defs[i][curr] = {name: '', id: ''};
                    defs[i][curr]['name'] = array[j]['definition'];
                    defs[i][curr]['id'] = array[j]['ID'];
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
        let randDefs = [];
        for(let i = 5*(n-1); i < 5*n; i++) {
            termID = 'term' + ((i+1) - (5 * (n - 1)));
            console.log(termID);
            document.getElementById(termID).innerHTML = terms[currLevel][i]['name'];

            randDefs[(i) - (5 * (n - 1))] = defs[currLevel][i]['name'];
        }

        randDefs[5] = defs[currLevel][20 + ((n*2) - 2)]['name'];
        randDefs[6] = defs[currLevel][20 + (n*2) - 1]['name'];
        randDefs = randomize(randDefs);

        for(let i = 0; i < 7; i++) {
            defID = 'def' + (i+1);
            console.log(defID);
            document.getElementById(defID).innerHTML = randDefs[i];
        }

        
    }
    // Checks the results array and adds each item to the correct array
    function checkResults() {
        let numCorrect = 0;

        for(let i = 1; i <= 7; i++) {
            if(results[i].termID !== ""){
                // find the term in the array that matches the term placed in the drop area
                let termName = document.getElementById(results[i].termID).innerHTML;
                let filterTerm = terms[currLevel].filter(function (e) {
                    return (e.name === termName);
                });
                // find the definition in the array that matches the term next to drop area
                // this is needed because the definitions are randomized.
                let defName = document.getElementById('def' + i).innerHTML;
                let filterDef = defs[currLevel].filter(function (e) {
                    return e.name === defName;
                });
                if(filterTerm[0].id === filterDef[0].id) {
                    console.log("Correct!");
                    numCorrect++;
                    correct.push({id: filterTerm[0].id, correct: 1});
                }
                else {
                    correct.push({id: filterTerm[0].id, correct: 0});
                }
                    
            }
            
        }
        console.log(correct);
        console.log("Got " + numCorrect + " correct");
        return numCorrect;
    }
    
    function showResults(){
        // hide the assessment elements
        $('.card').hide();
        $('.btn').hide();

        let numCorrect = 0;
        // Calculate the number of correct terms
        for(let i = 0; i < correct.length; i++) {
            if(correct[i].correct === 1)
                numCorrect++;
        }   

        $('.container').append("<h2 class='text-center'>Chart placeholder. You got " + numCorrect + " correct.</h2>");
    }

    function submitResults(){
        console.log(correct);
        $.ajax({
            type: "POST",
            url: "php/inc.assessment.php",
            data: {
                student: studentID,
                id: assessmentID,
                results: JSON.stringify(correct)
            },
            success: function(response) {
                console.log(response);
            }
        });
    }

});