<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Edit Terms and Definitions</title>
  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M"
    crossorigin="anonymous">

  <!-- Custom styles for this template -->
  <link rel="stylesheet" href="../css/cssAddWords.css" />
</head>

<body>
<!--  Nav Bar Part-->
  <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <a class="navbar-brand" href="#">Dashboard</a>
    <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault"
      aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href="#">Home
            <span class="sr-only">(current)</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Settings</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Profile</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Help</a>
        </li>
      </ul>
    </div>
  </nav>
  <div class="container-fluid">
    <div class="row">
      <nav class="col-sm-3 col-md-2 d-none d-sm-block bg-dark sidebar">
        <ul class="nav nav-pills flex-column">
          <li class="nav-item">
            <a class="nav-link active" href="#">DashBoard
            <span class="sr-only">(current)</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Edit Words</a>
            
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Import</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Export</a>
          </li>
        </ul>

        <ul class="nav nav-pills flex-column">
          <li class="nav-item">
            <a class="nav-link" href="#">Nav item</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Nav item again</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">One more nav</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Another nav item</a>
          </li>
        </ul>

        <ul class="nav nav-pills flex-column">
          <li class="nav-item">
            <a class="nav-link" href="#">Nav item again</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">One more nav</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Another nav item</a>
          </li>
        </ul>
      </nav>

      <!-- End Of Nav Bar part-->

      <!-- File Import/Export stuff-->
      <main class="col-sm-9 ml-sm-auto col-md-10 pt-3" role="main">
      <div class="row">
  <div class="col-sm-6">
    <form action="inc-addwords-importFile.php" method="post" accept-charset="utf-8" enctype="multipart/form-data">
  <div class="form-group">
  <label for="InputFile">Import CSV</label>
  <input type="file" class="form-control-file" id="InputFile" aria-describedby="fileHelp">
  <small id="fileHelp" class="form-text text-muted">Warning. This Will Overwrite Currently Saved Words List.</small>
  <button type="submit" class="btn btn-primary" value = "submit">Upload</button>
  </div>
</form></div>
  <div class="col-sm-6"><label >Export CSV</label><br><br>
  <small id="downloadHelp" class="form-text text-muted">Click Download To Export Table to CSV</small>
  <a href="inc-addwords-exportFile.php" class="btn btn-primary" role="button" download="exportedterms">Download</a>
  
  </div>
  
</div>
<!--  End of File Import/Export stuff-->
      <!--  Table Part-->
        <div class="table-responsive">
          <H2> Words and Defintions</H2>
          <table id="word_table" class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>ID</th>
                <th>Category</th>
                <th>Word</th>
                <th>Definition</th>
                <th>Difficulty <br> Level </th>
                <th> &nbsp;</th>
              </tr>
            </thead>
            <tbody id = "t_body"></tbody>
          </table>
          <p class="Buttons">
            <button class = " btn btn-primary" id="addRow">Add Words</button>
          </p>
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
  <script src="../js/Addwords.js"></script>
</body>

</html>