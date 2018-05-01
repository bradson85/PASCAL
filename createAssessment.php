<?php session_start();
    //$_SESSION['user'] = 'User';
 ?>
<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Create Assessment</title>
  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M"
    crossorigin="anonymous">

  <!-- Custom styles for this template -->
  <link rel="stylesheet" href="css/main.css" />
</head>

<body>

<!-- Add Nav Bar part-->
<?php include "topbar-header.php"; 
include "sidebar-header.php";
?>


<!-- Start main html-->
      <main class="col-sm-9 ml-sm-auto col-md-10 pt-3" role="main" id="createAssessment">
          <H2>Create New Assessment</H2>
          <span id="alertPlaceholder"></span>

      <div class="row">
        <div class="col-sm-4" id="categorychoice">
          Category:
        </div>
        <div class="col-sm-4">
          <label for="date">Date:</label>
          <input type="date" id="startDate">
        </div>
        <div class="col-sm-4">
          <div class="btn-group btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-secondary active" id="random">
              <input type="radio" name="options" id="randomInput" autocomplete="off" checked> Random
            </label>
            <label class="btn btn-secondary" id="selfSelect">
              <input type="radio" name="options" id="selfInput" autocomplete="off"> Self Select
            </label>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-10">
          <div id="terms">
            
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-5">
          <div class="card" id="studentAssignments" style="display: none">
            <div class="card-header">
              Student Assignments
            </div>
            <div class="card-body">
              <table class="table table-striped" id="studentTable">

              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        
        <div class="col">
          <button type="submit" class="btn btn-primary" id="submit">Create</button>
        </div>
      </div>
      <!--  Table Part-->
        <!-- <div class="table-responsive">
          
           <table id="word_table" class="table table-striped table-bordered">
            <thead>
              <tr>
                <th></th>
                <th>Word</th>
                <th>Definition</th>
              </tr>
            </thead>
            <tbody id = "t_body"></tbody>
          </table>
                   <div class= "messages" id="instructions"></div>
          <div class="table-responsive">
          <table id="assessment_table" class="table table-striped table-bordered">
            <tr>
            <th>Assessment ID</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Category and Level</th>
            </tr>
            <tr><td>Test</td></tr>
          </table>
          
          <p class="Buttons">
            <button class = " btn btn-primary" id='addRow'>Add Assessment</button>
            <button class = " btn btn-primary" id="save">Save Assessment</button>
          </p>
          </div> -->
<br />
<br />
<H2> List of Current Assessments</H2>
        <div class="table-responsive">
          
          <table id="assess_table" class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>Assessment ID</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Category And Level</th>
                <th></th>
              </tr>
            </thead>
            <tbody id = "t_body2"></tbody>
          </table>
        </div>
      </main>
    </div>
  </div>
  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS Code from bootstrap site -->
  <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
    crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
    crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1"
    crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/qunit/qunit-2.4.1.js"></script>
  
  <script>
    if (typeof ($.fn.modal) === 'undefined') {
      document.write('<script src="js/bootstrap.min.js"><\/script>')
    }
  </script>
  <script>
    window.jQuery || document.write('<script src="js/jquery-3.2.1.min.js"><\/script>');
  </script>
  <div id="bootstrapCssTest" class="hidden"></div>
  <script>
    $(function () {
      if ($('#bootstrapCssTest').is(':visible')) {
        $("head").prepend('<link rel="stylesheet" href="css/bootstrap.min.css">');
      }
    });
  </script>
  <script src="js/createAssessment.js"></script>
</body>

</html>