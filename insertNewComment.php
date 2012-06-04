<?php

  require_once($_SERVER['DOCUMENT_ROOT'].'/dev/inc/php/dbConnect.inc.php');
  require_once('inc/util.php');
  
  $db = dbConnect('erfamily');
  
  $vName = $_REQUEST['commentFormName'];
  $vContent = $_REQUEST['commentMSG'];
  $vPW = $_REQUEST['commentFormPW'];
  $mID = $_REQUEST['commentMID'];
 
  //$vContent = nl2br(htmlspecialchars($vContent));
  $vContent = htmlspecialchars($vContent);
  
  $sql = "INSERT INTO er_newboard_comment (name, pw, content, cDateTime, mid) 
              VALUES (\"$vName\", \"$vPW\", \"$vContent\", NOW(), $mID )"; 
 
 
  if ( ! $db->query( $sql ) ) {
    echo false;  
  } else {

    $id = $db->insert_id;
    
    $sql = "SELECT * FROM er_newboard_comment where id = $id";
    $result = $db->query($sql);
  
    $row = $result->fetch_assoc( );
    

    printComment( $row );

  }
  
  
?>