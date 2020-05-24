<?php
  session_start();

// Put your PHP functions and modules here
$moviesObject = [  'ACT' => ['title' => 'Avengers: Endgame',    
                              'rating' => 'PG-13',
                              'description' => '<p>With the help of remaining allies ... ',
                              'screenings' => ['WED' => 'T21',
                                                'THU' => 'T21',
                                                'FRI' => 'T21',
                                                'SAT' => 'T18',
                                                'SUN' => 'T18'
                                                ]
                              ]
                ];
$pricesObject = [
  'full' => [
      'STA' => 19.8,
      'STP' => 17.5,
      'STC' => 15.3,
      'FCA' => 30.0,
      'FCP' => 27.0,
      'FCC' => 24.0
  ],
  'discount' => [
      'STA' => 14.0,
      'STP' => 12.5,
      'STC' => 11.0,
      'FCA' => 24.0,
      'FCP' => 22.50,
      'FCC' => 21.0
  ]
];
$daysDate = ['MON', 'TUE', 'WED', 'THU', 'FRI','SAT','SUN'];

//prints data and shape/structure of data
function preShow( $arr, $returnAsString=false ) {
  $ret  = '<pre>' . print_r($arr, true) . '</pre>';
  if ($returnAsString){
       return $ret;
  } else{ 
      echo $ret; 
    }
}

// Outputt current file source code
function printMyCode() {
  $lines = file($_SERVER['SCRIPT_FILENAME']);
  echo "<pre class='mycode'><ol>";
  foreach ($lines as $line)
         echo '<li>'.rtrim(htmlentities($line)).'</li>';
  echo '</ol></pre>';
}

//php multiple dimensional array to javascript object
function php2js( $arr, $arrName ) {

  $lineEnd="";

  echo "<script>\n";

  echo "/* Generated with A4's php2js() function */";

  echo "  var $arrName = ".json_encode($arr, JSON_PRETTY_PRINT);

  echo "</script>\n\n";

}

//'reset the session' submit button
if (isset($_POST['session-reset'])) {
  foreach($_SESSION as $something => &$whatever) {
       unset($whatever);
  }
}

// Validate form data 
function test_input(&$data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialChars($data);
  return $data;
}

// ----- Get day price -----
function getPrice($movieday, $movietime){
  global $daysDate, $pricesObject;
  if((array_search($movieday, $daysDate) <3 )|| 
    ((array_search($movieday, $daysDate) <5) && ($movietime == 'T12'))){
    return $pricesObject['discount'];
  }else{
    return $pricesObject['full'];
  }
}
// Validate total value
function calToltal ($seatarr, $movieday, $movietime){
  $total =0;
  $price = getPrice($movieday, $movietime);
  foreach ($seatarr as $seat => $seatQuan){
    $total += $seatQuan * $price[$seat];
  }
  return $total;
}

// Get date different 
function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' )
{
    $interval = date_diff($date_1, $date_2);
    return $interval->format($differenceFormat);
}

//Get seats
function printRow($seatarr,$movieday, $movietime){
  $price = getPrice($movieday, $movietime);
  foreach ($seatarr as $seat => $seatQuan){
    if (!empty($seatQuan)){
        
      
    }
}
}
?>
