<?php session_start(); ?>


<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Assign An Assessment</title>
  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M"
    crossorigin="anonymous">

  <!-- Custom styles for this template -->
  <link rel="stylesheet" href="/css/cssAddWords.css" />
</head>

<body>

<!-- Add Nav Bar part-->
<?php include "topbar-header.php"; 
  include "sidebar-header.php"
?>




<!-- Start main html-->
      <main class="col-sm-9 ml-sm-auto col-md-10 pt-3" role="main">
<!-- Alert boxes stuff-->
      <?php
      include("alertmessages-header.php");
      // check get variable for import success
    if(isset($_GET["imp"])) {
      echo specialMessages($_GET["imp"],"success");
           
    } else{
// check get for import fail
    if(isset($_GET["fal"])) {
      
       echo specialMessages($_GET["fal"],"error");
     }else{
      echo simpleMessages();
     }
    }  
  ?>

<!-- END of Alert box stufff-->

     <!--Sorting Stuff-->
       <div class="table-responsive">
        <H2> Assign An Assessment To A Student</H2>
          <table id="selection_table" class="table table-bordered">
          <thead>
              <tr>
                <th>Assessment Choice</th>
                <th>Student Assigned To</th>
                <th>Email Address To Send To</th>
                <th>Expiration Date</th>
              </tr>
            </thead>
            <tbody id = "sort_body">
            <tr>  <?php  include "php/inc-assignAssessment-functions.php";  
                         dbGetAssessments(); 
                         dbGetStudents();               
            ?> 
           </tr>
            </tbody>
          </table>
       </div>
       <p class="Buttons">
            <button class = " btn btn-primary" id="assign">Assign Assessment</button>
          </p>

       <div class="table-responsive">
        <H2> Assessments Assigned To Students</H2>
          <table id="assess_table" class="table table-bordered">
          <thead>
              <tr>
                <th>Assessment Number</th>
                 <th>Student Assigned To</th>
                <th>Expiration Date</th>
               
              </tr>
            </thead>
            <tbody id = "t_body">
              <?php  
                      dbViewRecentAssessments();                 
            ?>  
           
            </tbody>
          </table>
       </div>
      <!--  Table Part-->
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
  <script src="/js/assignAssessment.js"></script>
</body>

</html>