<?php

  require_once($_SERVER['DOCUMENT_ROOT'].'/dev/inc/php/dbConnect.inc.php');
  require_once('inc/util.php');
  require_once('inc/mainPHP.php');
 

  $db = dbConnect('erfamily');


  $type = $_REQUEST['type'];
  $btype = $_REQUEST['btype'];
  $totalMsgs = $_REQUEST['numOfMsg'];
  
  $limitNum = $g_numOfMsg + 1;
  $sql = "SELECT * FROM er_newboard_main WHERE type = '$btype' ORDER BY id DESC LIMIT $totalMsgs , $limitNum";
  $result = $db->query($sql);
  
  if($type == 1) {
  // return this is last part or not.
  $numStrories = $result->num_rows;
  
    if( $numStrories <= $g_numOfMsg)  echo false;
    else  echo true;
    
  }else if ($type == 2) {
    if ($result->num_rows > 0) {
      $rCount = 0;
      while( $mRow = $result->fetch_assoc( ) ) {
          
        if ($rCount == $g_numOfMsg) break;
        $rCount++;
        
        printMSG($mRow, true, $db); 
        
      }
    }
  }
  

?>