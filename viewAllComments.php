<?php

  require_once($_SERVER['DOCUMENT_ROOT'].'/dev/inc/php/dbConnect.inc.php');
  require_once('inc/util.php');
  
  $db = dbConnect('erfamily');
  
  $mid = $_REQUEST['mid'];
  
  $sql = "SELECT * FROM er_newboard_comment WHERE mid = $mid ORDER BY id ASC";
  
  $result = $db->query($sql); 
              
  while( $row = $result->fetch_assoc( ) ) {
     printComment($row); 
  }
  
?>