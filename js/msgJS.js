
function moreStoryMouseEventOn() { 

  $g_More_Story.on ( {
      mouseenter: function (evt) { 
        $(this).css({"background-color":"#D8DFEA" , "cursor":"pointer", "text-decoration" : "underline"});
      },
      mouseleave: function (evt) {
        $(this).css({"background-color":"#EDEFF4" , "cursor":"default", "text-decoration" : "none"});
      }
  }); 
}

function moreStoryClickEventOn(  ) {
  $g_More_Story.on ( 'click', function () {
    $this = $(this);
    
    $('#moreStroyProgressImg').show();
    $('#moreStroyMsg').hide();
    $this.css({"background-color":"#EDEFF4" , "cursor":"default", "text-align" : "center"});
    $this.off('click mouseenter mouseleave');
    
    var url = "moreStory.php";
    
    var sData = {type:1, numOfMsg : getNumOfMsgs(), btype: g_bType};
    
    // Check More List or not   
    $.post(url, sData).always( function(data, textStatus) {
      
      if(textStatus == "success") {
        // Get More Stories
        var isMoreMsgs = data;
        
        sData.type = 2;
        $.post(url, sData).always( function(data, textStatus) {
          if(textStatus == "success") {
            
            $(data).hide().appendTo('#txtListView').fadeIn("slow");
            $('#txtListView').find('.commentText').elastic();
            
            if(!isMoreMsgs) {
              $('#moreStroyProgressImg').hide();
              $g_More_Story.hide();
              $('#noMoreStory').show();
              
              return false;
            } 
          
          
          }
          
          $('#moreStroyProgressImg').hide();
          $('#moreStroyMsg').show();
          $this.css("text-align" , "left");
          moreStoryMouseEventOn();
          moreStoryClickEventOn();
          
        }); // Second Post
          
      } else {
        // Fail to First Post 
          $('#moreStroyProgressImg').hide();
          $('#moreStroyMsg').show();
          $this.css("text-align" , "left");
          moreStoryMouseEventOn();
          moreStoryClickEventOn();
      }
      
    }); // First Post
    
  }); // $g_More_Story.on ( 'click'
    
} // function moreStoryClickEventOn



function msgLib(  ){
  /*
   *   Submit Events
   */  
   // New Msg
  $('#postForm').submit( function() {
    $this = $(this);
       
    //$(":submit" , this).attr("disabled", "disabled");
    //$(":text" , this).attr("disabled", "disabled");
   
    $postMsgText = $this.find('textarea');
    $postMsgName = $this.find('input[name="postName"]');
    $postMsgPW = $this.find('input[name="postPW"]');
    
    var vMsg = $.trim($postMsgText.val());
    var vName = $.trim($postMsgName.val());
    var vPW = $.trim($postMsgPW.val());
    
    var isErr = false;
    var isImg = false;
    
    var img1 = $this.find('input[name="postImg1"]').val();

    if (img1) isImg = true;
    
    if(!vPW) {
      isErr = true;
      $postMsgPW.val("");
      $postMsgPW.focus();
      $('#postFormLblPW').css('color' , '#C66');
    } else {
      $('#postFormLblPW').css('color' , '#666');
    }
    
    if(!vName) {
      isErr = true;
      $postMsgName.val("");
      $postMsgName.focus();
      $('#postFormLblName').css('color' , '#C66');
    } else {
      $('#postFormLblName').css('color' , '#666');
    }

    if(!vMsg && !isImg) {
             
      isErr = true;
      $postMsgText.focus();

    }
    
    //Check Type of File
    if(isImg) {
      
      var pos = img1.lastIndexOf(".");
      var ext = img1.substr(pos + 1).toLowerCase();
      
      if( ext != "gif" && ext != "jpg" && ext != "png" ) {
        $('#postImgUploadMsg').css('color' , '#C66');
        $this.find('input[name="postImg1"]').val("");
        isErr = true;
      } else {
        $('#postImgUploadMsg').css('color' , '#333');
      }
    }
        
    if(isErr) {
      return false;
    }


    // show progress Img   
    $('#postProgressImg').show();
    var formData = $this.serialize();
    
    //console.log("%o", formData);
    
    //var url = $this.attr('action');
   
    
    $postSubmitButton = $(":submit" , this);
    $postSubmitButton.attr("disabled", "disabled");
    
    // Change design as disabled button
    $postSubmitButton.css({"background":"#ADBAD4", "border-color":"#94A2BF", "cursor":"default"}) 

    if (isImg) {
      // For Add Photo
      var url = "addPhotos.php";
      
      $this.attr({"action" : url , "enctype" : "multipart/form-data"});
      
      return true;
      
    } else {
      var url = "insertNewPost.php";
  
      $.post(url, formData).always( function(data, textStatus) {
        $('#postProgressImg').hide();
        
        $postSubmitButton = $("#postAll :submit");
        $postSubmitButton.removeAttr("disabled");
        $postSubmitButton.css({"background":"#627AAC", "border-color":"#29447E", "cursor":"pointer"})
        
        if (textStatus == 'success') {
             $(data).hide().prependTo('#txtListView').fadeIn("slow");
             $('#txtListView').find('.commentText:first').elastic();
             
             $postMsgText.val("");
             $postMsgName.val("");
             $postMsgPW.val("");
             
             $('#postViewDummy').show();
             $('#postFormView').hide();
        }
      });
 
      return false;
    }
      
    
  }); // $('#postForm').submit
  
   
  /*
   *   Click Events
   */  
  // Remove Msg
  $g_MAIN_MSG_LIST.on('click', '.msgDelImg', function() {    
    $this = $(this);
          
    showDeletePopupWindow('msg', $this);
    
  }); // $g_MAIN_MSG_LIST.on('click', '.commentLink'
  
  // See More
  $g_MAIN_MSG_LIST.on('click', '.msg_exposed_link', function() {    
    $this = $(this);
          
    $this.hide();
    $this.siblings('.msg_exposed_hide').hide();
    $this.siblings('.msg_exposed_show').show();
    
  }); // $g_MAIN_MSG_LIST.on('click', '.msg_exposed_link'
  
    
  // Click a Image
  $g_MAIN_MSG_LIST.on('click', '.toSeeMainImg', function() {    
    $this = $(this);
    
    var imgPath = $('img', this).attr("src");
    var pos = imgPath.lastIndexOf("/");
    var originImg = "/dev/data/boardImg" + imgPath.substr(pos);

    $("#IPW_Big_Img").attr("src", originImg);
    $("#IPW_Show_Imgs").show();
    
    $('body').css("overflow", "hidden"); 
    //$("#container").hide();
    
  }); // $g_MAIN_MSG_LIST.on('click', '.toSeeMainImg'
  
  // Close Original Image Window
  $("#IPW_Show_Imgs").click( function() { 
    $this = $(this);
    
   // $("#container").show();
    $this.hide();
    $('body').css("overflow", "visible"); 
  }); // $("#IPW_Show_Imgs").click
  
  
  // Get More Strories
  moreStoryClickEventOn();
  
  /*
   *   focus Events
   */  
   $('#postViewDummyText').focus(function() {
   
      $(this).parent().hide();
      $('#postFormView').show();
      $g_Post_TextArea.focus();
   
   });
  
  /*
   *   Hover Events
   */  
  
   // Write Comment 
  $g_MAIN_MSG_LIST.on({
    mouseenter: function(){
      $this = $(this);
   
      $this.css({"text-decoration":"underline", "cursor":"pointer"});  
    },
    mouseleave: function(){
      $this = $(this);
      $this.css({"text-decoration":"none", "cursor":"default"});
    }
  }, '.commentLink'); 
  
  // Delete Msg
  $g_MAIN_MSG_LIST.on({
    mouseenter: function(){
      $this = $(this);
   
      $('.msgDelImg', this).show();  
    },
    mouseleave: function(){
      $this = $(this);
      $('.msgDelImg', this).hide();
    }
  }, '.msgContent'); 
  
  $g_MAIN_MSG_LIST.on({
    mouseenter: function(){
      $this = $(this);
   
      $this.css('cursor', 'pointer');
      $this.attr('src','img/del1.png');
      
      showToolTip( $this.parent(), 'Delete&nbsp;Post' );       
    },
    mouseleave: function(){
      $this = $(this);
      
      $this.css('cursor', 'default');
      $this.attr('src','img/del2.png'); 
      
     removeToolTip();
    }
  }, '.msgDelImg'); 
  
  // See More
  $g_MAIN_MSG_LIST.on({
    mouseenter: function(){
      $this = $(this);
      $this.css({'cursor' : 'pointer' , 'text-decoration' : 'underline' });
 
    },
    mouseleave: function(){
      $this = $(this);
      $this.css({'cursor' : 'default' , 'text-decoration' : 'none'});
 
    }
  }, '.msg_exposed_link'); // $g_MAIN_MSG_LIST.on

   
  // More Stories  
  moreStoryMouseEventOn();
  
  
}
