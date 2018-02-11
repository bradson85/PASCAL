<?php session_start();?>
<!DOCTYPE html>
<html>

<head>

  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Dashboard</title>
  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

  <!-- Custom styles for this template -->
  <link rel="stylesheet" href="../css/cssAddWords.css" />
</head>

<body>
<!-- Add Nav Bar part-->
<?php 

  include  __DIR__ ."/../topbar-header.php"; 
  include  __DIR__ ."/../sidebar-header.php";
?>




<!-- Start main html-->
      <main class="col-sm-9 ml-sm-auto col-md-10 pt-3" role="main">
         <!-- Icon Cards examples-->
      <div class="row">
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-white bg-primary o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-comments"></i>
              </div>
              <div class="mr-5">26 Completed Assesments</div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="#">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-white bg-warning o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-list"></i>
              </div>
              <div class="mr-5">11 Assessments Incomplete</div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="#">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-white bg-success o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-shopping-cart"></i>
              </div>
              <div class="mr-5"> Some Text Here</div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="#">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-white bg-danger o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-support"></i>
              </div>
              <div class="mr-5">&#9888 &nbsp 2 Alerts</div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="#">
              <span class="float-left">View Details</span>
              <span class="float-right">
              </span>
            </a>
          </div>
        </div>
      </div>
      
      <!-- Area Chart Example-->
      <div class="row">
        <div class="col-lg-8">
        <div class="card text-center">
    <div class="card-header">
      <h5>Directions</h5>
         </div>
           <div class="card-block">
            <h4 class="card-title"><br>What Works?</h4>
          <p class="card-text">None of these dashboard cards work. <br>However most nav-links work on the left Navbar. Check them out.</p>
          <a href="#" class="btn btn-primary">Go somewhere</a>
            </div> <br>
               <div class="card-footer text-muted">
                             2 days ago
                        </div>
                    </div>
<br>
          <!-- Example  Card-->
          <div class="card mb-3">
            <div class="card-header">
              Information Card Example</div>
            <div class="card-body">
              <div class="row">
                <div class="col-sm-8 my-auto">
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
    <a href="#" class="btn btn-primary">Go somewhere</a>
                </div>
              </div>
            </div>
            <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
          </div>
      <div class="card mb-3">
        <div class="card-header">
        <h5 class="card-title">Recent Tests Taken</h5></div>
        <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Grade</th>
                  <th>Teacher</th>
                  <th>School</th>
                  <th>Age</th>>
                  <th>Average</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Tiger Nixon</td>
                  <td>Grade 3 </td>
                  <td>Mrs. Smith</td>
                  <td>Edwardsville</td>
                  <td>9</td>
                  <td>95</td>
                  <td>9/5/17</td>
                </tr>
                <tr>
                  <td>Garrett Winters</td>
                  <td>Grade 4</td>
                  <td>Mrs. Doe</td>
                  <td>Town</td>
                  <td>10</td>
                  <td>95</td>
                  <td>9/5/17</td>
                </tr>
                <tr>
                  <td>Donna Snider</td>
                  <td>Grade 5 </td>
                  <td>Mrs. Smith</td>
                  <td>Edwardsville</td>
                  <td>11</td>
                  <td>95</td>
                  <td>9/5/17</td>
                </tr>
                <tr>
                  <td>Michael Bruce</td>
                  <td>Grade 3 </td>
                  <td>Mrs. Smith</td>
                  <td>Edwardsville</td>
                  <td>9</td>
                  <td>95</td>
                  <td>9/4/17</td>
                </tr>
                </tbody>
            </table>
          </div>
        </div>
        <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
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
  <script src="/tests/tests.js"></script>
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