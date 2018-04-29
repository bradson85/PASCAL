<?php
require_once($_SERVER['DOCUMENT_ROOT']."/dbconfig.php");
include $_SERVER['DOCUMENT_ROOT']."/php/inc-admin-db.php";

//$test_array = array();
//$test_array[0] = array('ID' => '333', 'category' => 'Physical Science 4','word' => 'test4', 'definition' => 'def423s');
//$test_array[1] = array('ID' => 334, 'category' => 'Physical Science 5','word' => 'test word2', 'definition' => 'test definition2');

//echo pickSaveDB("terms",$test_array);
//print_r(addClassesToTable());

// -----------------------------check what is postting from ajax____________
if(isset($_POST['type'])){
      $tableType = $_POST['type'];
     echo json_encode (pickTable($tableType));
}

if(isset($_POST['get'])){
    $rowUpdate = $_POST['get'];
   echo pickUpdateRow($rowUpdate,$_POST['selected']);
}

if(isset($_POST['currLev'])){
    $levelIDs = $_POST['currLev'];
    $returnData= array();
   foreach ($levelIDs as $key=> $value) {
    $returnData[$key] = retrieveSelectedCatLevel($value["ID"]);
   }
   unset($valeu);
   echo json_encode($returnData);    
}
//for deleteing items from db
if(isset($_POST['delete'])){
    $type = $_POST['delType'];
    $data = $_POST['delete'];
    if($type == "terms"){
        deleteTerms($type,$data);
    }else if($type == 'categories'){
       
        deleteCategories($type,$data);
    }else if($type == "classes"){
        deleteClasses($type,$data);
}else if($type == "schools"){
    deleteSchools($type,$data);
}
}
if(isset($_POST['info'])){
  $finished = "";
  $stypes.= "";
  $ntypes.-"";
  $newData = json_decode(stripslashes($_POST['info']),true);
  $oldData = json_decode(stripslashes($_POST['oldData']),true);
  foreach ($newData as $key => $value) {
      if(!empty($value)){
   $stypes .= "$key, ";
  $finished.= saveToDB($key,$newData[$key],$oldData[$key]);
      } else  $ntypes .= "$key, ";
  }
   if(checkEmptyUpdateString($finished))
  {
      echo "No updates for " . substr($stypes, 0, -2) .". Data already exists. No new data sent for " . substr($ntypes , 0,-2). ".";
  } else {
      echo $finished;
  }
}

if(isset($_POST['currCat'])){
    $classIDs = $_POST['currCat'];
    $returnData= array();
   foreach ($classIDs as $key=> $value) {
    $catID = getCatIDFromTermID($value["ID"]);
    $returnData[$key] = retrieveSelectedCategory($catID);
   }
   echo json_encode($returnData); 
}

 if(isset($_POST['currClass'])){
    $classIDs = $_POST['currClass'];
    $levelData= array();
    $schoolData= array();
    foreach ($classIDs as $key=> $value) {
    $schoolID= getSchoolIDFromClasses($value["ID"]);
   $schoolData[$key] = retrieveSelectedSchool($schoolID);
 $levelData[$key] =  retrieveSelectedClassLevel($value['ID']);
   }
   $returnData = array(0 =>$schoolData,
  1 => $levelData
   );
   echo json_encode($returnData); 
}

//--------------------End of Posts--------------------------------//

//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

//==============Start of Functions===================================//

function pickTable($tableType){
    switch ($tableType) {
        case "terms":
        return array (0=>createTable("Terms and Definitions",$tableType),1=>addWordsToTable());
            break;
        case "categories":
        return array(0=>createTable("Categories and Grade Level",$tableType),1=>addCategoriesToTable());
            break;
        case "schools":
        return array(0=>createTable("Schools",$tableType),1=>addSchoolsToTable());
            break;
        case "classes":
        return array(0=>createTable("Classes",$tableType),1=>addClassesToTable());
        break;
        default:
            return "Error: No Table Type Data";
    }

}

// For choosing to laod specific slect option boxes.
function pickUpdateRow($rowUpdate,$selected){
    switch ($rowUpdate) {
        case "terms":
        return createCatAndLevelSelect($selected[0]) ;
            break;
        case "categories":
        return createLevelSelect($selected[0]);
            break;
        case "schools":
             return "" ;
            break;
        case "classes":
        return createGradeLevelSelect($selected[0])." " . createSchoolsSelect($selected[1]);
        break;
        default:
            return "Error: No Table Type Data";
    }

}
function deleteCategories($type,$data){
    $where = array(0=>'name',1=> 'level');
    $what = array(0=>$data[0],1=> $data[1]);
        deleteFromTable($type, $where,$what ,2);
    
}
function deleteTerms($type,$data){

    $level =  trim(substr($data[0], -1));                        // category is first of the split string
    $category = trim(substr($data[0], 0, -2));
    $catID = matchCatID($category, $level);
    $where = array(0=>'name',1=> 'definition',2=> 'catID');
    $what = array(0=>$data[1],1=> $data[2],2=> $catID);

        deleteFromTable($type, $where, $what ,3);
}
function deleteSchools($type,$data){
    $where = array(0=>'name');
    $what = array(0=>$data[0]);

        deleteFromTable($type, $where, $what ,1);
    
}
function deleteClasses($type,$data){
    $schoolID =  matchSchoolName(trim($data[2]));     
    $where = array(0=>'name',1=> 'gradeLevel',2=> 'schoolID');
    $what = array(0=>$data[0],1=> $data[1],2=> $schoolID);

        deleteFromTable($type, $where, $what ,3);
    
}


function saveToDB($type,$newinfo,$oldinfo){
    switch ($type) {
        case "terms":
         return saveTerms($newinfo,$oldinfo);
            break;
        case "categories":
        return saveCategories($newinfo,$oldinfo);
            break;
        case "schools":
        return saveSchools($newinfo,$oldinfo);
            break;
        case "classes":
        return saveClasses($newinfo,$oldinfo);
        break;
        default:
            return "Error: No Save Type Data";
}
}

///save terms: ID,Category and Level ,Term, definition
function saveTerms($newData, $oldData){
 $string ="";   
for ($i=0; $i < count($newData); $i++) { 
         $newlevel =  trim($newData[$i]["gradeLevel"]);        
        $newcategory = trim($newData[$i]["category"]);   // category is first of the split string
        $oldlevel =  trim($oldData[$i]["gradeLevel"]);        
        $oldcategory = trim($oldData[$i]["category"]);
        $newcatID = matchCatID($newcategory, $newlevel); 
        $oldcatID= matchCatID($oldcategory, $oldlevel);
        $where = array(0=>'name',1=> 'definition',2=> 'catID');
        $what = array(0=>$oldData[$i]["word"],1=> $oldData[$i]["definition"],2=> $oldcatID);
   // If the entry is marked in order to detect changes 
   if ($newData[$i]["checked"]) {
     // if changes were made we check if the old data exists if it does we update the db to the new data where the old data was 
    if (checkIfInfoExists("terms",$where, $what ,3)){
     $string.= updateTerms($newData[$i]["word"],$newData[$i]["definition"],$newcatID,$oldData[$i]["word"],$oldData[$i]["definition"],$oldcatID);
    } else $string.= insertTermsIntoDB($newData[$i]["word"],$newData[$i]["definition"],$newcatID);
}else{ // if changes aren't marked then we check if it is marked as new entry
    if ($newData[$i]["newCheck"]){
        if(checkCustom("terms","name", $newData[$i]["word"])){
            $string .=  $newData[$i]["word"] ." already exists in database";
         } else{
        $string.= insertTermsIntoDB($newData[$i]["word"],$newData[$i]["definition"],$newcatID);
       } 
    }
}

}
  
return $string;
}

function saveCategories($newData, $oldData){
    $string ="";   
    for ($i=0; $i < count($newData); $i++) {
        $where = array(0=>'name',1=> 'level');
        $what = array(0=>$oldData[$i]["catName"],1=> $oldData[$i]["level"]); // use data before change to find it in DB
        if($newData[$i]["checked"]){  // see if changees whree made from data sent
       // if changes were made we check if the old data exists if it does we update the db to the new data where the old data was 
        if ((checkIfInfoExists("categories",$where,$what,2))){ 
         $string.= updateCategories($newData[$i]["catName"],$newData[$i]["level"],$oldData[$i]["catName"],$oldData[$i]["level"]);
        } else $string.= insertCategoriesIntoDB($newData[$i]['catName'],$newData[$i]["level"]); // else we insert data into db if it doesn't exist
      }  else{ // if changes aren't marked then we check if it is marked as new entry
        if ($newData[$i]["newCheck"]){
             $string.= insertCategoriesIntoDB($newData[$i]['catName'],$newData[$i]["level"]);
            } 
    }  
}  
      
    return $string;
    
}

function saveSchools($newData, $oldData){
    $string ="";   
    for ($i=0; $i < count($newData); $i++) { 
        $where = array(0=>'name');
        $what = array(0=>$oldData[$i]["schoolName"]);
       // If the ID  exist then we need to detect changes 
       if ($newData[$i]["checked"] ) {
       // see if changees whree made from data sent
       // if changes were made we check if the old data exists if it does we update the db to the new data where the old data was
        if (checkIfInfoExists("schools",$where, $what, 1)){
         $string.= updateSchools($newData[$i]["schoolName"],$oldData[$i]['schoolName']);
        } else $string.= insertSchoolIntoDB($newData[$i]['schoolName']);
    }else{  // if changes aren't marked then we check if it is marked as new entry
        if ($newData[$i]["newCheck"]){
        if(checkCustom("schools","name", $newData[$i]["schoolName"])){
            $string .=  $newData[$i]["schoolName"] ." already exists in database";
         } else{
              $string.= insertSchoolIntoDB($newData[$i]['schoolName']);
            }
        }
    }
    
    }
      
    return $string;
}

function saveClasses($newData,$oldData){
    $string ="";  
for ($i=0; $i < count($newData); $i++) { 
         $newschoolID =  matchSchoolName(trim($newData[$i]["schoolName"])); 
         $oldschoolID =  matchSchoolName(trim($oldData[$i]["schoolName"]));                        // category is first of the split string
         $where = array(0=>'name',1=> 'gradeLevel',2=> 'schoolID');
         $what = array(0=>$oldData[$i]["className"],1=> $oldData[$i]["gradeLevel"],2=> $oldschoolID);
         if($newData[$i]["checked"]){  
             // see if changees whree made from data sent
       // if changes were made we check if the old data exists if it does we update the db to the new data where the old data was
    if (checkIfInfoExists("classes",$where, $what,3)){
     $string.= updateClasses($newData[$i]["className"],$newData[$i]["gradeLevel"],$newschoolID,$oldData[$i]["className"],$oldData[$i]["gradeLevel"],$oldschoolID);
    }else $string.= insertClassIntoDB($newData[$i]["className"],$newData[$i]["gradeLevel"],$newschoolID);
}else{ // if changes aren't marked then we check if it is marked as new entry
    if($newData[$i]["newCheck"]){
    if(checkCustom("classes","name", $newData[$i]["className"])){
        $string .=  $newData[$i]["className"] ." already exists in database";
     } else{
          $string.= insertClassIntoDB($newData[$i]["className"],$newData[$i]["gradeLevel"],$newschoolID);
        }
    }
}
}
return $string;

}

function addWordsToTable(){
   $select = array();
   $sortBy = $_POST['sortBy'];
    if(isset($_POST['choice'])&& strcmp($_POST['choice'],"All")!=0){
        $level =  trim(substr($_POST['choice'], -1));                        // category is first of the split string
        $category = trim(substr($_POST['choice'], 0, -2));
        $catID = matchCatID($category, $level);
        $words =getTermsDataSpecial($catID,$sortBy);
    } else {$words = getTermsData($sortBy);}
      
   foreach ($words as $index) { 
    $unifiedString = "".$index["category"]. " ".$index["gradeLevel"];
        array_push($select, createCatAndLevelSelect($unifiedString));
       }
        unset($index);
  return array(0=>$words,1=>$select);
}

function createCatAndLevelSelect($selected){

    $selectstring= array();
    $selectVal =array();
    $htmlIDName = "selcat";
    $titleOption = "--Select Category/Level--";
    $categories = getCategoriesData("ID");
    $count = 0;
    foreach ($categories as $value) {
        $selectstring[$count]= "".$value["catName"]." - Level ".$value["level"];
        $selectVal[$count]= "".trim($value["catName"]). " ".trim($value["level"]);
        $count = $count+1;
    }
       unset($value);
    return createHTMLSelect($selectstring,$selectVal,$htmlIDName,$titleOption,$selected);
    
}

function createLevelSelect($selected){
    $htmlIDName = "sellev";
    $titleOption = "--Select Level--";
    $levelID =array(3,4,5,6,7);
    $levelname = array("Level 3", "Level 4", "Level 5", "Level 6","Level 7");
    return createHTMLSelect($levelname,$levelID,$htmlIDName,$titleOption,$selected);

}

function createGradeLevelSelect($selected){
    $htmlIDName = "sellev";
    $titleOption = "--Select Level--";
    $levelID =array(3,4,5,6,7);
    $levelname = array("Grade 3", "Grade 4", "Grade 5", "Grade 6","Grade 7");
    return createHTMLSelect($levelname,$levelID,$htmlIDName,$titleOption,$selected);

}

// get schools select
function createSchoolsSelect($selected){
    
    $selectstring = array();
    $schools = getSchoolData("ID");
    $htmlIDNameSch = "selschool";
    $titleOptionSch = "--Select School--";
    $count = 0;
    foreach ($schools as $value) {
        $selectstring[$count]= "".$value["schoolName"];
        $count++;
    }
    unset($value);
    $selectSch =createHTMLSelect($selectstring,$selectstring,$htmlIDNameSch,$titleOptionSch,$selected);
   
    return $selectSch;
}

function addCategoriesToTable(){
    $sortBy = $_POST['sortBy'];
    $select = array();
    $categories = getCategoriesData($sortBy);
    foreach ($categories as $index) { 
       array_push($select,  createLevelSelect($index['level']));
        }
        unset($index);
  return array(0=>$categories,1=>$select);

}

function addSchoolsToTable(){
    $sortBy = $_POST['sortBy'];
    $schools= getSchoolData($sortBy);
    
  return array(0=>$schools,1=>null);

}

function addClassesToTable(){
    $sortBy = $_POST['sortBy'];
    $select = array();
    $classes = getClassData($sortBy);
    $unifiedString = "";
    foreach ($classes as $index) { 
        $unifiedString = createGradeLevelSelect($index['gradeLevel'] )."  ". createSchoolsSelect($index['schoolName']);
        array_push($select, $unifiedString );
        }
        unset($index);
  return array(0=>$classes,1=>$select);

}


// set what dropdwon options are available in the hambureger menu
function importExport($choice){
    switch ($choice) {
        case "terms":
        return 
        '<a class="btn btn-secondary btn-sm" id="importSelect" href="import.php">Import Terms</a>
        <a class="btn btn-secondary btn-sm"  id="exportSelect" href="export.php">Export Terms</a>';
            break;
        case "categories":
        return ' <a class="btn btn-secondary btn-sm" id="importSelect" href="import.php">Import Categories</a>
        <a class="btn btn-secondary btn-sm"  id="exportSelect" href="export.php">Export Categories</a>';
            break;
        case "schools":
            return '<a class="btn btn-secondary btn-sm" id="schoolImportSelect" href="#">Import School List</a>
            <a class="btn btn-secondary btn-sm"  id="schoolExportSelect" href="#">Export School List</a>';
            break;
        case "classes":
           return  '<a class="btn btn-secondary btn-sm" id="classImportSelect" href="#">Import Class List</a>
           <a class="btn btn-secondary btn-sm"  id="classExportSelect" href="#">Export Class List</a>';
            break;
        default:
            return "";
    }
   
}

// pics which table headings 
function tableHeadings($choice){

    switch ($choice) {
        case "terms":
        return ' <tr>
        <th id="sort_1">Category And Grade Level</th>
        <th id="sort_2">Word</th>
        <th id="sort_3">Definition</th>
        <th class="col-sm-auto">
            </th>
            </tr>';
            break;

        case "categories":
        return '  <tr>
      
                <th id="sort_1">Category Name</th>
                <th id="sort_2"> Grade Level</th>
                <th class="col-sm-auto"></th>
              </tr>';
            break;

        case "schools":
            return '<tr>
        
            <th id="sort_1">School Name</th>
            <th class="col-sm-auto"> </th>
            </tr>';
            break;

        case "classes":
           return  '<tr>
          
           <th id="sort_1">Class Name</th>
           <th id="sort_2">Grade Level</th>
           <th id="sort_3">School</th>
           <th class="col-sm-auto"> </th>
            </tr>';
            break;
        default:
            return "";
    }

}



function helpMessage($choice){

    switch ($choice) {
        case "terms":
        return 'Add New Terms by Clicking Add Terms Button.';
            break;
        case "categories":
        return ' Add New Categories by Clicking Add Categories Button.';
            break;
        case "schools":
            return 'Add New School by Clicking Add School Button.';
            break;
        case "classes":
           return  'Add New Class by Clicking Add Class Button.';
            break;
        default:
            return "";
    }

}


//Four types categories,terms,schools, and classess
function createTable($title,$type){

    
   return  '<div class="table-responsive">
    <br><H2>'.$title.'</H2>
    <span class="Buttons">
      <button class = " btn btn-primary" id="addRow2" data-toggle="tooltip" 
      data-placement="right" title="'.helpMessage($type).'">Add '.ucfirst($type).'</button>
      <button class = " btn btn-primary" id="save2""data-toggle="tooltip" 
      data-placement="right" title="Save Changes by Clicking Save.">Save Changes</button>
      <button type="button" id="help" class="btn btn-sm btn-light" data-container="body" data-trigger="manual" 
      data-placement="right" data-content=\'"Add" button adds a new table row to the top.
       There is also an "Add" button add the bottom of the table that adds a new table row to the bottom.
      Click "Save" to save all new changes. Saves Current Tab only. Switch Tabs to Save Data under other Tabs\'
      ><img src ="img/helpicon.png" width="25"> </button>
      &nbsp; &nbsp; &nbsp; &nbsp;
    <div class="btn-group float-right" role="group" aria-label="Basic example">' .importExport($type).'</div>
    </span>
    <div id="sortsearch"> <span><label>Size:</label> <select class="input-small" id="sortSize">
    <option value="10">10</option>
    <option value="50">50</option>
    <option value="100">100</option>
    <option value="250">250</option>
  </select> <span class ="float-right"><label>'.ucfirst($type).' Search:</label> <input id="wordsearch" "type="text" placeholder="Search..">
  <button class = " btn btn-sm btn-primary" id="searchGo">Search</button>
  </span>
  </span>
  </div>
    <small  class="form-text text-muted">Click Table Cells To Edit. Click Titles To Sort </small>
    <table id="word_table" class="table table-striped table-bordered">
      <thead style="cursor: pointer;">
       '.tableHeadings($type).'
      </thead>
      <tbody id ="t_body_'.$type.'">
      </tbody>
      
    </table>
    <nav aria-label="...">
    <ul id="pages" class="pagination justify-content-center">
      <li class="page-item">
        <a class="page-link" href="#" tabindex="-1">Previous</a>
      </li>
      <li class="page-item"><a class="page-link" href="#">1</a></li>
      <li class="page-item">
        <a class="page-link" href="#">Next</a>
      </li>
    </ul>
  </nav>
  <br>
    <p class="Buttons">
      <button class = " btn btn-primary" id="addRow1">Add '.ucfirst($type).'</button>
      <button class = " btn btn-primary" id="save1">Save Changes</button>
    </p>
    
  </div>';


}

 

// this creates and html select item
// used alot in the tables we create
// takes one to many options data, the name that the id html attribute needs
// and the first default tilte ie category, or level etc
function createHTMLSelect($optionContent,$optionID,$idName,$selectTitle,$selected){

    $selectString = "<td><select class=\"form-control\" 
    id=\"$idName\"><<option value = \"0\" selected disabled> $selectTitle</option>";
   $count = 0;
    foreach ($optionContent as $value) {
        if(isset($selected) && ((strcasecmp($selected,$optionID[$count])== 0)|| ($selected == $optionID[$count]))){
            $selectString.= "<option value = \"".$optionID[$count]."\" selected>".$value."</option>";
            $count = $count+1;
        }else {
        $selectString.= "<option value = \"".$optionID[$count]."\">".$value."</option>";
         $count = $count+1;
        }
    }
    unset($value);
    $selectString.= "</select></td>";

    return $selectString;

}

function checkEmptyUpdateString($string){

    return($string === '');

}


?>