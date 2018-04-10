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

  $data = json_decode(stripslashes($_POST['info']),true);
  $type = $_POST['what'];
  
   $finished = pickSaveDB($type,$data);
   if(checkEmptyUpdateString($finished))
  {
      echo "No Updates";
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
        return ;
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


function pickSaveDB($type,$info){
    switch ($type) {
        case "terms":
         return saveTerms($info);
            break;
        case "categories":
        return saveCategories($info);
            break;
        case "schools":
        return saveSchools($info);
            break;
        case "classes":
        return saveClasses($info);
        break;
        default:
            return "Error: No Save Type Data";
}
}

///save terms: ID,Category and Level ,Term, definition
function saveTerms($data){
 $string ="";   
for ($i=0; $i < count($data); $i++) { 
         $level =  trim($data[$i]["gradeLevel"]);        
        $category = trim($data[$i]["category"]);   // category is first of the split string
        $catID = matchCatID($category, $level);
        $where = array(0=>'name',1=> 'definition',2=> 'catID');
        $what = array(0=>$data[$i]["word"],1=> $data[$i]["definition"],2=> $catID);
   // If the entry is marked in order to detect changes 
   if ($data[$i]["checked"] ) {
    // we check to see if anthing in the tables. if one thing
    // has changed then this will return false we update other wise do nothing.
    if (!(checkIfInfoExists("terms",$where, $what ,3))){
    
     $string.= updateTerms($data[$i]["word"],$data[$i]["definition"],$catID);
    } else $string.= "Did not save changes to" .$data[$i]["catName"]." ".$data[$i]["level"].". It already exists";
}else{ // if ID doensn't exist then we have a new table entry thsu insert
    // check if definition exists.
    if(checkCustom("terms","definition", $data[$i]["definition"])){
        $string .=  $data[$i]["definition"] ." already exists";
     } else{
          $string.= insertTermsIntoDB($data[$i]["word"],$data[$i]["definition"],$catID);
        }
}

}
  
return $string;
}

function saveCategories($data){
    $string ="";   
    for ($i=0; $i < count($data); $i++) {
        $where = array(0=>'name',1=> 'level');
        $what = array(0=>$data[$i]["catName"],1=> $data[$i]["level"]);
        if($data[$i]["checked"]){  // see if changees to exists
       // we check to see if anthing in the tables. if one thing
        // has changed then this will return false we update other wise do nothing.
       
        if (!(checkIfInfoExists("categories",$where,$what,2))){
         $string.= updateCategories($data[$i]["catName"],$data[$i]["level"]);
        } else $string.= "Did not save changes to" .$data[$i]["catName"]." ".$data[$i]["level"].". It already exists";
      }  else{ // if name or category doesnt exist then we have a new table entry thsu insert
        if (!(checkIfInfoExists("categories",$where,$what,2))){
             $string.= insertCategoriesIntoDB($data[$i]['catName'],$data[$i]["level"]);
            }
    }
    
}  
      
    return $string;
    
}

function saveSchools($data){
    $string ="";   
    for ($i=0; $i < count($data); $i++) { 
        $where = array(0=>'name');
        $what = array(0=>$data[$i]["schoolName"]);
       // If the ID  exist then we need to detect changes 
       if ($data[$i]["checked"] ) {
        // we check to see if anthing in the tables. if one thing
        // has changed then this will return false we update other wise do nothing.
        if (checkIfInfoExists("schools",$where, $what, 1)){
        
         $string.= updateSchools($data[$i]["schoolName"]);
        } else $string.= "Did not save changes to" .$data[$i]["schoolName"].". It already exists";
    }else{ // if ID doensn't exist then we have a new table entry thsu insert
        // check if definition exists.
        if(checkCustom("schools","name", $data[$i]["schoolName"])){
            $string .=  $data[$i]["schoolName"] ." already exists";
         } else{
              $string.= insertSchoolIntoDB($data[$i]['schoolName']);
            }
    }
    
    }
      
    return $string;
}

function saveClasses($data){

    $string ="";   
for ($i=0; $i < count($data); $i++) { 
         $schoolID =  matchSchoolName(trim($data[$i]["schoolName"]));                        // category is first of the split string
         $where = array(0=>'name',1=> 'gradeLevel',2=> 'schoolID');
         $what = array(0=>$data[$i]["className"],1=> $data[$i]["gradeLevel"],2=> $schoolID);
         if($data[$i]["checked"]){  // see if changees to exists
   // If the changes  exist then we need to detect id changed for db 
    // we check to see if anthing in the tables. if one thing
    // has changed then this will return false we update other wise do nothing.
    if (!(checkIfInfoExists("classes",$where, $what,3))){
     $string.= updateClasses($data[$i]["className"],$data[$i]["gradeLevel"],$schoolID);
    }else $string.= "Did not save changes to" .$data[$i]["schoolName"].". It already exists";
}else{ // if name doensn't exist then we have a new table entry thsu insert
    // check if definition exists.
    if(checkCustom("classes","name", $data[$i]["className"])){
        $string .=  $data[$i]["className"] ." already exists";
     } else{
          $string.= insertClassIntoDB($data[$i]["className"],$data[$i]["gradeLevel"],$schoolID);
        }
}
return $string;
}
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