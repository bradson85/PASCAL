<?php session_start(); ?>

<!DOCTYPE html>
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

<!-- Add Nav Bar part-->
<?php include "teacher-topbar-header.php"; 
  
?>




<!-- Start main html-->
      <main class="col-sm-9  col-md-10 pt-3" role="main">
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
     
         <H2> Assign An Assessment</H2>
         <small  class="form-text text-muted">To Assign an Assessment: Pick School, Class,
          Assign To Choice, Assign To Student or Entire Class , Pick Which Assessment To Assign:</small>
          <br>
          <div id= "school">
         
          </div>
          <div id= "classes">
         
          </div>
          <div id= "options">
          </div>
          <div id= "students">
          </div>
          <div id= "assessments">
          </div>
          <br>
            <p class="Buttons">
            <button class = " btn btn-primary" id="assign" disabled >Assign Assessment</button>
          </p>
      
      </main>
    
  
  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS Code from bootstrap site -->
  <script
  src="http://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
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
  <script src="/js/assignAssessmentTeacher.js"></script>
</body>

</html>