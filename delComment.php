<?php
  
  require_once($_SERVER['DOCUMENT_ROOT'].'/dev/inc/php/dbConnect.inc.php');

  $db = dbConnect('erfamily');
  
  $cid = $_REQUEST['cid'];
  $pw = $_REQUEST['pw'];
  
  
  // Check Password
  
  if( $pw != _getMasterPW()) {
    
    $sql = "SELECT id FROM er_newboard_comment WHERE pw = '$pw' and id = $cid";
    $result = $db->query($sql);
  
    if ($result->num_rows <= 0) {
      // Wrong Password
      echo "Err1";
      
      $db->close();
      
      return;
    }
  }
  
  $sql = "DELETE FROM er_newboard_comment WHERE id = $cid";
  
  if ( ! $db->query( $sql ) ) {
    echo "Err2";
      
    $db->close();
      
    return;
  }
 
  echo "0";
 

?>