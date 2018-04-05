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
// variables;
$tabTitles = array ("Categories and Levels","Terms and Definitions","School Information","Class Information");
$tabIds =array ("categories","terms","schools","classes");


   echo headerStuff("Admin Data");  // start pages with this
  include "topbar-header.php"; 
include "sidebar-header.php";
//include $_SERVER['DOCUMENT_ROOT']."/php/inc-admin-data.php";
echo "<!-- Start main html-->
<main class='col-sm-9 ml-sm-auto col-md-10 pt-3' role='main'>";

echo uploadTabDataPage($tabTitles,$tabIds);

///alert messages stuff
echo largeModal();
echo smallModal();
echo changesMade();
echo importModal();
echo exportModal();
echo searchTable();
echo "<div id='steve'>";


echo "</div></main>"; 
 echo footerStuff("admin_data"); //end  pages with this


}


function changesMade(){
  echo '<div id ="changes" style = "position:fixed; bottom: 0; right:0;"  class="alert alert-warning alert-dismissible fade show" role="alert" >
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
  <strong>Changes Made?</strong> Dont Forget To Save.
</div>';

}
function searchTable(){

  return '<br><br><div id = "topTable" class="table-responsive">
       <table id="sort_table" class="table table-bordered">
         <tbody id = "sort_body"></tbody>
       </table>
     </div>';
}


function uploadTabDataPage($title,$id){

// note double quotes
$string = '<div><ul class="nav nav-tabs">';
$count =0 ;
foreach ($title as $index) {
    $string.=  '<li class="nav-item">
    <a  id="'.$id[$count].'" class="nav-link" href="#">'.$index.'</a>
  </li>' ;
  $count = $count+1;
}
unset($index);
$sting .= '</ul>
            </div>';

return $string;
}

function largeModal(){
echo '<div id ="sure" class="modal fade">
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
</div>';

}


function smallModal(){

echo '<div id ="confirm" class="modal fade">
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
</div>';



}

function importModal(){

  return  '<div class="modal fade" id="importModal" role="dialog">
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
  <small id="fileHelp" class="form-text text-muted">Add a list of CSV Info in the FORM OF: Class, Grade Level , School ID.</small>
  <button type="submit" class="btn btn-primary" id="fileup" disabled>Upload</button>
  </div>
  </form>
    </div>   
      </div> 
    </div>  
 </div>';
}


function exportModal(){
 return   '<div class="modal fade" id="exportModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal import content-->
      <div class="modal-content">
        <div class="modal-header" style="padding:15px 40px;">
          <H5>Export List</H5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body" style="padding:20px 50px;">
        <div class="col-sm-6"><label >Export CSV</label><br><br>
  <small id="downloadHelp" class="form-text text-muted">Click Download To Export Class List to CSV</small>
  <a href="#"  id="exportbutton" class="btn btn-primary" role="button" download="exportedterms">Download</a>
  
  </div>
    </div>   
      </div> 
    </div>  
 </div>';

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
     
      <!--dataTables Testing responsive table-->

      <link rel='stylesheet' href= 'https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css' />
      <!-- Custom styles for this template -->
      <link rel='stylesheet' href='/css/cssAddWords.css' />
    </head>
    <body>";
}


function footerStuff($jsFilename){
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
    <script src='/js/$jsFilename.js'></script>
  
  </body>
  
  </html>" ;
  



}

?>