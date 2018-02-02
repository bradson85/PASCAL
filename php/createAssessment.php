<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Create Assessment</title>
  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M"
    crossorigin="anonymous">

  <!-- Custom styles for this template -->
  <link rel="stylesheet" href="../css/createAssessment.css" />
</head>

<body>

<!-- Add Nav Bar part-->
<!--  Top Nav Bar Part-->
<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <a class="navbar-brand" href="#">Formative Assessment</a>
    <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault"
      aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
   
    <div class="collapse navbar-collapse" id="navbarsExampleDefault"> <!--  This is what makes it responsive-->
    <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link customlink" href="dashboard.php">&#8962  &nbsp Dashboard
            <span class="sr-only">(current)</span>   
          </a>
        </li>
        <li class="nav-item customlink">
          <a class="nav-link" href="#">&#9881 &nbsp Settings</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#"> Help</a>
        </li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
      <li class="nav-item customlink">
      <a class="nav-link" href="index.php">&#128274 &nbsp Log Out</a>
      <li class="nav-item">
          <a class="nav-link" href="#">&#128100  &nbsp Account</a>
    </ul>
    </div>
  </nav>
 

  <!--  side bar nav-->
  <div class="container-fluid">
    <div class="row">
      <nav class="col-sm-3 col-md-2 d-none d-sm-block bg-dark sidebar">
      <ul class="sidebar-brand">
      <a href="#"> Quick Links </a>
      </ul>
        <ul class="nav nav-pills flex-column">
          <li class="nav-item">
            <a class="nav-link" href="createAssesment.php">Create Assessment
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Edit Assessment</a> 
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Take Assessment</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="#">Generate Report</a>
            </li>
        </ul>

        <ul class="nav nav-pills flex-column">
          <li class="nav-item">
            <a class="nav-link" href="add_editwords.php">Add or Edit Terms</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="add_editwords.php">Import/Export Terms</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="add_editcategories.php">Add or Edit Categories</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="add_editcategories.php">Import/Export Categores</a>
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




<!-- Start main html-->
      <main class="col-sm-9 ml-sm-auto col-md-10 pt-3" role="main">
      <H2> Create New Assessment</H2>
      <div class="row">
        <div class="col" id="categorychoice">
          Choose the category of words to be choosen for a assessment.  
        </div>
        <div class="col">
          <button type="submit" class="btn btn-primary" id="submit">Submit</button>
        </div>
      </div>
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
  <script src="../js/createAssessment.js"></script>
</body>

</html>