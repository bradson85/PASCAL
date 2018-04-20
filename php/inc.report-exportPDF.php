<?php
session_start();
require("pdfapi/mc_table.php");
//header('Content-type: text/plain');
$dataChoice =  $_POST['data']; // this is  an object
$dataChoice = str_replace("\'", "'", $dataChoice);
$dataChoice = str_replace("'+String.fromCharCode(34)+'", '"' , $dataChoice);
$dataChoice = json_decode($dataChoice,true);
//print_r($dataChoice);


// this turns associative array into indexed array
$newArray =array();
$newInnerArray =array();
$reorgArray = array();
$finalArray = array();
$titleData = array();
$data = array();
$divisioncount = 0;

foreach ($dataChoice as $key => $value) {
$split = array_chunk($dataChoice[$key],6);
    foreach($split as $key1=> $fix){
      $divisioncount = max($divisioncount,$key1);
      if($key1==0){
        $titleData[] = $fix[0]; // grab titles
      }
       $amountneeded = (5 - sizeof($fix)); // there six tables plus adding title lines
       if($amountneeded >0){
         $arr = array_fill(sizeof($fix),$amountneeded, " ");
        $result =  array_merge($split[$key1], $arr);
        if(sizeof($split)>1){
          array_unshift($result,$titleData[$key]);
        }
        $split[$key1] = $result;
       }
    }unset($fix);
    array_push($reorgArray, $split);
} unset($value);



for ($i=0; $i <= $divisioncount; $i++) { 
  for ($j=0; $j <sizeof($reorgArray); $j++) { 
      $finalArray[] = $reorgArray[$j][$i];
  }
}
//nedd ot reorgonize data
//print_r($finalArray);

//print_r($data);
$pdf = new PDF('P','pt');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',12);
$pdf->MultiCell(0,20,'Report Results');
$pdf->SetFont('Arial','',10);
$pdf->tablewidths = array(90, 90, 90, 90, 90, 90);

$pdf->morepagestable($finalArray);


$pdf->Output();

?>