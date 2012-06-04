<?php



function seeMoreForMsg( $msg ){
  $split_word = '<br />';
  $max_line = 7;
  $view_line = 5;
  
  $msg =  nl2br($msg); 
  $numBR = substr_count( $msg , $split_word );
  
  $rMsg = $msg;
  
  if( $numBR > $max_line ) {
    // Add See More
    $tok = strtok($msg, $split_word);
    $count = 0;
    $rMsg = "";
    
    while ($tok !== false) {
      $count++;
      $rMsg .= $tok .$split_word;
      
      if( $count == $view_line ) {
        $rMsg .= "<span class='msg_exposed_hide'>...</span><span class='msg_exposed_show' style='display:none'>";
        
      }

      $tok = strtok($split_word);
    }
    
    $rMsg .= "</span><span class='msg_exposed_hide msg_exposed_link'><br />See More</span>";
  }
    
  return $rMsg;
  //return $msg;
}

function printMSG($row, $commentCheck, $db = null) {

  $name = $row['name'];
  $msg = $row['content'];
  $mid = $row['id'];
  $date = date("F d, Y \a\\t g:ia", strtotime($row['bDateTime']));
  
  $msg = seeMoreForMsg($msg);
  
  echo <<<_END
  
     <div class="msgContent" id='$mid'>
        
        <div class="msgCImg">
          <span style="width:50px">&nbsp;</span>
          <!-- <img src="img/tmp/c1.gif" /> -->
        </div>
        
        <div class="msgInnerContent">
          
          <div class="msgWriterName">$name</div>
          
          <div class="msgBody">$msg</div>
          
          <div class="msgFooter">
            <span class="commentLink">Comment</span> 
            <span class="dateTime"> · $date</span>
            <img class="commentProgressImg" src="img/progress.gif" style="float:right; display:none" />
          </div>
 
 
       <!-- Start : Comment Area -->          
        
          <div class="comment">
          
          <div class='commentItems' id='ct$mid'>
_END;

   // Comment
  $CP_dummy_display = "style='display:none'";
  $CP_status = 0; // 0: none  1: dummy  2: form
      
   if ( $commentCheck ) {
     
      $cSql = "SELECT COUNT(*) as count FROM er_newboard_comment WHERE mid = $mid";
      $cResult = $db->query($cSql);   
      $cRow = $cResult->fetch_assoc( );
      
      $numOfcomments = $cRow['count'];
      
      if ($numOfcomments > 0) {
          
        if ($numOfcomments > 3) {
          $offset = $numOfcomments - 2;
          $cSql = "SELECT * FROM er_newboard_comment WHERE mid = $mid ORDER BY id ASC LIMIT $offset, 2";
          
          echo <<<_END
          <div class="commentViewAll" style=" color:#3B5998">
            <img src="img/viewall.png" style="vertical-align:middle" /><span style="margin-left:5px;">View all $numOfcomments comments</span>
            <img src="img/progress.gif" class="commentViewAllProgressImg" style="display:none" />
            
          </div>
_END;
        } else { 
          $cSql = "SELECT * FROM er_newboard_comment WHERE mid = $mid ORDER BY id ASC";
        }
        
        $cResult = $db->query($cSql); 
              
        while( $cRow = $cResult->fetch_assoc( ) ) {
           printComment( $cRow); 
        }
        
        
      
        $CP_status = 1;
        $CP_dummy_display = "";
      } 
   
   }
    
   echo <<<_END
            
            </div> <!-- <div class='commentItems'> -->
            
            <div class="dummyCommentText" $CP_dummy_display >
              <input type="text" value="Write a comment..." class='inputtext' style="width:98%; color: gray" />
            </div>


            <div class="commentPost">
              
              <div class="mainCommentDV" >
                <div class="commentSelImg">
                &nbsp;
                  <!-- <img src="img/tmp/c1_small.gif" /> -->
                </div>
                  
                <div class="commentForm">
                  <form id="ctForm" class="ctForm" action="insertNewComment.php">
                    <div>
                      <textarea class="commentText" name="commentMSG" rows="1"></textarea>
                      <img class="commentRMForm" src="img/cdel.png" />
                      
                    </div>
                    
                    <div style="margin-top: 3px">
                      <input type='text' class='inputtext commentFormName' name='commentFormName'  size="20" maxlength="20" style="margin-right: 10px" />
                      <input type='text' class='inputtext commentFormPW' id="" name="commentFormPW"  size="10" maxlength="8" />
                      <input type='hidden' name='commentMID' class="commentMID" value='$mid' />
                      <input type='hidden' name='commentDivStatus' class="commentDivStatus" value='$CP_status' />
                     
                     
                      <!--<button class="UICommonButton" style="margin-left: 40px">Comment</button>-->
                      <input type='submit' value='Comment' class="UICommonButton" style="margin-left: 20px" />
                    </div>
                  </form>
                </div>
                             
                <div class="clearFloat"></div>
              </div>
            
            </div>  <!-- End : <div class="commentPost"> --> 
            
            
            
          </div>

<!-- End : Comment Area --> 
          
        </div>
 
        <div style="position: relative;float:right; "><img class="msgDelImg" style="display:none;" src="img/del2.png" />
        
        </div>
        
        <div class="clearFloat"></div>
      </div>
_END;

   
}


function printComment($cRow) {
  
  $cName = $cRow['name'];
  $cMsg = $cRow['content'];
  $cDate = date("F d, Y \a\\t g:ia", strtotime($cRow['cDateTime']));
  $cid = $cRow['id'];
  
  
  $cMsg = seeMoreForMsg($cMsg);
  
echo <<<_END
  <div class="commentItem">
    
    <div class="commentImg">
      &nbsp;
                  <!-- <img src="img/tmp/c1_small.gif" /> -->
    </div>
    
    <div class="commentVIew">
      
      <div class="commentContent">
        <span class="commentName">$cName</span>
         $cMsg
      </div>
      
      <div class="commentDate">
      $cDate
      </div>
      
    </div>
    
    <div class="commentDel">
      <img class="ctDImg" src="img/cdel.png" />
      <input type="hidden" class="commentID" value="$cid" />
    </div>  
    
    <div class="clearFloat"></div>            
  </div>
  
_END;


}  

  

 

?>