<!DOCTYPE html>
<html>
<head>
<title>Create an Assesment</title>
<link rel="stylesheet"
    href="../css/createAssessment.css" />
</head>
<body>
<div id="menubar">Menu items go here</div>    
<div class = "container">
<div id= "sidemenu">  

    <p>Maybe show all assessments that are already made here. This way the user can click on them to see and edit already made assessments.</p>
</div>
  <div id="tableDiv">   
<form id="category" name="getcategory" method="" action="">
<table id="category">
   <thead>
   Choose the category of words to be choosen for a assessment.
   </thead>
<td>
 
	
</td>
</table>
</form>

<?php require 'inc-createassessment-getcategories.php';?>

<table id="word_table" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Checkbox</th>
            <th>Term</th>
            <th>Definition</th>
</tr>
    </thead>
    <tbody id="t_body"></tbody>
</table> 




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
<script src="../js/createAssessment.js"></script>
</body>
</html>