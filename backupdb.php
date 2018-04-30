<?php
// Start the session
session_start();
if((!isset($_SESSION['type'])) && $_SESSION['type'] != 0){
    echo "Unauthorized access";
   header("Location:index.php"); //to redirect back to "index.php" after logging out
   exit();
 } 
?>

<!DOCTYPE html>
<html>



<head>

  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Backup</title>
  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M"
    crossorigin="anonymous">

  <!-- Custom styles for this template -->
  <link rel="stylesheet" href="/css/cssAddWords.css" />
</head>

<body>

<!-- Add Nav Bar part-->
<?php 

include  "topbar-header.php"; 
include  "sidebar-header.php"

?>




<!-- Start main html-->
<main class="col-sm-9  ml-sm-auto col-md-10 pt-3 " role="main">
<div class= "container">
<h2>Data Backup And Delete</h2>
<form action="/action_page.php">
  <div class="form-group">
  <label for="sel1">Data Save Type:</label>
  <select class="form-control" id="sel1">
  <option value="CSV">CSV</option>
  <option value="SQL">SQL</option>
  <option value="TEXT">TEXT</option>
</select>
  </div>
  <div class="form-check">
  <label class="form-check-label">
    <input type="checkbox" class="form-check-input" id="s" value="0" checked> Save All Data
  </label>
</div>
<div class="form-check">
  <label class="form-check-label">
    <input type="checkbox" class="form-check-input" id ="sd" value="1"> Delete All Data
  </label>
</div>
<div class="form-check">
  <label class="form-check-label">
    <input type="checkbox" class="form-check-input" id ="r" value="2"> Emergency Restore Database<br> (Restores Last SQL Backup Made From File On The Server)
  </label>
</div>
  
  <button id="sendform" class="btn btn-primary">Submit</button>

</form>
         </div>


<!-- Modal -->
<div id ="sure" class="modal fade">
<div class="modal-dialog" role="dialog">
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
    <a href="#"  id="modalsave" class="btn btn-warning" role="button" >Download</a>
      <button type="button" id ="modalclose "class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>
</div>

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
  <script src='/js/backup.js'></script>
</body>

</html>