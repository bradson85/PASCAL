<?php
session_start();

if(!isset($_SESSION['type'])){

    echo "Unauthorized access";
    exit();
}else{
    $page_title = "Report";
    $css_path = "css/main.css";
    include('php/inc.header.php');
}
?>

    <body>

    <?php 
    if($_SESSION['type'] == 1){
        include('teacher-topbar-header.php');
        include('teacher-sidebar-header.php'); 
    }else if($_SESSION['type'] == 2){
        include('topbar-header.php');
        include('sidebar-header.php'); 
    }
    ?>
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
            <button class="btn btn-primary" id="back"><</button>
            <button class="btn btn-primary" id="forward">></button>
            <table class="table table-striped table-responsive">
                <!-- Generate table based on class (administrators will have additional options -->
                <thead id="results-head">
                </thead>
                <tbody id="results">
                </tbody>
            </table>
        </div>


        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
        <script src="js/report.js"></script>
    </body>
</html>