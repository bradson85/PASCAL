<?php
    session_start();
    // $_SESSION['email'] = "student@test.com";
    // $_SESSION['type'] = 2;

    $page_title = "Assessment";
    $css_path = "css/main.css";

    include ('php/inc.header.php');
    include ('php/inc.assessment.php');

    $id = $_GET['id'];
?>

    <body id="assessment">
        <span><p hidden name="assessmentID" id="assessmentID" value="<?php echo $id; ?>"><?php echo "$id";?></p></span>
        
        <div class="container-fluid">
            <div class="card" id="directions">
                <div class="card-body">
                    <h5 class="card-title text-center">Directions</h5>
                    <span>
                        <p>Now you will match some terms and definitions about science. When the next screen appears, please match the words on the left-hand side of the screen with their definitions. Click on the word and drag it to the space next to the correct definition. Please match as many words and definitions as you can. If you want a word read to you – you can click on the speaker next to it.</p>
                        <p>There will always be 5 words and 7 definitions on the screen. So two definitions will not be used. When you match all the words on the screen to their correct definition please click the “next” button at the bottom of the screen.</p>
                        <p>You will match terms and definitions for ten minutes. The computer will let you know when to stop. Just do your best! Ready?</p>
                    </span>
                    <button class="speak"><i class="fas fa-volume-up"></i></button>
                    <!-- <span class="oi oi-volume-high"></span> -->
                </div>
            </div>

            <button class="btn btn-primary" id="startButton">Begin</button>


        <div class="countdown text-right"></div>
            <div class="row">
                <div class="left col-sm-3">
                    <div class="card canDrag quizCard">
                        <div class="card-body align-items-center d-flex">
                            <span id="term1">Term 1</span>
                            <button class="speak"><i class="fas fa-volume-up"></i></button>
                        </div>
                    </div>
                    <div class="card canDrag quizCard">
                        <div class="card-body align-items-center d-flex">
                            <span id="term2">Term 2</span>
                            <button class="speak"><i class="fas fa-volume-up"></i></button>
                        </div>
                    </div>
                    <div class="card canDrag quizCard">
                        <div class="card-body align-items-center d-flex">
                            <span id="term3">Term 3</span>
                            <button class="speak"><i class="fas fa-volume-up"></i></button>
                        </div>
                    </div>
                    <div class="card canDrag quizCard">
                        <div class="card-body align-items-center d-flex">
                            <span id="term4">Term 4</span>
                            <button class="speak"><i class="fas fa-volume-up"></i></button>
                        </div>
                    </div>
                    <div class="card canDrag quizCard">
                        <div class="card-body align-items-center d-flex">
                            <span id="term5">Term 5</span>
                            <button class="speak"><i class="fas fa-volume-up"></i></button>
                        </div>
                    </div>
                </div>

                <div class="right col-sm-9">
                    <div class="row">
                        <div class="card canDrop  quizCard" id="drop1">
                            <div class="card-body">
                                
                            </div>
                        </div>
                        <div class="card definition quizCard">
                            <div class="card-body align-items-center d-flex">
                                <span id="def1">Definition 1</span>
                                <button class="speak white float-right"><i class="fas fa-volume-up"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card canDrop quizCard" id="drop2">
                            <div class="card-body">
                                
                            </div>
                        </div>
                        <div class="card definition quizCard">
                            <div class="card-body align-items-center d-flex">
                                <span id="def2">Definition 2</span>
                                <button class="speak white"><i class="fas fa-volume-up"></i></button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="card canDrop quizCard" id="drop3">
                            <div class="card-body">
                                
                            </div>
                        </div>
                        <div class="card definition quizCard">
                            <div class="card-body align-items-center d-flex">
                                <span id="def3">Definition 3</span>
                                <button class="speak white"><i class="fas fa-volume-up"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card canDrop quizCard" id="drop4">
                            <div class="card-body">
                                
                            </div>
                        </div>
                        <div class="card definition quizCard">
                            <div class="card-body align-items-center d-flex">
                                <span id="def4">Definition 4</span>
                                <button class="speak white"><i class="fas fa-volume-up"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card canDrop quizCard" id="drop5">
                            <div class="card-body">
                                
                            </div>
                        </div>
                        <div class="card definition quizCard">
                            <div class="card-body align-items-center d-flex">
                                <span id="def5">Definition 5</span>
                                <button class="speak white"><i class="fas fa-volume-up"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card canDrop quizCard" id="drop6">
                            <div class="card-body">
                                
                            </div>
                        </div>
                        <div class="card definition quizCard">
                            <div class="card-body align-items-center d-flex">
                                <span id="def6">Definition 6</span>
                                <button class="speak white"><i class="fas fa-volume-up"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card canDrop quizCard" id="drop7">
                            <div class="card-body">
                                
                            </div>
                        </div>
                        <div class="card definition quizCard">
                            <div class="card-body align-items-center d-flex">
                                <span id="def7">Definition 7</span>
                                <button class="speak white"><i class="fas fa-volume-up"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <button class="btn btn-primary" id="next" disabled>Next Page</button>
                    </div>
                </div> <!-- end col-->
            </div>
        </div>


        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src="js/jquery.ui.touch-punch.min.js"></script>
        <script src="js/assessment.js"></script>
    </body>
</html>