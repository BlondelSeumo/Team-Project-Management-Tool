"use strict";

var timeout = '';
var msg_count = []
$("#chat-form").submit(function() {
  clearTimeout(timeout);
	if($('#chat_input').val().trim().length > 0) {    
    
    var formData = new FormData(this);
    $.ajax({
	    type:'POST',
	    url: $(this).attr('action'),
	    data:formData,
	    cache:false,
	    contentType: false,
	    processData: false,
	    dataType: "json",
	    success:function(result){
			    if(result['error'] == true){
			    	iziToast.error({
              title: "Something wrong! Try again.",
              message: "",
              position: 'topRight'
            });
			    }
		  }
    });
    
	  $.chatCtrl('#mychatbox', {
		text: $('#chat_input').val(),
	  });
	  $('#chat_input').val('');
	} 
	return false;
});
  
function get_chat(opposite_user_id){
  
	$.ajax({
        type: "POST",
        url: base_url+'chat/get_chat', 
        data: "opposite_user_id="+opposite_user_id,
        dataType: "json",
        success: function(result) 
        {	
        	if(result['error'] == false){
              if(result['data']){
                var chats = result['data'];
                if(msg_count[opposite_user_id] == undefined || msg_count[opposite_user_id] == ''){
                  msg_count[opposite_user_id] = chats.length;
                  for(var i = 0; i < chats.length; i++) {
                    $.chatCtrl('#mychatbox', {
                    text: (chats[i].text != undefined ? chats[i].text : ''),
                    position: 'chat-'+chats[i].position,
                    });
                  }
                }
                
                if(msg_count[opposite_user_id] < chats.length){
                  msg_count[opposite_user_id] = chats.length;
                  for(var i = 0; i < chats.length; i++) {
                    $.chatCtrl('#mychatbox', {
                    text: (chats[i].text != undefined ? chats[i].text : ''),
                    position: 'chat-'+chats[i].position,
                    });
                  }
                }
              }
          }else{
              iziToast.error({
                  title: "Something wrong! Try again.",
                  message: "",
                  position: 'topRight'
              });
          }
        }        
    });

    timeout = setTimeout(function () {
      var to_id = $("#to_id").val();
      if(to_id != ''){
        get_chat(to_id);
      }
    }, 7000);
}

$(document).on('click','.user-selected-for-chat',function(e){
  e.preventDefault();
    $(".chat-content").html('');
    clearTimeout(timeout);
    var card = $('#mychatbox');
    let card_progress = $.cardProgress(card, {
      spinner: true
    });

    var id = $(this).data("id");
    msg_count[id] = '';
    $.ajax({
        type: "POST",
        url: base_url+'users/ajax_get_user_by_id', 
        data: "id="+id,
        dataType: "json",
        success: function(result) 
        {	
          get_chat(result['data'].id);

          if(result['error'] == false){
            $("#to_id").val(result['data'].id);
            if(result['data'].profile && result['data'].profile != ''){
              var profile = '<figure class="avatar avatar-sm">'+
              '<img src="'+base_url+'assets/uploads/profiles/'+result['data'].profile+'" alt="'+result['data'].first_name+' '+result['data'].last_name+'">'+
              '</figure><div class="media-body">'+
              '<div class="ml-2 font-weight-bold">'+result['data'].first_name+' '+result['data'].last_name+'</div>'+
              '</div>';
            }else{
              var profile = '<figure class="user-avatar avatar avatar-sm rounded-circle profile-widget-picture" data-initial="'+result['data'].short_name+'"></figure><div class="media-body">'+
              '<div class="ml-2 font-weight-bold">'+result['data'].first_name+' '+result['data'].last_name+'</div>'+
                '</div>';
            }
            $("#current_chating_user").html(profile);
            $("#chat-form").removeClass('d-none');
          }else{
              iziToast.error({
                title: "Something wrong! Try again.",
                message: "",
                position: 'topRight'
            });
          }
          
          card_progress.dismiss(function() {
          });

        }        
    });
});

