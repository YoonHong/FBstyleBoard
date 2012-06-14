
function showCommentPost($commentPost){

  $commentPost.show();
  $commentPost.find('.commentText').focus();
  
  initCommentForm ( $commentPost.find('.commentFormName') );
  initCommentForm ( $commentPost.find('.commentFormPW') );
  
  initCommentForm ( $commentPost.find('.commentText') );
  
}

function initCommentForm( $input ) {
  if($input.is('.commentFormName')) {
    $input.val("Your Name!!!");
    $input.css("color" , "gray");
    $input.data('step', 'default');
    
  } else if($input.is('.commentFormPW')) {
    $input.val("Password");
    $input.css("color" , "gray");
    $input.data('step', 'default');
      
  } else if($input.is('.commentText')) {
    $input.val("");
    $input.data('step', 'hasValue');
  }

}

function isComment($commentPost) { 
 
  var numComment = $commentPost.parent().find('.commentItem').length;
   
  if(numComment == 0) {
    return false;
  } else {
    return true;
  }
 
}

function commentFormHasFocus ($input) {
  
  var vStep = $input.data('step');
  
  if( vStep == 'default' || vStep == 'err') {
    $input.val("");
    $input.css("color" , "black");
    $input.data('step', 'hasValue');
  }
}

function commentFormBlur ($msg, $input) {
  
  var v = $.trim($input.val());
  
  if(v) {
     
  } else {
    // Blur without msg
    $input.val($msg);
    $input.css("color" , "#C66");
    $input.data('step', 'err');     
  }
}


function valCommentForm($input){
  
  if ($input.data('step') == 'hasValue' && $.trim($input.val())) 
    return true;
  else {
       
    $input.data('step', 'err');
    $input.css("color" , "#C66");
    
    return false;
  }
    
}

function commentLib() {
  
  /*
   *   Submit Events
   */ 
  $g_MAIN_MSG_LIST.on('submit', '.ctForm', function() {

    $this = $(this);
    
    // Validate values
    $inputName = $this.find('.commentFormName');
    $inputPW = $this.find('.commentFormPW');
    $inputMsg = $this.find('.commentText');
    
    var valName = valCommentForm($inputName);
    var valPW = valCommentForm($inputPW);
    var valMSG = valCommentForm($inputMsg);
    
    if(!(valName && valPW && valMSG)) {
        return false;
    }

          
    //g_ctID = $(this .commentMID).val();
    
    // show progress Img   
    $this.parents('.msgInnerContent').find('.commentProgressImg').show();
    
    $(":submit" , this).attr("disabled", "disabled");
        
    var formData = $this.serialize();

    var url = $this.attr('action');
    
     $this.parents('.comment').find('.commentItems').ajaxComplete(function(e, xhr, settings) {
      
      if(settings.url == "insertNewComment.php"){ 
        $this = $(this);
             
        if( xhr.status == 200) {
                
          $(xhr.responseText).hide().appendTo($this).fadeIn();
          $this.off("ajaxSuccess");
        
          $this.parent().children('.commentPost').hide();
          $this.parent().children('.dummyCommentText').show();
        }
        
        $this.parent().find(":submit").removeAttr("disabled");
                        
        $this.parents('.msgInnerContent').find('.commentProgressImg').hide();
        $this.off("ajaxComplete");
      }
           
    });      
    
    $.post(url, formData, function (data) {  });
    
        
    return false;
    
  });  
  
  
  /*
   *   Click Events
   */ 
   
  // Click CommentLink
  $g_MAIN_MSG_LIST.on('click', '.commentLink', function() {
      $this = $(this);
      $comment = $this.parents('.msgInnerContent').find('.comment');
             
      $commentPost = $comment.find('.commentPost');

      if ($commentPost.css("display") == "none") {
      
        if( isComment($commentPost) ) {
          $comment.find('.dummyCommentText').hide();      
           
        }    
        showCommentPost($commentPost);
      }
   
  });  // $('#txtListView').on('click', '.commentLink'
  
  
  $g_MAIN_MSG_LIST.on('click', '.dummyCommentText', function() {
    $this = $(this);
        
    $this.hide();
    showCommentPost($this.siblings('.commentPost'));
    
  });  // $('#txtListView').on('click', '.dummyCommentText'

  
  $g_MAIN_MSG_LIST.on('click', '.commentRMForm', function() {
      $this = $(this);
      $commentPost = $this.parents('.commentPost');
      $commentPost.hide();
     
      if($commentPost.siblings('.commentItems').is('.commentItems')){
        $commentPost.siblings('.dummyCommentText').show();
      }
      
  }); // End : $('#txtListView').on('click', '.commentRMForm'
  
  
  // View All Comments
  $g_MAIN_MSG_LIST.on('click', '.commentViewAll', function() {
    $this = $(this);
    $commentItems = $this.parent();
    
    $this.children(".commentViewAllProgressImg").show();
    var mID = $this.parents('.msgContent').attr('id');
    
    // Lose Click event forever
    $this.off('click');
    
    $commentItems.ajaxComplete( function (e, xhr, settings) {
      $cItems = $(this);
      if (settings.url == "viewAllComments.php") {
        if( xhr.status == 200) {
          
            $cItems.children('.commentViewAll').hide();
            $cItems.html(xhr.responseText);
      
        } else {
          
        }
        
        $cItems.find(".commentViewAllProgressImg").hide();
        $cItems.off("ajaxComplete");
        
      }
    });

    $.post("viewAllComments.php", "mid=" + mID, function (data) {  });
    
  }); // End : $('#commentItems').on('click', '.commentViewAll'
    

  // Click Remove Comment
  $g_MAIN_MSG_LIST.on('click', '.ctDImg', 
      function(event) {
        $this = $(this);
                        
        showDeletePopupWindow('comment', $this);
      }
  
  );
  
  
  /*
   *   Focus Events
   */ 
   
  $g_MAIN_MSG_LIST.on('focus', '.commentFormName', function() {
     
    commentFormHasFocus( $(this) );
    
  }); // End : $('#txtListView').on('click', '.commentFormName'
 
  $g_MAIN_MSG_LIST.on('focus', '.commentFormPW', function() {
   
   commentFormHasFocus( $(this) );
    
  }); // End : $('#txtListView').on('click', '.commentFormPW'
 
  $g_MAIN_MSG_LIST.on('focus', '.commentText', function() {
    
    commentFormHasFocus( $(this) );
      
  }); // End : $('#txtListView').on('click', '.commentText'


  /*
   *   Blur Events
   */ 
   
  $g_MAIN_MSG_LIST.on('blur', '.commentFormName', function() {
        
    commentFormBlur( "Your Name!!!", $(this) );
    
  }); // End : $('#txtListView').on('blur', '.commentFormName'
 
 
  $g_MAIN_MSG_LIST.on('blur', '.commentFormPW', function() {
     
    commentFormBlur( "Password", $(this) );
       
  }); // End : $('#txtListView').on('blur', '.commentFormPW' 
  

  $g_MAIN_MSG_LIST.on('blur', '.commentText', function() {
    
    commentFormBlur( "Write a comment...", $(this) );

  }); // End : $('#txtListView').on('blur', '.commentText' 
  
  
   /*
   *   Hover Events
   */ 
   
  // Comment View All
  $g_MAIN_MSG_LIST.on({
    mouseenter: function(){
      $this = $(this);
      $this.css({"text-decoration":"underline", "cursor":"pointer"});
    
    },
    mouseleave: function(){
      $this = $(this);
      $this.css({"text-decoration":"none", "cursor":"default"});

    }
  }, '.commentViewAll');
  
  // Remove Comment Input Form  
  $g_MAIN_MSG_LIST.on({
    mouseenter: function(){
      $this = $(this);
      $this.css('cursor', 'pointer');
      $this.attr('src','img/del1.png');
      
      //showToolTip( $this.parent() );     
    },
    mouseleave: function(){
      $this = $(this);
      $this.css('cursor', 'default');
      $this.attr('src','img/cdel.png'); 
      
      //removeToolTip();
    }
  }, '.commentRMForm');
 
   // Remove Comment 
  $g_MAIN_MSG_LIST.on({
    mouseenter: function(){
      $('.ctDImg', this).show();  
    },
    mouseleave: function(){
      $('.ctDImg', this).hide();
    }
  }, '.commentItem');
 
   
  // Remove Comment
  $g_MAIN_MSG_LIST.on({
    mouseenter: function(){
      $this = $(this);
      $this.css('cursor', 'pointer');
      $this.attr('src','img/del1.png');
      
      showToolTip( $this.parent(), 'Remove' );     
    },
    mouseleave: function(){
      $this = $(this);
      $this.css('cursor', 'default');
      $this.attr('src','img/cdel.png'); 
      
      removeToolTip();
    }
  }, '.ctDImg');


} // End : function comment()
