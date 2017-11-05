<?php
// add simple set of waring boxes that can be manipulated with jquery or javascript
function simpleMessages(){

    $string = ('<div class="alert alert-success hide" role="alert" id = "success">
    <button type="button" class="close"  aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
    <strong></strong>
  </div>
  <div class="alert alert-info hide" role="alert" id = "info">
  <button type="button" class="close"  aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
    <strong></strong>
  </div>
  <div class="alert alert-danger hide" role="alert" id = "warning">
  <button type="button" class="close"  aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
    <strong></strong>
  </div>' );
  
 return $string;
}
// add specialized warning boxes to manipultaed with javascript
function specialMessages($message,$type){

    switch($type){
    case "success":   
    $string = ("<div class=\"alert alert-success hide \" role=\"alert\" id = \"success\">
    <button type=\"button\" class=\"close\"  aria-label=\"Close\">
      <span aria-hidden=\"true\">&times;</span>
    </button>
    <strong></strong>
  </div>
  <div class=\"alert alert-success hide \" role=\"alert\" id = \"special\">
  <button type=\"button\" class=\"close\"  aria-label=\"Close\">
    <span aria-hidden=\"true\">&times;</span>
  </button>
  <strong>$message</strong>
</div>
  <div class=\"alert alert-info hide\" role=\"alert\" id = \"info\">
  <button type=\"button\" class=\"close\"  aria-label=\"Close\">
      <span aria-hidden=\"true\">&times;</span>
    </button>
    <strong></strong>
  </div>
  <div class=\"alert alert-danger hide\" role=\"alert\" id = \"warning\">
  <button type=\"button\" class=\"close\"  aria-label=\"Close\">
      <span aria-hidden=\"true\">&times;</span>
    </button>
    <strong></strong>
  </div>");
  break;
  case "error":
  // danger is not hidden and $message is inside
  $string = ("<div class=\"alert alert-success hide\" role=\"alert\" id = \"success\">
  <button type=\"button\" class=\"close\"  aria-label=\"Close\">
    <span aria-hidden=\"true\">&times;</span>
  </button>
  <strong></strong>
</div>
<div class=\"alert alert-info hide\" role=\"alert\" id = \"info\">
<button type=\"button\" class=\"close\"  aria-label=\"Close\">
    <span aria-hidden=\"true\">&times;</span>
  </button>
  <strong></strong>
</div>
<div class=\"alert alert-danger hide \" role=\"alert\" id = \"warning\">
<button type=\"button\" class=\"close\"  aria-label=\"Close\">
    <span aria-hidden=\"true\">&times;</span>
  </button>
  <strong></strong>
  </div>
  <div class=\"alert alert-danger hide \" role=\"alert\" id = \"special\">
  <button type=\"button\" class=\"close\"  aria-label=\"Close\">
      <span aria-hidden=\"true\">&times;</span>
    </button>
    <strong>$message</strong>
</div>");
break;
default:
 $string =("<div class=\"alert alert-success hide\" role=\"alert\" id = \"success\">
<button type=\"button\" class=\"close\"  aria-label=\"Close\">
  <span aria-hidden=\"true\">&times;</span>
</button>
<strong></strong>
</div>
<div class=\"alert alert-info hide\" role=\"alert\" id = \"info\">
<button type=\"button\" class=\"close\"  aria-label=\"Close\">
  <span aria-hidden=\"true\">&times;</span>
</button>
<strong></strong>
</div>
<div class=\"alert alert-danger hide\" role=\"alert\" id = \"warning\">
<button type=\"button\" class=\"close\"  aria-label=\"Close\">
  <span aria-hidden=\"true\">&times;</span>
</button>
<strong></strong>
</div>");

    }
  return $string;
}
           

?>