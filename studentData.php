<?php
// Start the session
session_start();
if((!isset($_SESSION['type'])) && $_SESSION['type'] != 1){
   echo "Unauthorized access";
  header("Location:index.php"); //to redirect back to "index.php" after logging out
  exit();
} 
$_SESSION['currStudentEmail'] = $_POST["email"];
?>
<!DOCTYPE html>
<html>



<head>

  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Student Data</title>
  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M"
    crossorigin="anonymous">

  <!-- Custom styles for this template -->
  <link rel="stylesheet" href="/css/cssAddWords.css" />
</head>

<body>

<!-- Add Nav Bar part-->
<?php 
if($_SESSION['type'] == 1){
include  "teacher-topbar-header.php"; 
include  "teacher-sidebar-header.php";
}else if( $_SESSION['type'] == 0){
  include  "topbar-header.php"; 
  include  "sidebar-header.php";
}
?>




<!-- Start main html-->
<main class="col-sm-9  ml-sm-auto ccol-md-10 pt-3 " role="main">
<div class="card mb-6">
        <div class="card-header boardModule">
        <h5 class="card-title text-white" id="studentName"></h5></div>
        <div class="card-body">
<div class="table-responsive">
            <table class="table table-bordered" id="dataTableStudentAssess" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Assessment Name</th>
                  <th>Category</th>
                  <th>Score</th>
                </tr>
              </thead>
              <tbody>
                </tbody>
            </table>
            <br>
          </div>
          </div>
          </div>
        <div id="chart">
        <canvas id="studentChart" width="300" height="200"></canvas>
        </div>
      </main>
    
  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS Code from bootstrap site -->
  <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
    crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
    crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1"
    crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/qunit/qunit-2.4.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js"></script>
  <script src="/tests/tests.js"></script>
  <script>
    if (typeof ($.fn.modal) === 'undefined') {
      document.write('<script src="/js/bootstrap.min.js"><\/script>')
    }
  </script>
  <script>
    window.jQuery || document.write('<script src="/js/jquery-3.2.1.min.js"><\/script>');
  </script>
  <div id="bootstrapCssTest" class="hidden"></div>
  <script>
    $(function () {
      if ($('#bootstrapCssTest').is(':visible')) {
        $("head").prepend('<link rel="stylesheet" href="/css/bootstrap.min.css">');
      }
    });
  </script>
  <script src="/js/studentData.js"></script>
</body>

</html>