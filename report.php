<?php
session_start();

if(!isset($_SESSION['type']) || $_SESSION['type'] == 2){

    echo "Unauthorized access";
    exit();
}else{
    $page_title = "Report";
    $css_path = "css/main.css";
}
?>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Assign An Assessment</title>
  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

  <!-- Custom styles for this template -->
  <link rel="stylesheet" href="/css/cssAddWords.css" />
</head>

    <body>

    <?php 
    if($_SESSION['type'] == 1){
        include('teacher-topbar-header.php');
        include('teacher-sidebar-header.php'); 
    }else if($_SESSION['type'] == 0){
        include('topbar-header.php');
        include('sidebar-header.php'); 
    }
    ?>
      <main class="col-sm-9  ml-sm-auto ccol-md-10 pt-3 " role="main">
        <div class="container report">
            <div class="row">
                <div class="col-sm-6">
                    School:
                    <select class="form-control" id="school"></select>
                </div>
                <div class="col-sm-6">
                    Class: <select class="form-control" id="class"></select>
                </div>
            </div>

             <div class="row">
                <div class="col-md-10">
                    FileFormat:
                    <select class="form-control" id="format"></select>
                </div>
            </div>

            <div class="row">
            Report:
                <div class="col-md-10"> 
                    Show:
                    <div class="form-check">
                         <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" value="option1" checked>
                             <label class="form-check-label" for="exampleRadios1">
                             Avg Score
                                </label>
                                    </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2" value="option2">
                                <label class="form-check-label" for="exampleRadios2">
                                  All Scores
                                    </label>
                    </div>
                    <div class="form-check">
                            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios3" value="option3" disabled>
                                 <label class="form-check-label" for="exampleRadios3">
                                 Last Grade Level Completed
                                        </label>
                                    </div>
                                    <div class="form-check">
                            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios3" value="option3" disabled>
                                 <label class="form-check-label" for="exampleRadios3">
                                 All Grade Levels Per Assessment
                                        </label>
                                    </div>
            </div>


            <table class="table table-striped table-responsive">
                <!-- Generate table based on class (administrators will have additional options -->
                <thead id="results-head">
                </thead>
                <tbody id="results">
                </tbody>
            </table>
        </div>
</main>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
        <script src="js/report.js"></script>
    </body>
</html>