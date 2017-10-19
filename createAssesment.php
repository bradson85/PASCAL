<!DOCTYPE html>
<html>
<head>
<title>Create an Assesment</title>
<link rel="stylesheet"
    href="css/createAssessment.css" />
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
   <select id='categorySelector' class="styled-select slate">
  <option value="">Please Select</option>
  <option value='1'>Database updates this</option>  
</select>
<button type="submit" name="" class="style">Submit Category</button>
	
</td>
</table>
</form>


<table id="wordList">
    <thead>
        <tr id="wordList1">
            <th id="wordList1">Checkbox</th>
            <th id="wordList1">Term</th>
            <th id="wordList1">Definition</th>
</tr>
    </thead>
    <tbody>
    <tr id="wordList1">
<td>
    <input type="checkbox" name="check" value=""></td>
<td>Terms</td>
<td>Deftinition</td>
</tr>
<tr><td>Check Boxes</td>
<td>Terms</td>
<td>Deftinition</td></tr>
        <tr></tr>
        <tr></tr>
    </tbody>
</table> 
<div id="submitDiv"> 
<form id="submitAssessment">
 <br>Name the new assessment.<br>
  <input type="text" name="firstname"><br> 
  <button name="submit" class="style">Submit</button>
</form>
</div>
</div>  

</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="js/jquery-3.2.1.min.js"></script>
<script src="js/createAssessment.js"></script>
</body>
</html>