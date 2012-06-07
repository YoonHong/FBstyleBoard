<?php

  require_once($_SERVER['DOCUMENT_ROOT'].'/dev/inc/php/dbConnect.inc.php');

  function checkImgType( $type ){
    $rightImgTypes = array ( 'image/gif' , 'image/jpeg' , 'image/pjpeg' , 'image/png' ); 
    
    return true;
    
    if (in_array($type, $rightImgTypes)){
      return true;
    } else {
      return false;
    }
  }
  
  function goBackToMain( $type ){
    header("Location: mainView.php?type=$type");
  }

  $db = dbConnect('erfamily');
  
  $maxSize = 10485760;

  $vName = $_REQUEST['postName'];
  $btype = $_REQUEST['btype'];
  $vContent = $_REQUEST['postMSG'];
  $vPW = $_REQUEST['postPW'];
  
  // Get number of Photos
  $numOfPics = 0;
  foreach($_FILES as $img) {
    if( $img["error"] == UPLOAD_ERR_OK && $img["size"] < $maxSize && checkImgType($img["type"])) {
      $numOfPics++;     
    } 
  }
  
  
  if($numOfPics == 0) {
    // No Picture
    goBackToMain($btype);
    exit;
  }
  
  
  $vContent = htmlspecialchars($vContent);
  $sql = "INSERT INTO er_newboard_main (name, pw, content, bDateTime, numOfPics, type)"
    ." VALUES (\"$vName\", \"$vPW\", \"$vContent\", NOW(), $numOfPics, \"$btype\" )"; 

  if ( ! $db->query( $sql ) ) {
    goBackToMain($btype);
    exit;
  }
  
  $id = $db->insert_id;
  
  $imgID = 0;
  // Move tempImgs to Storage.
  foreach($_FILES as $img) {
    if( $img["error"] == UPLOAD_ERR_OK ) {
      $imgID++;
      
      $imgDir = '/dev/data/boardImg/';
      $simgDir = '/dev/data/boardImg/s/';

      //$ext = strrchr( $img['name'], ".");
      $imgName = $id."_".$imgID.".jpg";        
 
      $sImgPath = $_SERVER['DOCUMENT_ROOT'].$simgDir.$imgName;
      $imgPath = $_SERVER['DOCUMENT_ROOT'].$imgDir.$imgName;
        
      // Convert to Thumb Nail Img
      $details = getimagesize($img['tmp_name']);
      
      $originalWidth = $details[0];
      $originalHeight = $details[1];
      
      $smallWidth = $originalWidth;
      $smallHeight = $originalHeight;
          
      $max_dimension = 320;
      if( $originalWidth > $originalHeight) {
        if ($originalWidth > $max_dimension){
          $ration = $max_dimension / $originalWidth;
          $smallWidth = $max_dimension;
          $smallHeight = round( $originalHeight * $ration );
        }
      } else {
        // Height is longer than Width
        if ($originalHeight > $max_dimension){
          $ration = $max_dimension / $originalHeight;
          $smallHeight = $max_dimension;
          $smallWidth = round( $originalWidth * $ration );
        }       
        
      } 
      
      $largeWidth = $originalWidth;
      $largeHeight = $originalHeight;
      
      $smallPic = imagecreatetruecolor($smallWidth, $smallHeight);
      $largePic = imagecreatetruecolor($largeWidth, $largeHeight);
      
      if($img["type"] == "image/gif"){
        // For GIF
        $org_resource = imagecreatefromgif($img['tmp_name']);       
      } else if($img["type"] == "image/png") {
        // For PNG
        $org_resource = imagecreatefrompng($img['tmp_name']);       
      } else {
        // For JPG
        $org_resource = imagecreatefromjpeg($img['tmp_name']);
      }
 
       // Copy for thumbnail
      imagecopyresampled($smallPic, $org_resource, 0, 0, 0, 0, $smallWidth, $smallHeight, $originalWidth, $originalHeight);
      // Copy for Large Img
      imagecopyresampled($largePic, $org_resource, 0, 0, 0, 0, $largeWidth, $largeHeight, $originalWidth, $originalHeight);
      
      imagejpeg($smallPic, $sImgPath, 90);
      imagejpeg($largePic, $imgPath, 60);
      
      imagedestroy($org_resource);     
      imagedestroy($smallPic);
      imagedestroy($largePic);
    }
  }
  
  goBackToMain($btype);
  exit;

// If there is error when file is being uploaded, remove DB.

?>