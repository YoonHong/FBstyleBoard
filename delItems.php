<?php
  
  require_once($_SERVER['DOCUMENT_ROOT'].'/dev/inc/php/dbConnect.inc.php');
  
  $db = dbConnect('erfamily');
  
  $id = $_REQUEST['id'];
  $pw = $_REQUEST['pw'];
  $type = $_REQUEST['type'];
  
  $numOfImgs = 0;
  
  if ($type == "msg") {
    $sqlTable = "er_newboard_main";
  } else if ($type == "comment") {
    $sqlTable = "er_newboard_comment";
  }
  
  
  // Check Password
  if( $pw != _getMasterPW()) {
    
    $sql = "SELECT id FROM $sqlTable WHERE pw = '$pw' and id = $id";
    $result = $db->query($sql);
  
    if ($result->num_rows <= 0) {
      // Wrong Password
      echo "Err1";
      
      $db->close();
      
      return;
    }
    
  }
  

  if ($type == "msg") {
  // Del the child comments
    $sql = "DELETE FROM er_newboard_comment WHERE mid = $id";
    $db->query( $sql );
    
    // Remove Images
    $sql = "SELECT numOfPics FROM $sqlTable WHERE id = $id";
    $result = $db->query($sql);
    $row = $result->fetch_assoc( );
    $numOfImgs = $row['numOfPics'];    
    
    if ($numOfImgs > 0) {
      
      for($i=1; $i <= $numOfImgs; $i++) {
        $imgDir = '/dev/data/boardImg/';
        $simgDir = '/dev/data/boardImg/s/';
        
        $imgName = $id."_".$i.".jpg";
        
        // Remove large img
        $imgPath = $_SERVER['DOCUMENT_ROOT'].$imgDir.$imgName;
        if(file_exists($imgPath)) unlink($imgPath);

        // Remove small img
        $imgPath = $_SERVER['DOCUMENT_ROOT'].$simgDir.$imgName;
        if(file_exists($imgPath)) unlink($imgPath);
      }
    }
          
  }
  
  $sql = "DELETE FROM $sqlTable WHERE id = $id";
  
  if ( ! $db->query( $sql ) ) {
    echo "Err2";
      
    $db->close();
      
    return;
  }
 
  
  echo "0";
 

?>