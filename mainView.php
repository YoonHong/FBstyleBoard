<?php

	$isLocal = false;
	if($_SERVER['HTTP_HOST'] == 'localhost') {
		$isLocal = true;
	} 

	require_once($_SERVER['DOCUMENT_ROOT'].'/dev/inc/php/dbConnect.inc.php');
  
  require_once('inc/mainPHP.php');
  require_once('inc/util.php');
  
	$db = dbConnect('erfamily');
	
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title> ERFamily.com </title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  
  
  <link href="css/main.css" type="text/css" rel="stylesheet" />
  

  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>

  <script type="text/javascript" src="/dev/inc/js/jquery.elastic.source.js"></script>
  <script type="text/javascript" src="js/mainJS.js?05262012"></script>
  <script type="text/javascript" src="js/utilJS.js"></script>
  <script type="text/javascript" src="js/msgJS.js"></script>
  <script type="text/javascript" src="js/commentJS.js"></script>
  
<script type="text/javascript">
$(document).ready(function(){
   
  g_bType = "<?=  $g_bType ?>";

  initJS();

  utilLib();
  msgLib();
  commentLib();

     
});
</script>

</head>

<body>

<div id="container">  
  
  
  <div id='title'>
    <div style="margin: 3px 0 0 10px">
    <img src="img/title2.gif" />
    <img src="img/title3.gif" />
    </div>
  </div>
 
  <div id='mainView'>
    <div id='mainViewSub'>
      
    <div id='postView'>
      

      
      <div id='postAll'>
        <div id='postTitle'>
          
            <div class="lFloat"><img src="img/post.png" style="vertical-align:middle" / > <span style="font-weight: bold;">Write Post</span></div>
            <div class="rFloat progressImg" id="postProgressImg"><img src="img/progress.gif" /></div>
            <div class="clearFloat"></div>
        </div>
        
        <div id='postViewDummy'>
        
          <input type="text" id="postViewDummyText" value="Write something..." class='inputtext' style="width:532px; color: gray" />
        
        </div>
        <div id='postFormView' style="display: none;">
          <form action="insertNewPost.php" method="post" name="postForm" id="postForm">
          
          <div id='postText' class="clearFloat">
            <textarea id='postTextArea' name="postMSG" rows="3" ></textarea>
          </div>
          
          <div id='postImg' style="display:none;">
            
            <div id='postImglink' style="font-weight: bold; color: #3B5998">
              <img src="img/plus.png" /> Upload Pictures
            </div>
            
            <div id='postImgFiles' style="display:none;">
              <div style="font-weight: bold;">
                Select an image on your computer.
                <img src="img/del2.png" id="postImgClose" style="float: right" />
              </div>
              
              <input name="postImg1" type="file"  /> <br/>
              <input name="postImg2" type="file"  /> <br/>
              <input name="postImg3" type="file"  />
            </div>
          </div>
          
          
          <div id='postOption'>
            <label class="label" id="postFormLblName"> Name: </label>
            <input type='text' name='postName' class='inputtext' size="20" maxlength="20" />
            <label class="label" id="postFormLblPW" style="margin-left: 20px"> Password: </label>
            
            <input type='text' id="postPW" name="postPW"  class='inputtext' size="10" maxlength="8" />
            <input type='hidden' name="btype" value="<?=  $g_bType ?>" />
            
            <input type="submit" class="UICommonButton" value="Post"  style="padding-left: 20px; padding-right: 20px; float: right;"/>
          </div>
          
          </form>
        </div>
        
      </div>
      
    </div>
	 
    <div id="txtListView">	

<?php
  $limitNum = $g_numOfMsg + 1;
  $sql = "SELECT * FROM er_newboard_main WHERE type = '$g_bType' ORDER BY id DESC LIMIT 0 , $limitNum";
  $result = $db->query($sql);
  
  $numStrories = $result->num_rows;
  
  if ($result->num_rows > 0) {
    $rCount = 0;
    while( $mRow = $result->fetch_assoc( ) ) {
        
      if ($rCount == $g_numOfMsg) break;
      $rCount++;
     
      printMSG($mRow, true, $db);
      
    }
  }
  
  $moreStroyDisplay = "";
  $noMoreStroyDisplay = "";
  
  if ($numStrories > $g_numOfMsg) {
    $noMoreStroyDisplay = "style='display:none'";
  } else {
    $moreStroyDisplay = "style='display:none'";
  }
?>  
        
    </div>
    
    <div id="noMoreStory" <?= $noMoreStroyDisplay ?> >There are no more posts to show.</div>
    <div id="moreStory" <?= $moreStroyDisplay ?> ><span id="moreStroyMsg">More Stories ...</span> <img id="moreStroyProgressImg" src="img/progress.gif" style='display:none' /></div>
 
 </div> 
 
 </div> <!-- <div id='mainView'> -->   
 
 <div id="Footer">by Yoon ( yoon.s.hong@gmail.com )</div>
 
 
</div>


<div class="mainPopupWindow" id="mainDeleteWindow">
  <div class="PW_window">
    <div class="PW_container">
      
      <div class="PW_title"></div>
      
      <div class="PW_body">
        <div class="PW_msg"></div>
        <div class="PW_progress" id='PW_Progress_Img'>
          <img src="img/progress.gif" />
        </div>
        <div class="clearFloat"></div>
      </div>
      
      <div class="PW_footer"> 
        
        <div class="PW_passwordForm">
          <label class="label">
            Password:
          </label>
          <input type="text" class="inputtext" id="PW_DEL_Input" size="10" maxlength="10" />
          <label class="PW_errorMsg" id="PW_Err_Msg" style="font-weight: bold; color: #C66; display:none;">
            <img src="img/error1.png" style="vertical-align:middle" />
            Wrong password.
          </label>
        </div>
        
        <div class="PW_buttons">
          <button class="UICommonButton" id="PW_Del_BT">Delete</button>  
          <button class="UICancelButton" id="PW_Cancel_BT">Cancel</button>     
        </div>
      
        <div class="clearFloat"></div>
      </div>
      
    </div>
  </div>
</div>


<div class="alertPopupWindow" id='mainAlertWindow'>
  <div class="PW_window">
    <div class="PW_container">
      
      <div class="PW_title"></div>
      
      <div class="PW_body">
        <div class="PW_msg"></div>
      </div>
      
      <div class="PW_footer"> 
       
        
          <button class="UICommonButton">Close</button>     
    
      
        <div class="clearFloat"></div>
      </div>
      
    </div>
  </div>
</div>



</body>
</html>
