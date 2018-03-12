
<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html>



<head>

  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Dashboard</title>
  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M"
    crossorigin="anonymous">

  <!-- Custom styles for this template -->
  <link rel="stylesheet" href="/css/cssAddWords.css" />
</head>

<body>

<!-- Add Nav Bar part-->
<?php 

include  __DIR__ ."/../teacher-topbar-header.php"; 
include  __DIR__ ."/../teacher-sidebar-header.php"

?>




<!-- Start main html-->
<main class="col-sm-9  ml-sm-auto ccol-md-10 pt-3 " role="main">
         <!-- Icon Cards examples-->
      <div class="row">
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-white bg-primary o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-comments"></i>
              </div>
              <div id ="completed" class="mr-5">- Completed Assesments</div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-white bg-warning o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-list"></i>
              </div>
              <div id='available' class="mr-5">- Assessments Incomplete</div>
            </div>
          </div>
        </div>
<br>
      
<div class="card mb-10">
        <div class="card-header boardModule">
        <h5 class="card-title text-white">Available Assessments</h5></div>
        <div class="card-body">
        <div id = "topTable" class="table-responsive">
     </div>
        <div class="table-responsive">
        <small class='text-muted'>Click on row to view assessment.</small>
            <table class="table table-bordered" id="assessTableTeach" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Start Date</th>
                  <th>End Date</th>
                  <th>Category and Grade Level</th>
                </tr>
              </thead>
              <tbody>
                </tbody>
            </table>
          </div>
        </div>
      </div>
      <br>
          <!-- Example  Card-->
      <div class="card mb-6">
        <div class="card-header boardModule">
        <h5 class="card-title text-white">Class Table Stats</h5></div>
        <div class="card-body">
        <div id = "topTable" class="table-responsive">
       <table id="sort_table" class="table table-bordered">
         <tbody id = "sort_body"></tbody>
       </table>
     </div>
        <div class="table-responsive">
        <small class='text-muted'>Click on row to view student data.</small>
            <table class="table table-bordered" id="dataTableTeach" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Grade</th>
                  <th>Class</th>
                  <th>Last Taken Assessment</th>
                  <th>Last Assessment Level</th>
                  <th>Date Completed</th>
                </tr>
              </thead>
              <tbody>
                </tbody>
            </table>
          </div>
        </div>
      
  <!-- graph stuff -->
       <div class="row">
        <div class="col-lg-10">
        <div class="card text-center">
    <div class="card-header boardModule">
      <h5 class= "text-white">Student Graphs of Scores</h5>
         </div>
         <br> <span id='graphclassoption'><select>
  <option value="volvo">Homeroom 9AM</option>
  <option value="saab">Science 12:30PM</option>
</select></span><br>
            <span id='graphstudentoption'><select>
            <option value="volvo">Jane Student</option>
  <option value="saab">Bob Student</option>
  <option value="mercedes">Steve Student</option>
</select></span>
            <canvas id="myChart" width="200" height="100"></canvas>
          </div>
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
  <script src="/js/dashboard.js"></script>
  <script src="/js/studentData.js"></script>
</body>

</html>