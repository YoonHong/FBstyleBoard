function getNumOfMsgs() {
  return $g_MAIN_MSG_LIST.children(".msgContent").length;
}

function showToolTip( $pDiv , msg ){
  var tDiv = '<div class="tooltip"><div class="tooltipMsg"><span>' + msg + '</span></div> <div class="tooltipBottom"></div> </div>';
  
  $pDiv.append(tDiv);
  
  var pHeight = $pDiv.height();
  
  $('.tooltip')
    .css('bottom', pHeight)
    .show();

}

function removeToolTip( ){
  $('.tooltip').remove();
}


function showDeletePopupWindow(type, $img) {
  
  if ( type == "comment" ) {
    var title = "Delete Comment";
    var msg = "Are you sure you want to delete this comment?";

    $g_delDiv = $this.parents('.commentItem');
    g_PID = $this.siblings('.commentID').val();
    g_PW_Type = type;
      
  } else if ( type == "msg" ) {
    $g_delDiv = $img.parents('.msgContent');
    g_PID = $g_delDiv.attr('id');
    g_PW_Type = type;
       
    var title = "Delete Post";
    var msg = "Are you sure you want to delete this?";
  }
  
  $delWindow = $g_PW_DEL;
  
  $delWindow.find('.PW_title').text(title);
  $delWindow.find('.PW_msg').text(msg);
  
  $delWindow.find('.PW_errorMsg').hide();
  //$delWindow.find('.inputtext').val("");
  $delWindow.find('.PW_progress').hide();  
    
  $delWindow.fadeIn( function () {
    $delWindow.find('.inputtext').val("").focus();
  });
}

function closeDeletePopupWindow() {
  $g_PW_DEL.find('.inputtext').val("")
  $g_PW_DEL.fadeOut();
}


function showAlertPopupWindow( msg ) {
  $g_PW_ALERT.find('.PW_title').text("Alert");
  $g_PW_ALERT.find('.PW_msg').text(msg);
  
  $g_PW_ALERT.fadeIn();
}

function closeAlertPopupWindow() {
  $g_PW_ALERT.fadeOut();
}

function PWDelBT_ClickEventOn(  ) {
  $('#PW_Del_BT').click(function() {
    $this = $(this);  
    
    $pw_input = $('#PW_DEL_Input');  
    $pw_err_msg = $('#PW_Err_Msg');
    
    //var pw = encodeURIComponent($.trim($('.mainPopupWindow .inputtext').val()));
    var pw = $pw_input.val();
    
    if(!pw){
    // Click button without password
    
      $pw_err_msg.show();
      $pw_input.focus();
      
      return false; 
    }
         
    var url = "delItems.php";
    var sData = {id : g_PID, pw : pw, type : g_PW_Type};
    
    
    $pw_progress_img = $('#PW_Progress_Img');
    
    $pw_progress_img.show();
 
    $this.off('click');
 
    $.post(url, sData).always( function(data, textStatus) {  
      
      $pw_progress_img.hide();
      PWDelBT_ClickEventOn();


      if (textStatus == "success" ) {
        // Success 
        //var data = xhr.responseText;

        if(data == "0") {
        // Correct Password
           $g_delDiv.remove();
           closeDeletePopupWindow();
            
        } else if (data == "Err1") {
          // Wrong Password
          $g_PW_DEL.find('.PW_errorMsg').show();
          $g_PW_DEL.find('.inputtext').val("").focus();
          
        } else if (data == "Err2") {
          // System Err
          closeDeletePopupWindow();
          showAlertPopupWindow( "Unable to delete comment. Try Again!" );
        }
      
      } else {
        // Fail
        closeDeletePopupWindow();
        showAlertPopupWindow( "Unable to delete comment. Try Again!" );
      }
      
    });
 
        
  });  // $('.mainPopupWindow .UICommonButton').click  
}

function utilLib() {

  /*
   *   Click Events
   */ 
  
  // Click Delete Button of Popup Window   
  PWDelBT_ClickEventOn();
  

  // Click Cancel Button of Popup Window 
  $('.mainPopupWindow .UICancelButton').click(function() {
    closeDeletePopupWindow();
  });
  

  // Click 
  $('.alertPopupWindow .UICommonButton').click(function() {
    closeAlertPopupWindow();
  });

}

