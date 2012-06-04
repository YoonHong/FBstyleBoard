<?php

  require_once($_SERVER['DOCUMENT_ROOT'].'/dev/inc/php/dbConnect.inc.php');
  require_once('inc/util.php');
  
  $db = dbConnect('erfamily');
  
  $vName = $_REQUEST['postName'];
  $btype = $_REQUEST['btype'];
  $vContent = $_REQUEST['postMSG'];
  $vPW = $_REQUEST['postPW'];
  
  //$vContent = nl2br(htmlspecialchars($vContent));
  $vContent = htmlspecialchars($vContent);
  
  $sql = "INSERT INTO er_newboard_main (name, pw, content, bDateTime, type) VALUES (\"$vName\", \"$vPW\", \"$vContent\", NOW(), \"$btype\" )"; 
 
 
  if ( ! $db->query( $sql ) ) {
    echo false;  
  } else {
    
    $id = $db->insert_id;
    
    $sql = "SELECT * FROM er_newboard_main where id = $id";
    $result = $db->query($sql);
  
    $row = $result->fetch_assoc( );
    
    printMSG($row, false, $db);
 
  }
  
  
?>