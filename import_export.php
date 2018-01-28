
<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Edit Categories</title>
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
<!-- File Import/Export stuff-->

<div class="row">
  <div class="col-sm-6">
  <form method="POST" action="php/inc-addcategories-importFile.php" enctype="multipart/form-data" id="fileForm">
  <div class="form-group">
  <label for="InputFile">Import CSV</label>
  <input type="file" accept=".csv" class="form-control-file" id="InputFile" name= "InputFile" aria-describedby="fileHelp">
  <small id="fileHelp" class="form-text text-muted">Add a list of CSV Info in the FORM OF: Category, Level.</small>
  <button type="submit" class="btn btn-primary" id="fileup">Upload</button>
  </div>
  </form>
</div>
  <div class="col-sm-6"><label >Export CSV</label><br><br>
  <small id="downloadHelp" class="form-text text-muted">Click Download To Export Table to CSV</small>
  <a href="/php/inc-addcategories-exportFile.php" class="btn btn-primary" role="button" download="exportedterms">Download</a>
  
  </div>
  
</div>
</main>
<!--  End of File Import/Export stuff-->
</body>
</html>