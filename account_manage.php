<?php 
session_start(); 
if(isset($_SESSION['user']) ){
    openPages();
} else{
  echo "Unauthorized access";
  header($_SERVER['DOCUMENT_ROOT']."Location:index.php"); //to redirect back to "index.php" after logging out
  exit();
} 

function openPages(){
echo headerStuff("Account Management");  // start pages with this
include "topbar-header.php"; 
include "sidebar-header.php";
//include $_SERVER['DOCUMENT_ROOT']."/php/inc-admin-data.php";
echo "<!-- Start main html-->
<main class='col-sm-9 ml-sm-auto col-md-10 pt-3' role='main'>";

echo buttonLinks();

echo "</div></main>"; 
 echo footerStuff(); //end  pages with this
}


function buttonLinks(){

 return  ' <br><br><p class="text-center"> <a href="create-account.php" class="btn btn-primary btn-lg" role="button">Create Accounts</a>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <a href="deleteAccounts.php" class="btn btn-primary btn-lg" role="button">Delete Accounts</a><p>';
}



function headerStuff($title){



    return " <!DOCTYPE html>
    <html>
    <head>
      <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
      <title>$title</title>
      <!-- Bootstrap core CSS -->
      <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' 
      integrity='sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm' crossorigin='anonymous'>
      <!-- Custom styles for this template -->
      <link rel='stylesheet' href='/css/cssAddWords.css' />
    </head>
    <body>";
}


function footerStuff(){
return " 
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS Code from bootstrap site -->
    <script src='https://code.jquery.com/jquery-3.2.1.min.js' integrity='sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4='
      crossorigin='anonymous'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js' 
    integrity='sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4'
      crossorigin='anonymous'></script>
      <script src='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js' 
      integrity='sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl' crossorigin='anonymous'></script>
      <script src='https://code.jquery.com/qunit/qunit-2.4.1.js'></script>
    <script src='/tests/tests.js'></script>
    <script>
      if (typeof ($.fn.modal) === 'undefined') {
        document.write(\"<script src='/js/bootstrap.min.js'><\/script>\")
      }
    </script>
    <script>
      window.jQuery || document.write(\"<script src='/js/jquery-3.2.1.min.js'><\/script>\");
    </script>
    <div id='bootstrapCssTest' class='hidden'></div>
    <script>
      $(function () {
        if ($('#bootstrapCssTest').is(':visible')) {
          $('head').prepend(\"<link rel='stylesheet' href='/css/bootstrap.min.css'>\");
        }
      });
    </script>
  </body>
  
  </html>" ;

}

?>