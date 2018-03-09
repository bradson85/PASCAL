<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Welcome to PASCAL</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M"
    crossorigin="anonymous">

    <!-- Custom fonts for this template -->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template -->
    <link href="css/landing-page.css" rel="stylesheet">

  </head>

  <body>

   <!-- Add Nav Bar part-->
<?php include "login-topbar-header.php"; 
  
  ?>
 
    <!-- Masthead -->
    <header class="masthead text-white text-center">
      <div class="overlay"></div>
      <div class="container">
        <div class="row">
          <div class="col-xl-9 mx-auto">
            <h1 class="mb-5">PASCAL System</h1>
            <h3>Progress Assessment in Science and Academic Language</h3>
          </div>
          <div class="col-md-10 col-lg-8 col-xl-7 mx-auto">
          <br><a href="login.php" id='middlelogin' class="btn btn-primary btn-lg" role="button" aria-pressed="true">Sign In</a>
          </div>
        </div>
      </div>
    </header>
    <section class="bg-white mb-0">
    <div class="overlay"></div>
      <div class="container">
        <div class="row">
          <div class="col">
          <h3 class="mb-5 text-center">About</h3>
           <p><h4 class="mb-5">PASCAL is a tool to assist teachers in monitoring student performance in science and
                academic language. This formative assessment tool will allow a teacher to weekly assess a student's
                content knowledge in science that has been aligned with the Next Generation Standards in Science (NGSS) standards.
            </h4></p>

          </div>
        </div>
      </div>
      </div>
        </section>
        <section class="text-white mb-0" id="about">
      <div class="container">
        <h2 class="text-center text-uppercase text-white">How It Works</h2><br>
        <ul><p>1. Teachers can select key discipline for the assessment (i.e. Life Science,
                Physical Science, Earth Science, and Engineering, Technology, and Applications)</p></ul>
         <ul><p>2. The computer-generated assessments allow students to match as many science concepts/words 
                with their corresponding defintions as they can in 10 mimutes. Additional defintions are included 
                to control for the process of elimination.</p></ul>
        <ul><p>3. Students are presented with grade level words and able to utilize text to speech
                software to to assist with the reading if the text</p></ul>
                <ul><p>4. Reports are self-generated to assist the teacher in tracking student progress.</p></ul>
      </div>
    </section>

  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="padding:35px 50px;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4><span class="glyphicon glyphicon-lock"></span> Login</h4>
        </div>
        <div class="modal-body" id='addform' style="padding:40px 50px;">
         
        </div>
        <div class="modal-footer">
          <button type="submit" id= login class="btn btn-danger btn-default pull-left" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
        </div>
      </div>
      
    </div>
  </div> 
</div>
 
    <!-- Footer -->
    <footer class="footer bg-light">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 h-100 text-center text-lg-left my-auto">
            <ul class="list-inline mb-2">
              <li class="list-inline-item">
                <a href="login.php">Log In</a>
              </li>
              <li class="list-inline-item">&sdot;</li>
              <li class="list-inline-item">
                <a href="#">Contact</a>
              </li>
              <li class="list-inline-item">&sdot;</li>
              <li class="list-inline-item">
                <a href="#">Terms of Use</a>
              </li>
              <li class="list-inline-item">&sdot;</li>
              <li class="list-inline-item">
                <a href="#">Privacy Policy</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </footer>

   <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS Code from bootstrap site -->
  <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
    crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
    crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" 
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
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

  <script src="/js/login.js"></script>

  </body>

</html>


  

    
 