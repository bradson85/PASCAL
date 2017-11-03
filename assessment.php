<?php
    $page_title = "Assessment";
    $css_path = "css/main.css";

    include ('php/inc.header.php');
    include ('php/inc.assessment.php');
?>

    <body>
        <div class="container">
            <div class="row">
                <div class="left col-sm-4">
                    <div class="card canDrag" id="term1">
                        <div class="card-body">
                            Term 1
                        </div>
                    </div>
                    <div class="card canDrag" id="term2">
                        <div class="card-body">
                            Term 2
                        </div>
                    </div>
                    <div class="card canDrag" id="term3">
                        <div class="card-body">
                            Term 3
                        </div>
                    </div>
                    <div class="card canDrag" id="term4">
                        <div class="card-body">
                            Term 4
                        </div>
                    </div>
                    <div class="card canDrag" id="term5">
                        <div class="card-body">
                            Term 5
                        </div>
                    </div>
                </div>

                <div class="right col-sm-8">
                    <div class="row">
                        <div class="card canDrop" id="def1">
                            <div class="card-body">
                                Definition 1 
                                Definition 1
                                Definition 1
                                Definition 1
                            </div>
                        </div>
                        <div class="card definition" id="def1">
                            <div class="card-body">
                                Definition 1 
                                Definition 1
                                Definition 1
                                Definition 1
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card canDrop" id="def2">
                            <div class="card-body">
                                Definition 2
                            </div>
                        </div>
                        <div class="card definition" id="def2">
                            <div class="card-body">
                                Definition 2
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="card canDrop" id="def2">
                            <div class="card-body">
                                Definition 2
                            </div>
                        </div>
                        <div class="card definition" id="def2">
                            <div class="card-body">
                                Definition 2
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card canDrop" id="def2">
                            <div class="card-body">
                                Definition 2
                            </div>
                        </div>
                        <div class="card definition" id="def2">
                            <div class="card-body">
                                Definition 2
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card canDrop" id="def2">
                            <div class="card-body">
                                Definition 2
                            </div>
                        </div>
                        <div class="card definition" id="def2">
                            <div class="card-body">
                                Definition 2
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card canDrop" id="def2">
                            <div class="card-body">
                                Definition 2
                            </div>
                        </div>
                        <div class="card definition" id="def2">
                            <div class="card-body">
                                Definition 2
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card canDrop" id="def2">
                            <div class="card-body">
                                Definition 2
                            </div>
                        </div>
                        <div class="card definition" id="def2">
                            <div class="card-body">
                                Definition 2
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary">Next Page</button>
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