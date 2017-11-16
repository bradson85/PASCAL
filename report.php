<?php
    $page_title = "Report";
    $css_path = "css/main.css";
    include('php/inc.header.php');
?>

    <body>

    <?php 
        include('topbar-header.php');
        include('sidebar-header.php'); 
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
            
            <table class="table table-striped">
                <!-- Generate table based on class (administrators will have additional options -->

            </table>
        </div>


        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
        <script src="js/report.js"></script>
    </body>
</html>