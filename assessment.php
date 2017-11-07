<?php
    $page_title = "Assessment";
    $css_path = "css/main.css";

    include ('php/inc.header.php');
    include ('php/inc.assessment.php');

    $id = $_GET['id'];
    $student = $_GET['student'];
?>

    <body>
        <span><p hidden name="assessmentID" id="assessmentID" value="<?php echo $id; ?>"><?php echo "$id";?></p><p hidden name="student" id="student" value= "<?php echo $student; ?>"><?php echo "$student";?></p></span>
        <div class="container">
            <div class="row">
                <div class="left col-sm-4">
                    <div class="card canDrag">
                        <div class="card-body">
                            <span id="term1">Term 1</span>
                        </div>
                    </div>
                    <div class="card canDrag">
                        <div class="card-body">
                            <span id="term2">Term 2</span>
                        </div>
                    </div>
                    <div class="card canDrag">
                        <div class="card-body">
                            <span id="term3">Term 3</span>
                        </div>
                    </div>
                    <div class="card canDrag">
                        <div class="card-body">
                            <span id="term4">Term 4</span>
                        </div>
                    </div>
                    <div class="card canDrag">
                        <div class="card-body">
                            <span id="term5">Term 5</span>
                        </div>
                    </div>
                </div>

                <div class="right col-sm-8">
                    <div class="row">
                        <div class="card canDrop">
                            <div class="card-body">
                                
                            </div>
                        </div>
                        <div class="card definition">
                            <div class="card-body">
                                <span id="def1">Definition 1</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card canDrop">
                            <div class="card-body">
                                
                            </div>
                        </div>
                        <div class="card definition">
                            <div class="card-body">
                                <span id="def2">Definition 2</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="card canDrop">
                            <div class="card-body">
                                
                            </div>
                        </div>
                        <div class="card definition">
                            <div class="card-body">
                                <span id="def3">Definition 3</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card canDrop">
                            <div class="card-body">
                                
                            </div>
                        </div>
                        <div class="card definition">
                            <div class="card-body">
                                <span id="def4">Definition 4</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card canDrop">
                            <div class="card-body">
                                
                            </div>
                        </div>
                        <div class="card definition">
                            <div class="card-body">
                                <span id="def5">Definition 5</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card canDrop">
                            <div class="card-body">
                                
                            </div>
                        </div>
                        <div class="card definition">
                            <div class="card-body">
                                <span id="def6">Definition 6</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card canDrop">
                            <div class="card-body">
                                
                            </div>
                        </div>
                        <div class="card definition">
                            <div class="card-body">
                                <span id="def7">Definition 7</span>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary" id="next">Next Page</button>
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