<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en" xml:lang="en" xmlns= "http://www.w3.org/1999/xhtml">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>View Class List</title>
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
<!--small modal content-->
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
<!--response modal content-->
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
    <button type="button" id="modalsave" class="btn btn-danger">OverWrite</button>
      <button type="button" id ="modalclose "class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>
</div>
      <div class="modal fade" id="importModal" role="dialog">
    <div class="modal-dialog">
      <!-- Modal import content-->
      <div class="modal-content">
        <div class="modal-header" style="padding:15px 40px;">
          <H5>Import List</H5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body" style="padding:20px 50px;">
        <form method="POST" action="#" enctype="multipart/form-data" id="fileForm">
  <div class="form-group">
  <label for="InputFile">Import CSV</label>
  <input type="file" accept=".csv" class="form-control-file" id="InputFile" name= "InputFile" aria-describedby="fileHelp">
  <small id="fileHelp" class="form-text text-muted">Add a list of CSV Info in the FORM OF: Name, Email, Password, Class, Grade Level , School ID.</small>
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
          <H5>Export List</H5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body" style="padding:20px 50px;">
        <form method="POST" action="#" id="exportForm">
  <div class="form-group">
        <div class="col-sm-6"><label >Export CSV</label><br><br>
  <small id="downloadHelp" class="form-text text-muted">Click Download To Export Class List to CSV</small>
  <button type="submit" class="btn btn-primary" id="exportbutton" download="classlist">Download</button>
  </div>
  </form>
  </div>
    </div>   
      </div> 
    </div>  
 </div>

 <div class="card mb-3">
        <div class="card-header boardModule">
        <h5  id ='listHeader' class="card-title text-white"> Class Lists For <?php echo $_SESSION['user']?></h5>
            </div>
        <div class="card-body">
        <div id = "topTable" class="table-responsive">
       <table id="sort_table" class="table table-bordered">
         <tbody id = "sort_body"></tbody>
       </table>
     </div>
     <div class="btn-group float-right " role="group" aria-label="Basic example">
            <a class="btn btn-secondary btn-sm" id="classListImportSelect" href="#">Import Class List</a>
            <a class="btn btn-secondary btn-sm"  id="classListExportSelect" href="#">Export Class List</a>
            <a class="btn btn-secondary btn-sm"  id="deleteStudent" href="#">Delete Mode</a>
            </div>
        <div class="table-responsive">
            <table class="table table-bordered" id="classListTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Password Set</th>
                  <th>Grade Level</th>
                  <th>Class</th>
                  <th>School</th>
                  <th id ="editHead" hidden></th>
                </tr>
              </thead>
              <tbody>
                </tbody>
            </table>
          </div>
        </div>
      </div>
    
      
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
  <script src="/js/dashboard.js"></script>
</body>

</html>