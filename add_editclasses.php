<?php session_start();?>
<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Edit Classes</title>
  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <!-- Custom styles for this template -->
  <link rel="stylesheet" href="/css/cssAddWords.css" />
</head>

<body>

<!-- Add Nav Bar part-->
<?php 
include "topbar-header.php"; 
include "sidebar-header.php"
?>

<!-- Start main html-->
      <main class="col-sm-9 ml-sm-auto col-md-10 pt-3" role="main">



<!-- Modal to alert that updating categories affects children-->
<div id ="sure" class="modal fade">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Are You Sure?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Modal body text goes here.</p>
      </div>
      <div class="modal-footer">
        <button type="button" id="modalsave" class="btn btn-warning">Overwrite</button>
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
        <button type="button" id ="modalclose "class="btn btn-secondary" data-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>

<!-- END of Alert box stufff-->

<!-- File Import/Export stuff-->

<div class="modal fade" id="importModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal import content-->
      <div class="modal-content">
        <div class="modal-header" style="padding:15px 40px;">
          <H5>Import Class List</H5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body" style="padding:20px 50px;">
        <form method="POST" action="php/inc-addclasses-importFile.php" enctype="multipart/form-data" id="fileForm">
  <div class="form-group">
  <label for="InputFile">Import CSV</label>
  <input type="file" accept=".csv" class="form-control-file" id="InputFile" name= "InputFile" aria-describedby="fileHelp">
  <small id="fileHelp" class="form-text text-muted">Add a list of CSV Info in the FORM OF: Class, Grade Level , School ID.</small>
  <button type="submit" class="btn btn-primary" id="fileup">Upload</button>
  </div>
  </form>
    </div>   
      </div> 
    </div>  
 </div>


<div class="modal fade" id="exportModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal import content-->
      <div class="modal-content">
        <div class="modal-header" style="padding:15px 40px;">
          <H5>Export School List</H5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body" style="padding:20px 50px;">
        <div class="col-sm-6"><label >Export CSV</label><br><br>
  <small id="downloadHelp" class="form-text text-muted">Click Download To Export Class List to CSV</small>
  <a href="/php/inc-addclasses-exportFile.php" class="btn btn-primary" role="button" download="exportedterms">Download</a>
  
  </div>
    </div>   
      </div> 
    </div>  
 </div>
<!--  End of File Import/Export stuff-->

      <!--  Table Part-->
        <div class="table-responsive">
          <H2>Classes</H2>
          <p class="Buttons">
            <button class = " btn btn-primary" id="addRow2">Add Class</button>
            <button class = " btn btn-primary" id="save2">Save Changes</button>
            <button type="button" class="btn btn-sm btn-light" data-toggle="tooltip" 
            data-placement="right" title="Add New Class by Clicking Add Class Button.
                       Save Changes by Clicking Save."><img src ="img/helpicon.png" width="25"> </button>
          </p>
          <small  class="form-text text-muted">Click Table Cells To Edit</small>
          <table id="word_table" class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>Class Name</th>
                <th>Grade Level</th>
                <th>School</th>
                <th class="col-sm-auto"> <div class="dropdown">
  <button class="btn btn-primary btn-sm dropdown-toggle float-right" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
  &#9776;
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    <a class="dropdown-item" id="classImportSelect" href="#">Import Class List</a>
    <a class="dropdown-item"  id="exportSelect" href="#">Export Class List</a>
  </div>
</div></th>
              </tr>
            </thead>
            <tbody id = "t_body"></tbody>
          </table>
          <p class="Buttons">
            <button class = " btn btn-primary" id="addRow1">Add Class</button>
            <button class = " btn btn-primary" id="save1">Save Changes</button>
          </p>
        </div>
      </main>
      <div id ="changes" style = 'position:fixed; bottom: 0; right:0;'  class="alert alert-warning alert-dismissible fade show" role="alert" >
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
  <strong>Changes Made?</strong> Dont Forget To Save.
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
  <script src="/js/Addclasses.js"></script>
</body>

</html>
