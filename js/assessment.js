$(document).ready(function() {
    $('.canDrag').draggable({revert: "invalid", snap: ".canDrop", snapMode: "inner"});
    $('.canDrop').droppable();
    $('.countdown').hide();

    //var msg = new SpeechSynthesisUtterance('Test message :)');
    //speechSynthesis.speak(msg);

    // Hide timer (not visible)
    // San serif font ( Arial 16pt)
    // Button color and clickability should change when all terms have been matched

    $('#directions').modal('show');

    // Set up global variables
    terms = [];
    defs = [];
    results = [];
    correct = [];
    placement = [];
    page = 1;
    termCount = 0;
    requiredTerms = 5;
    assessmentID = document.getElementById('assessmentID').innerText;
    studentID = document.getElementById('student').innerText;
    currLevel = getLevel();
    minLevel = 0;
    maxLevel = 0;

    // initializes the results array
    setResults();
    
    // Set up droppable areas that save the term that is dropped when an item is dropped
    // TODO: Make only one card able to be dropped in a drop location at a time
    $('#drop1').droppable({
        drop: function(event, ui) {
            checkDrop(1, ui.draggable.find('span').attr('id'));
            console.log(results);
        }
    });
    $('#drop2').droppable({
        drop: function(event, ui) {
            checkDrop(2, ui.draggable.find('span').attr('id'));
        }
    });
    $('#drop3').droppable({
        drop: function(event, ui) {
            checkDrop(3, ui.draggable.find('span').attr('id'));
        }
    });
    $('#drop4').droppable({
        drop: function(event, ui) {
            checkDrop(4, ui.draggable.find('span').attr('id'));
        }
    });
    $('#drop5').droppable({
        drop: function(event, ui) {
            checkDrop(5, ui.draggable.find('span').attr('id'));
        }
    });
    $('#drop6').droppable({
        drop: function(event, ui) {
            checkDrop(6, ui.draggable.find('span').attr('id'));
        }
    });
    $('#drop7').droppable({
        drop: function(event, ui) {
            checkDrop(7, ui.draggable.find('span').attr('id'));
        }
    });

    $('#closeDirections').click(function() {
        timer();
    });


    $('#next').click(function() {
        console.log("Number of terms: " + termCount);
        if(termCount < requiredTerms) {
            //error message 
            //return;
        }
        termCount = 0;
        page++;
        let correct = checkResults();
        console.log(terms[currLevel - 1]);
        // If all correct or none correct, switch level
        // if the current level is not at max level or min level.
        // Note: May change this to be configurable based on feedback.
        if(correct === 5){
            if(currLevel < maxLevel && terms[currLevel + 1].length > 0)
                currLevel++;
        }
        else if(correct === 0){
            if(currLevel > minLevel && terms[currLevel - 1].length > 0)
                currLevel--;
        }
        // If the page is less than 5, reset the draggable elements and the results array.
        // otherwise, the assessment is over, the results are displayed
        if(page < 5){
            displayItems(page);
            $('.canDrag').css({'top':'', 'left':''});
            setResults();
            $('#next').attr('disabled', true);
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
            console.log("curr level: " + currLevel); 
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

    function count(num) {
        termCount += num;
        if(termCount === requiredTerms) {
            // update button color and clickability
            console.log("I should update button now.");
            $('#next').attr('disabled', false);
        }

    }

    function timer() {
        // Timer should be hidden in final release.
        // $('.countdown').show();
        // Code used from: https://stackoverflow.com/questions/41035992/jquery-countdown-timer-for-minutes-and-seconds
        // Original author: AJ
        var timer2 = "10:01";
        var interval = setInterval(function() {
            var timer = timer2.split(':');
            //by parsing integer, I avoid all extra string processing
            var minutes = parseInt(timer[0], 10);
            var seconds = parseInt(timer[1], 10);
            --seconds;
            minutes = (seconds < 0) ? --minutes : minutes;
            if (minutes < 1 && seconds == 0) {
                clearInterval(interval);
                // end the assessment if time limit is reached
            }
            seconds = (seconds < 0) ? 59 : seconds;
            seconds = (seconds < 10) ? '0' + seconds : seconds;
            //minutes = (minutes < 10) ?  minutes : minutes;
            $('.countdown').html(minutes + ':' + seconds);
            timer2 = minutes + ':' + seconds;
        }, 1000);
    }

    function checkDrop(i, tID) {
        let drop = "drop" + i;
        results[i].dropID = drop;
        results[i].termID = tID;
        console.log("substr " + results[i].termID.substring(4,5));
        console.log(placement);
        console.log(results);
        if(placement[results[i].termID.substring(4,5)] !== "" && placement[results[i].termID.substring(4,5)] !== results[i].dropID){
            console.log(results[i].termID.substring(4,5));
            results[placement[results[i].termID.substring(4,5)].substring(4,5)].dropID = "";
            results[placement[results[i].termID.substring(4,5)].substring(4,5)].termID = "";
            placement[results[i].termID.substring(4,5)] = results[i].dropID;
            //count(-1);
        }
        else {
            placement[results[i].termID.substring(4,5)] = results[i].dropID;
            count(1);
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
            placement[i] = "";
        }
    }
    // set terms in the terms array for each level
    function getTerms(array) {
        console.log("in getTerms\n");
        // min level not being calculated properly, need to investigate.
        console.log("min level in getTerms: " + minLevel);
        console.log("But currLevel - 1 is: " + (currLevel - 1));
        let curr = 0;
        for(let i = 0; i < minLevel; i++) {
            terms[i] = [];
        }
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
        console.log(terms);
    }

    function getLevel() {
        let retVal = 0;
        $.ajax({
            type: "POST",
            url: "php/inc.assessment.php",
            dataType: "json",
            data: {
                student: studentID 
            },
            success: function(response) {
                console.log(response);
                currLevel = (parseInt(response[0].gradeLevel));
                minLevel = currLevel - 1;
                maxLevel = currLevel + 1;
            },
            error: function(response) {
                console.log("ERROR!: " + response);
            }
        });
        
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
        console.log(terms);
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
        console.log("rand defs: " + randDefs);
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