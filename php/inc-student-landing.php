<?php session_start()?>
<!DOCTYPE html>
<html>

<head>

  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Dashboard</title>
  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M"
    crossorigin="anonymous">

  <!-- Custom styles for this template -->
  <link rel="stylesheet" href="../css/cssAddWords.css" />
</head>

<body>
<!-- Add Nav Bar part-->
<?php 

 include $_SERVER['DOCUMENT_ROOT']."/student-topbar-header.php"; 
  
?>
<main class="col-sm-9 ml-sm-auto col-md-10 pt-3" role="main">
     <!--Asssingned Assessment Table-->
       <div class="table-responsive">
        <H2> Assessment Assigned</H2>
          <table id="selection_table" class="table table-bordered">
          <thead>
              <tr>
                <th>Assessment Category</th>
                <th>Start Date</th>
                <th>End Date</th>
              </tr>
            </thead>
            <tbody id = "sort_body">
              <?php include $_SERVER['DOCUMENT_ROOT']."/php/inc-student-landing-functions.php" ?>
                    
            </tbody>
          </table>
       </div>
       <?php getButton(); ?>
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
  <script>
    if (typeof ($.fn.modal) === 'undefined') {
      document.write('<script src="../js/bootstrap.min.js"><\/script>')
    }
  </script>
  <script>
    window.jQuery || document.write('<script src="../js/jquery-3.2.1.min.js"><\/script>');
  </script>
  <div id="bootstrapCssTest" class="hidden"></div>
  <script>
    $(function () {
      if ($('#bootstrapCssTest').is(':visible')) {
        $("head").prepend('<link rel="stylesheet" href="../css/bootstrap.min.css">');
      }
    });
  </script>
</body>

</html>