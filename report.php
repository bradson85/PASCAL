<?php
session_start();

if(!isset($_SESSION['type']) || $_SESSION['type'] == 2){

    echo "Unauthorized access";
    exit();
} 
?>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Reports</title>
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

    <!--small modal content-->
<div id ="confirm" class="modal fade">
<div class="modal-dialog modal-sm" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Confirm</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <p>Modal body text goes here.</p>
    </div>
    <div class="modal-footer">
      <button type="button" id ="modalclosesmall"class="btn btn-secondary" data-dismiss="modal">OK</button>
    </div>
  </div>
</div>
</div>

<!--response modal content-->
<div id ="sure" class="modal fade">
<div class="modal-dialog modal-lg" role="dialog">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Result</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <p>Modal body text goes here.</p>
    </div>
    <div class="modal-footer">
    <button type="button" id="modalsave" class="btn btn-danger">OverWrite</button>
      <button type="button" id="modalclose "class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>
</div>
      <main class="col-sm-9  ml-sm-auto ccol-md-10 pt-3 " role="main">
        <div class="container report">

             <div class="row">
                <div class="col-md-6">
                    FileFormat:
                    <select class="form-control input-small" id="format">
                         <option value="csv">CSV</option>
                         <option value="pdf" >PDF</option>         
                    </select>
                </div>
            </div>

             <div class="row">
                <div class="col-sm-6">
                   Choosse Word Data or Assessment Data:
                    <select class="form-control" id="datatype"></select>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6"  id ="categoryArea">
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6" id ="schoolArea">
                    School:
                    <select class="form-control" id="school"></select>
                </div>
                </div>
                <div class="row">
                <div class="col-sm-6"  id ="classArea">
                    Class: <select class="form-control" id="class"></select>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6"  id ="studentEntireArea">
                    Individual Student or Entire Class:
                    <select class="form-control" id="student"></select>
                </div>
            </div>

            <div class="row" id="reportCustomiztion">
            <h5><br>Report Column Titles:</h5>
                <div class="col-md-10">
                <div class="form-check" id="secCheckBoxes">
            </div>
                <div class="form-check" id="checkBoxes">
            </div>
            
            </div>
             </div>
            <br>
             <p class="Buttons">
            <button class = " btn btn-primary" id="genReport" disabled>Generate Report</button>
          </p>
        </div>
</main>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
       <script src ="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>
        <script src="js/report.js"></script>
    </body>
</html>