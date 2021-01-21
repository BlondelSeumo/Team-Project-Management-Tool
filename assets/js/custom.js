"use strict";

$(document).on('click','.modal-edit-plan',function(e){
	e.preventDefault();

    let save_button = $(this);
  	save_button.addClass('btn-progress');

    var id = $(this).data("id");
    $.ajax({
        type: "POST",
        url: base_url+'plans/ajax_get_plan_by_id', 
        data: "id="+id,
        dataType: "json",
        success: function(result) 
        {
			save_button.removeClass('btn-progress');
        	if(result['error'] == false){
	        	$("#update_id").val(result['data'][0].id);
	        	$("#title").val(result['data'][0].title);
	        	$("#price").val(result['data'][0].price);
				$("#billing_type").val(result['data'][0].billing_type);
				$("#billing_type").trigger("change");
	        	$("#projects").val(result['data'][0].projects);
	        	$("#tasks").val(result['data'][0].tasks);
	        	$("#users").val(result['data'][0].users);
	    		$("#modal-edit-plan").trigger("click");
    		}else{
    			iziToast.error({
				    title: "Something wrong! Try again.",
				    message: "",
				    position: 'topRight'
				});
    		}
        }        
    });
});

$("#modal-edit-plan").fireModal({
  title: 'Edit Plan',
  body: $("#modal-edit-plan-part"),
  footerClass: 'bg-whitesmoke',
  autoFocus: false,
  onFormSubmit: function(modal, e, form) {
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
		    if(result['error'] == false){
				$('#plans_list').bootstrapTable('refresh');
				modal.modal('hide');
		    }else{
		        modal.find('.modal-body').append('<div class="alert alert-danger">'+result['message']+'</div>');
		    }
		    modal.find('.modal-body').find('.alert').delay(4000).fadeOut();    
      		form.stopProgress();  
		}
    });

    e.preventDefault();
  },
  buttons: [
    {
      text: 'Update',
      submit: true,
      class: 'btn btn-primary',
      handler: function(modal) {
      }
    }
  ]
});

$("#modal-add-plan").fireModal({
	title: 'Create New Plan',
	body: $("#modal-add-plan-part"),
	footerClass: 'bg-whitesmoke',
	autoFocus: false,
	onFormSubmit: function(modal, e, form) {
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
			  if(result['error'] == false){
				$('#plans_list').bootstrapTable('refresh');
				modal.modal('hide');
			  }else{
				  modal.find('.modal-body').append('<div class="alert alert-danger">'+result['message']+'</div>');
			  }
			  modal.find('.modal-body').find('.alert').delay(4000).fadeOut();    
				form.stopProgress();  
		  }
	  });
  
	  e.preventDefault();
	},
	buttons: [
	  {
		text: 'Create',
		submit: true,
		class: 'btn btn-primary ',
		handler: function(modal) {
		}
	  }
	]
});

$(document).on('click','.delete_plan',function(e){
	e.preventDefault();
	var id = $(this).data("id");
	if(id == 1){
		swal({
			title: 'Default Plan',
			text: 'Default plan can not be deleted.',
			icon: 'info',
			dangerMode: true,
			});
	}else{
		swal({
		title: 'Are you sure?',
		text: 'You want to delete this Plan? All users under this plan will be added to the Default Plan.',
		icon: 'warning',
		buttons: true,
		dangerMode: true,
		})
		.then((willDelete) => {
			if (willDelete) {
				$.ajax({
					type: "POST",
					url: base_url+'plans/delete/'+id, 
					data: "id="+id,
					dataType: "json",
					success: function(result) 
					{	
						if(result['error'] == false){
							$('#plans_list').bootstrapTable('refresh');
						}else{
							iziToast.error({
								title: result['message'],
								message: "",
								position: 'topRight'
							});
						}
					}        
				});
			} 
		});
	}
});

$(document).on('click','.check-todo',function(e){
	
	if($(this).hasClass('checked')){
		$(this).removeClass('checked');
		$(this).children('strong').removeClass('text-primary text-strike');
		$(this).parent('.custom-checkbox').children('.text-small').addClass('text-muted').removeClass('text-success text-danger');
		var status = 0;
	}else{
		$(this).addClass('checked');
		$(this).children('strong').addClass('text-primary text-strike');
		$(this).parent('.custom-checkbox').children('.text-small').addClass('text-success').removeClass('text-muted text-danger');
		var status = 1;
	}
    var id = $(this).data("id");
    
    $.ajax({
        type: "POST",
        url: base_url+'todo/update_status', 
        data: "id="+id+"&status="+status,
        dataType: "json",
        success: function(result) 
        {	
        	if(result['error'] == false){
	        	
    		}else{
    			iziToast.error({
				    title: "Something wrong! Try again.",
				    message: "",
				    position: 'topRight'
				});
    		}
        }        
    });
});


$(document).on('click','.delete_todo',function(e){
	e.preventDefault();
    var id = $(this).data("id");
    swal({
    title: 'Are you sure?',
    text: 'You want to delete this ToDo?',
    icon: 'warning',
    buttons: true,
    dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            $.ajax({
		        type: "POST",
		        url: base_url+'todo/delete/'+id, 
		        data: "id="+id,
		        dataType: "json",
		        success: function(result) 
		        {	
		        	if(result['error'] == false){
			        	location.reload();
		    		}else{
		    			iziToast.error({
						    title: result['message'],
						    message: "",
						    position: 'topRight'
						});
		    		}
		        }        
		    });
        } 
    });
});

$(document).on('click','.modal-edit-todo',function(e){
	e.preventDefault();

	var card = $(this).closest('.card');
	let save_button = $(this);
  	save_button.addClass('btn-progress');
	let card_progress = $.cardProgress(card, {
		spinner: true
	});

    var id = $(this).data("edit");
    $.ajax({
        type: "POST",
        url: base_url+'todo/get_todo', 
        data: "id="+id,
        dataType: "json",
        success: function(result) 
        {	
			card_progress.dismiss(function() {
				save_button.removeClass('btn-progress');
			});

        	if(result['error'] == false){
	        	$("#update_id").val(result['data'][0].id);
				$("#todo").val(result['data'][0].todo);
				$('#due_date').daterangepicker({
					locale: {format: date_format_js},
					singleDatePicker: true,
					startDate: result['data'][0].due_date,
				});
	    		$("#modal-edit-todo").trigger("click");
    		}else{
    			iziToast.error({
				    title: "Something wrong! Try again.",
				    message: "",
				    position: 'topRight'
				});
    		}
        }        
    });
});

$("#modal-edit-todo").fireModal({
  title: 'Edit ToDo',
  body: $("#modal-edit-todo-part"),
  footerClass: 'bg-whitesmoke',
  autoFocus: false,
  onFormSubmit: function(modal, e, form) {
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
		    if(result['error'] == false){
		    	location.reload();
		    }else{
		        modal.find('.modal-body').append('<div class="alert alert-danger">'+result['message']+'</div>');
		    }
		    modal.find('.modal-body').find('.alert').delay(4000).fadeOut();    
      		form.stopProgress();  
		}
    });

    e.preventDefault();
  },
  buttons: [
    {
      text: 'Update',
      submit: true,
      class: 'btn btn-primary',
      handler: function(modal) {
      }
    }
  ]
});

$("#modal-add-todo").fireModal({
	title: 'Create New ToDo',
	body: $("#modal-add-todo-part"),
	footerClass: 'bg-whitesmoke',
	autoFocus: false,
	onFormSubmit: function(modal, e, form) {
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
			  if(result['error'] == false){
					location.reload();
			  }else{
				  modal.find('.modal-body').append('<div class="alert alert-danger">'+result['message']+'</div>');
			  }
			  modal.find('.modal-body').find('.alert').delay(4000).fadeOut();    
				form.stopProgress();  
		  }
	  });
  
	  e.preventDefault();
	},
	buttons: [
	  {
		text: 'Create',
		submit: true,
		class: 'btn btn-primary ',
		handler: function(modal) {
		}
	  }
	]
});

$(document).on('click','.delete_notes',function(e){
	e.preventDefault();
    var id = $(this).data("id");
    swal({
    title: 'Are you sure?',
    text: 'You want to delete this note?',
    icon: 'warning',
    buttons: true,
    dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            $.ajax({
		        type: "POST",
		        url: base_url+'notes/delete/'+id, 
		        data: "id="+id,
		        dataType: "json",
		        success: function(result) 
		        {	
		        	if(result['error'] == false){
			        	location.reload();
		    		}else{
		    			iziToast.error({
						    title: result['message'],
						    message: "",
						    position: 'topRight'
						});
		    		}
		        }        
		    });
        } 
    });
});

$(document).on('click','.modal-edit-notes',function(e){
	e.preventDefault();

	var card = $(this).closest('.card');
	let save_button = $(this);
  	save_button.addClass('btn-progress');
	let card_progress = $.cardProgress(card, {
		spinner: true
	});

    var id = $(this).data("edit");
    $.ajax({
        type: "POST",
        url: base_url+'notes/get_notes', 
        data: "id="+id,
        dataType: "json",
        success: function(result) 
        {	
			card_progress.dismiss(function() {
				save_button.removeClass('btn-progress');
			});

        	if(result['error'] == false){
	        	$("#update_id").val(result['data'][0].id);
	        	$("#description").val(result['data'][0].description);
	    		$("#modal-edit-notes").trigger("click");
    		}else{
    			iziToast.error({
				    title: "Something wrong! Try again.",
				    message: "",
				    position: 'topRight'
				});
    		}
        }        
    });
});

$("#modal-edit-notes").fireModal({
  title: 'Edit Note',
  body: $("#modal-edit-notes-part"),
  footerClass: 'bg-whitesmoke',
  autoFocus: false,
  onFormSubmit: function(modal, e, form) {
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
		    if(result['error'] == false){
		    	location.reload();
		    }else{
		        modal.find('.modal-body').append('<div class="alert alert-danger">'+result['message']+'</div>');
		    }
		    modal.find('.modal-body').find('.alert').delay(4000).fadeOut();    
      		form.stopProgress();  
		}
    });

    e.preventDefault();
  },
  buttons: [
    {
      text: 'Update',
      submit: true,
      class: 'btn btn-primary',
      handler: function(modal) {
      }
    }
  ]
});

$("#modal-add-notes").fireModal({
	title: 'Create New Note',
	body: $("#modal-add-notes-part"),
	footerClass: 'bg-whitesmoke',
	autoFocus: false,
	onFormSubmit: function(modal, e, form) {
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
			  if(result['error'] == false){
					location.reload();
			  }else{
				  modal.find('.modal-body').append('<div class="alert alert-danger">'+result['message']+'</div>');
			  }
			  modal.find('.modal-body').find('.alert').delay(4000).fadeOut();    
				form.stopProgress();  
		  }
	  });
  
	  e.preventDefault();
	},
	buttons: [
	  {
		text: 'Create',
		submit: true,
		class: 'btn btn-primary ',
		handler: function(modal) {
		}
	  }
	]
});

$(document).on('click','.delete_project',function(e){
	e.preventDefault();
    var id = $(this).data("id");
    swal({
    title: 'Are you sure?',
    text: 'You want to delete this project? All related data with this project also will be deleted.',
    icon: 'warning',
    buttons: true,
    dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            $.ajax({
		        type: "POST",
		        url: base_url+'projects/delete_project/'+id, 
		        data: "id="+id,
		        dataType: "json",
		        success: function(result) 
		        {	
		        	if(result['error'] == false){
			        	location.reload();
		    		}else{
		    			iziToast.error({
						    title: result['message'],
						    message: "",
						    position: 'topRight'
						});
		    		}
		        }        
		    });
        } 
    });
});

$(document).on('click','.delete_task',function(e){
	e.preventDefault();
    var id = $(this).data("id");
    swal({
    title: 'Are you sure?',
    text: 'You want to delete this task? All related data with this task also will be deleted.',
    icon: 'warning',
    buttons: true,
    dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            $.ajax({
		        type: "POST",
		        url: base_url+'projects/delete_task/'+id, 
		        data: "id="+id,
		        dataType: "json",
		        success: function(result) 
		        {	
		        	if(result['error'] == false){
			        	location.reload();
		    		}else{
		    			iziToast.error({
						    title: result['message'],
						    message: "",
						    position: 'topRight'
						});
		    		}
		        }        
		    });
        } 
    });
});

$("#setting-update-form").submit(function(e) {
	e.preventDefault();
	swal({
		title: 'Are you sure?',
		text: 'You want to upgrade the system? Please take a backup before going further.',
		icon: 'warning',
		buttons: true,
		dangerMode: true,
		}).then((willDelete) => {
		if (willDelete) {
			let save_button = $(this).find('.savebtn'),
			output_status = $(this).find('.result'),
			card = $('#settings-card');

			let card_progress = $.cardProgress(card, {
				spinner: true
			});
			save_button.addClass('btn-progress');
			output_status.html('');
			
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
						if(result['error'] == false){
							location.reload(true);
						}else{
							output_status.prepend('<div class="alert alert-danger">'+result['message']+'</div>');
						}
						output_status.find('.alert').delay(4000).fadeOut();    
						save_button.removeClass('btn-progress');  
						card_progress.dismiss(function() {
						$('html, body').animate({
							scrollTop: output_status.offset().top
						}, 1000);
						});
					}
				});
		} 
	});
});

$(document).on('click','#comments-tab',function(){
	$("#is_comment").val('true');
	$("#is_attachment").val('false');
});

$(document).on('click','#attachments-tab',function(){
	$("#is_comment").val('false');
	$("#is_attachment").val('true');
	$('#file_list').bootstrapTable('refresh');
});

$(document).on('change','#project_id',function(){
	$.ajax({
        type: "POST",
        url: base_url+'projects/get_project_users/'+$(this).val(), 
        dataType: "json",
        success: function(result) 
        {	
        	var user = '';
			$.each(result, function (key, val) {
				user +=' <option value="'+val.id+'">'+val.full_name+'</option>';
			});
			$("#users_append").html(user);
        }        
    });
});

$(document).on('click','.delete_files',function(e){
	e.preventDefault();
    var url = $(this).data('delete');
    swal({
    title: 'Are you sure?',
    text: 'You want to delete this file?',
    icon: 'warning',
    buttons: true,
    dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            $.ajax({
		        type: "POST",
		        url: url, 
		        dataType: "json",
		        success: function(result) 
		        {	
		        	if(result['error'] == false){
			        	$('#file_list').bootstrapTable('refresh');
		    		}else{
		    			iziToast.error({
						    title: result['message'],
						    message: "",
						    position: 'topRight'
						});
		    		}
		        }        
		    });
        } 
    });
});

$(document).on('change','.project_filter',function(e){
	var value = $(this).val();
	window.location.replace(value);
});

$(document).on('change','#date_format',function(e){
    var js_value = $(this).find(':selected').data('js_value');
    $('#date_format_js').val(js_value);
});

$(document).on('change','#time_format',function(e){
    var js_value = $(this).find(':selected').data('js_value');
    $('#time_format_js').val(js_value);
});

$("#profile-form").submit(function(e) {
	e.preventDefault();
  let save_button = $(this).find('.savebtn'),
    output_status = $(this).find('.result'),
    card = $('#profile-card');

  let card_progress = $.cardProgress(card, {
    spinner: true
  });
  save_button.addClass('btn-progress');
  output_status.html('');
  
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
		    if(result['error'] == false){
		    	location.reload()
		    }else{
				output_status.prepend('<div class="alert alert-danger">'+result['message']+'</div>');
				output_status.find('.alert').delay(4000).fadeOut();    
				save_button.removeClass('btn-progress');  
				card_progress.dismiss(function() {
				$('html, body').animate({
					scrollTop: output_status.offset().top
				}, 1000);
				});
			}
			card_progress.dismiss(function() {
			});
		}
    });
});

$(document).on('click','#user_delete_btn',function(e){
	e.preventDefault();
    var id = $("#update_id").val();
    swal({
    title: 'Are you sure?',
    text: 'You want to delete this user? All related data with this user also will be deleted.',
    icon: 'warning',
    buttons: true,
    dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            $.ajax({
		        type: "POST",
		        url: base_url+'auth/delete_user', 
		        data: "id="+id,
		        dataType: "json",
		        success: function(result) 
		        {	
		        	if(result['error'] == false){
			        	location.reload();
		    		}else{
		    			iziToast.error({
						    title: result['message'],
						    message: "",
						    position: 'topRight'
						});
		    		}
		        }        
		    });
        } 
    });
});

$(document).on('click','#user_active_btn',function(e){
	e.preventDefault();
    var id = $("#update_id").val();
    swal({
    title: 'Are you sure?',
    text: 'You want to activate this user?',
    icon: 'warning',
    buttons: true,
    dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            $.ajax({
		        type: "POST",
		        url: base_url+'auth/activate', 
		        data: "id="+id,
		        dataType: "json",
		        success: function(result) 
		        {	
		        	if(result['error'] == false){
			        	location.reload();
		    		}else{
		    			iziToast.error({
						    title: result['message'],
						    message: "",
						    position: 'topRight'
						});
		    		}
		        }        
		    });
        } 
    });
});

$(document).on('click','#user_deactive_btn',function(e){
	e.preventDefault();
    var id = $("#update_id").val();
    swal({
    title: 'Are you sure?',
    text: 'You want to deactivate this user? This user will be not able to login after deactivation',
    icon: 'warning',
    buttons: true,
    dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            $.ajax({
		        type: "POST",
		        url: base_url+'auth/deactivate', 
		        data: "id="+id,
		        dataType: "json",
		        success: function(result) 
		        {	
		        	if(result['error'] == false){
			        	location.reload();
		    		}else{
		    			iziToast.error({
						    title: result['message'],
						    message: "",
						    position: 'topRight'
						});
		    		}
		        }        
		    });
        } 
    });
});


$(document).on('click','.modal-edit-user',function(e){
	e.preventDefault();

	let save_button = $(this);
  	save_button.addClass('btn-progress');

    var id = $(this).data("edit");
    $.ajax({
        type: "POST",
        url: base_url+'users/ajax_get_user_by_id', 
        data: "id="+id,
        dataType: "json",
        success: function(result) 
        {	
		
			save_button.removeClass('btn-progress');

        	if(result['error'] == false){
				$('#end_date').daterangepicker({
					locale: {format: date_format_js},
					singleDatePicker: true,
					startDate: result['data'].current_plan_expiry,
				});
				$("#update_id").val(result['data'].id);
				$("#company").val(result['data'].company);
	        	$("#old_profile_pic").val(result['data'].profile);
	        	$("#first_name").val(result['data'].first_name);
	        	$("#last_name").val(result['data'].last_name);
				$("#phone").val(result['data'].phone == 0?'':result['data'].phone);

				$("#plan_id").val(result['data'].current_plan_id);
				$("#plan_id").trigger("change");
				$("#groups").val(result['data'].group_id);
				$("#groups").trigger("change");
	            if(result['data'].active == 1){
	            	$("#user_deactive_btn").removeClass('d-none');
	            	$("#user_active_btn").addClass('d-none');
	            }else{
	            	$("#user_deactive_btn").addClass('d-none');
	            	$("#user_active_btn").removeClass('d-none');
	            }
	    		$("#modal-edit-user").trigger("click");
    		}else{
    			iziToast.error({
				    title: "Something wrong! Try again.",
				    message: "",
				    position: 'topRight'
				});
    		}
        }        
    });
});

$("#modal-edit-user").fireModal({
  title: 'Edit User',
  body: $("#modal-edit-user-part"),
  footerClass: 'bg-whitesmoke',
  autoFocus: false,
  onFormSubmit: function(modal, e, form) {
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
		    if(result['error'] == false){
		    	location.reload();
		    }else{
		        modal.find('.modal-body').append('<div class="alert alert-danger">'+result['message']+'</div>');
		    }
		    modal.find('.modal-body').find('.alert').delay(4000).fadeOut();    
      		form.stopProgress();  
		}
    });

    e.preventDefault();
  },
  buttons: [
  	{
      text: 'Delete',
      submit: false,
      class: 'btn btn-danger',
      id: 'user_delete_btn',
      handler: function(modal) {
      }
    },
    {
      text: 'Deactive',
      submit: false,
      class: 'btn btn-danger d-none',
      id: 'user_deactive_btn',
      handler: function(modal) {
      }
    },

    {
      text: 'Active',
      submit: false,
      class: 'btn btn-success d-none',
      id: 'user_active_btn',
      handler: function(modal) {
      }
    },
    {
      text: 'Update',
      submit: true,
      class: 'btn btn-primary',
      handler: function(modal) {
      }
    }
  ]
});

$("#modal-add-user").fireModal({
  title: 'Create New User',
  body: $("#modal-add-user-part"),
  footerClass: 'bg-whitesmoke',
  autoFocus: false,
  onFormSubmit: function(modal, e, form) {
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
		    if(result['error'] == false){
      			location.reload();
		    }else{
		        modal.find('.modal-body').append('<div class="alert alert-danger">'+result['message']+'</div>');
		    }
		    modal.find('.modal-body').find('.alert').delay(4000).fadeOut();    
      		form.stopProgress();  
		}
    });

    e.preventDefault();
  },
  buttons: [
    {
      text: 'Create',
      submit: true,
      class: 'btn btn-primary ',
      handler: function(modal) {
      }
    }
  ]
});

var submit_once = 0;
$("#modal-task-detail").fireModal({
  title: 'Task Detail',
  size: 'modal-lg',
  body: $("#modal-task-detail-part"),
  onFormSubmit: function(modal, e, form) {
	e.preventDefault();
	submit_once++;
	if(submit_once == 1){
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
			submit_once = 0;
			if(result['error'] == false){
				if($("#is_attachment").val() == 'true'){
					$("#attachment").val('');
					$('#file_list').bootstrapTable('refresh');
				}
				if($("#is_comment").val() == 'true'){
					$('#message').val('');
					$.ajax({
						type: "POST",
						url: base_url+'projects/get_comments', 
						data: "type=task_comment&to_id="+$('#comment_task_id').val(),
						dataType: "json",
						success: function(result_1) 
						{	
							if(result_1['error'] == false){
								var html = '';
								var profile = '';
								$.each(result_1['data'], function (key, val) {
									if(val.profile){
										profile = '<figure class="avatar avatar-md mr-3">'+
											'<img src="'+base_url+'assets/uploads/profiles/'+val.profile+'" alt="'+val.first_name+' '+val.last_name+'">'+
										'</figure>';
									}else{
										profile = '<figure class="avatar avatar-md bg-primary text-white mr-3" data-initial="'+val.short_name+'"></figure>';
									}
									html += '<ul class="list-unstyled list-unstyled-border mt-3">'+
									'<li class="media">'+profile+
									'<div class="media-body">'+
										'<div class="float-right text-primary">'+val.created+'</div>'+
										'<div class="media-title">'+val.first_name+' '+val.last_name+'</div>'+
										'<span class="text-muted">'+val.message+'</span>'+
									'</div>'+
									'</li>'+
									'</ul>';
								});
								$("#comments_append").html(html);
							}
						}        
					});
				}
		    }else{
				modal.find('.modal-body').append('<div class="alert alert-danger">'+result['message']+'</div>');
			}
		    
		    modal.find('.modal-body').find('.alert').delay(4000).fadeOut();    
      		form.stopProgress();  
		}
    });
	}
  },
});

$(document).on('click','.modal-task-detail',function(e){
	e.preventDefault();
	
	var card = $(this).closest('.card');
	let save_button = $(this);
  	save_button.addClass('btn-progress');
	let card_progress = $.cardProgress(card, {
		spinner: true
	});

    var id = $(this).data("edit");
    $.ajax({
        type: "POST",
        url: base_url+'projects/get_tasks', 
        data: "task_id="+id,
        dataType: "json",
        success: function(result) 
        {	

			card_progress.dismiss(function() {
				save_button.removeClass('btn-progress');
			});

        	if(result['error'] == false){
	        	$("#task_title").html(result['data'][0]['title']).removeClass().addClass('text-'+result['data'][0]['task_class']);
	        	$("#comment_task_id").val(result['data'][0]['id']);
	        	$("#attachment_task_id").val(result['data'][0]['id']);
	        	$("#task_project").html(result['data'][0]['project_title']).attr('href',base_url+'projects/detail/'+result['data'][0]['project_id']);
				$("#task_description").html(result['data'][0]['description']);
				$("#task_days_status").html('Days '+result['data'][0]['days_status']);
				$("#task_days_count").html(result['data'][0]['days_count']);
	        	$("#task_due_date").html(result['data'][0]['due_date']);
				$("#task_priority").html(result['data'][0]['task_priority']).removeClass().addClass('text-'+result['data'][0]['priority_class']);

				var profile_1 = '';
				$.each(result['data'][0]['task_users'], function (key, val) {
					if(val.profile){
						profile_1 += '<figure class="avatar avatar-sm mr-1">'+
										'<img src="'+base_url+'assets/uploads/profiles/'+val.profile+'" alt="'+val.first_name+' '+val.last_name+'" data-toggle="tooltip" data-placement="top" title="'+val.first_name+' '+val.last_name+'">'+
									'</figure>';
					}else{
						profile_1 += '<figure class="avatar avatar-sm bg-primary text-white mr-1" data-initial="'+val.first_name.charAt(0)+''+val.last_name.charAt(0)+'" data-toggle="tooltip" data-placement="top" title="'+val.first_name+' '+val.last_name+'"></figure>';
					}
				});

				$("#task_users").html(profile_1);
				
				$("#modal-task-detail").trigger("click");
				
				$.ajax({
					type: "POST",
					url: base_url+'projects/get_comments', 
					data: "type=task_comment&to_id="+result['data'][0]['id'],
					dataType: "json",
					success: function(result_1) 
					{	
						if(result_1['error'] == false){
							var html = '';
							var profile = '';
							$.each(result_1['data'], function (key, val) {
								if(val.profile){
									profile = '<figure class="avatar avatar-md mr-3">'+
										'<img src="'+base_url+'assets/uploads/profiles/'+val.profile+'" alt="'+val.first_name+' '+val.last_name+'">'+
									'</figure>';
								}else{
									profile = '<figure class="avatar avatar-md bg-primary text-white mr-3" data-initial="'+val.short_name+'"></figure>';
								}
								html += '<ul class="list-unstyled list-unstyled-border mt-3">'+
								'<li class="media">'+profile+
								  '<div class="media-body">'+
									'<div class="float-right text-primary">'+val.created+'</div>'+
									'<div class="media-title">'+val.first_name+' '+val.last_name+'</div>'+
									'<span class="text-muted">'+val.message+'</span>'+
								  '</div>'+
								'</li>'+
							  	'</ul>';
							});
							$("#comments_append").html(html);
						}
					}        
				});

    		}else{
    			iziToast.error({
				    title: "Something wrong! Try again.",
				    message: "",
				    position: 'topRight'
				});
    		}
        }        
    });
});

$(document).on('click','.modal-edit-task',function(e){
	e.preventDefault();

	var card = $(this).closest('.card');
	let save_button = $(this);
  	save_button.addClass('btn-progress');
	let card_progress = $.cardProgress(card, {
		spinner: true
	});

    var id = $(this).data("edit");
    $.ajax({
        type: "POST",
        url: base_url+'projects/get_tasks', 
        data: "task_id="+id,
        dataType: "json",
        success: function(result) 
        {	
			
			card_progress.dismiss(function() {
				save_button.removeClass('btn-progress');
			});

        	if(result['error'] == false){
	        	$("#update_id").val(id);
	        	$("#title").val(result['data'][0]['title']);
				$("#description").val(result['data'][0]['description']);

				$('#due_date').daterangepicker({
					locale: {format: date_format_js},
					singleDatePicker: true,
					startDate: result['data'][0]['due_date'],
				});
				
				$("#status").val(result['data'][0]['status']);
				$("#status").trigger("change");
				$("#priority").val(result['data'][0]['priority']);
				$("#priority").trigger("change");
				if(result['data'][0]['task_users_ids'] != '' && result['data'][0]['task_users_ids']  != null){ 
					result['data'][0]['task_users_ids'] = result['data'][0]['task_users_ids'].split(',');
					$("#users").val(result['data'][0]['task_users_ids']);
					$("#users").trigger('change');
				}
	    		$("#modal-edit-task").trigger("click");
    		}else{
    			iziToast.error({
				    title: "Something wrong! Try again.",
				    message: "",
				    position: 'topRight'
				});
    		}
        }        
    });
});

$("#modal-edit-task").fireModal({
  title: 'Edit Task',
  body: $("#modal-edit-task-part"),
  footerClass: 'bg-whitesmoke',
  autoFocus: false,
  onFormSubmit: function(modal, e, form) {
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
		    if(result['error'] == false){
		    	location.reload();
		    }else{
		        modal.find('.modal-body').append('<div class="alert alert-danger">'+result['message']+'</div>');
		    }
		    modal.find('.modal-body').find('.alert').delay(4000).fadeOut();    
      		form.stopProgress();  
		}
    });

    e.preventDefault();
  },
  buttons: [
    {
      text: 'Update',
      submit: true,
      class: 'btn btn-primary ',
      handler: function(modal) {
      }
    }
  ]
});

$("#modal-add-task").fireModal({
  title: 'Create New Task',
  body: $("#modal-add-task-part"),
  footerClass: 'bg-whitesmoke',
  autoFocus: false,
  onFormSubmit: function(modal, e, form) {
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
		    if(result['error'] == false){
		    	location.reload();
		    }else{
		        modal.find('.modal-body').append('<div class="alert alert-danger">'+result['message']+'</div>');
		    }
		    modal.find('.modal-body').find('.alert').delay(4000).fadeOut();    
      		form.stopProgress();  
		}
    });

    e.preventDefault();
  },
  buttons: [
    {
      text: 'Create',
      submit: true,
      class: 'btn btn-primary ',
      handler: function(modal) {
      }
    }
  ]
});

$(document).on('click','.modal-edit-project',function(e){
	e.preventDefault();

	var card = $(this).closest('.card');
	let save_button = $(this);
  	save_button.addClass('btn-progress');
	let card_progress = $.cardProgress(card, {
		spinner: true
	});

    var id = $(this).data("edit");
    $.ajax({
        type: "POST",
        url: base_url+'projects/get_projects', 
        data: "project_id="+id,
        dataType: "json",
        success: function(result) 
        {	
			card_progress.dismiss(function() {
				save_button.removeClass('btn-progress');
			});

        	if(result['error'] == false && result['data'][0]['id'] != undefined){
	        	$("#update_id").val(id);
	        	$("#title").val(result['data'][0]['title']);
				$("#description").val(result['data'][0]['description']);
				
				$('#starting_date').daterangepicker({
					locale: {format: date_format_js},
					singleDatePicker: true,
					startDate: result['data'][0]['starting_date'],
				});
				$('#ending_date').daterangepicker({
					locale: {format: date_format_js},
					singleDatePicker: true,
					startDate: result['data'][0]['ending_date'],
				});
				
				$("#status").val(result['data'][0]['status']);
				$("#status").trigger("change");
				if(result['data'][0]['project_users_ids'] != '' && result['data'][0]['project_users_ids']  != null){ 
					result['data'][0]['project_users_ids'] = result['data'][0]['project_users_ids'].split(',');
				}
				$("#users").val(result['data'][0]['project_users_ids']);
				$("#users").trigger('change');
				$("#client").val(result['data'][0]['client_id']);
				$("#client").trigger('change');
	    		$("#modal-edit-project").trigger("click");
    		}else{
    			iziToast.error({
				    title: "Something wrong! Try again.",
				    message: "",
				    position: 'topRight'
				});
    		}
        }        
    });
});


$("#modal-edit-project").fireModal({
  title: 'Edit Project',
  body: $("#modal-edit-project-part"),
  footerClass: 'bg-whitesmoke',
  autoFocus: false,
  onFormSubmit: function(modal, e, form) {
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
		    if(result['error'] == false){
				location.reload();
		    }else{
		        modal.find('.modal-body').append('<div class="alert alert-danger">'+result['message']+'</div>');
		    }
		    modal.find('.modal-body').find('.alert').delay(4000).fadeOut();    
      		form.stopProgress();  
		}
    });

    e.preventDefault();
  },
  buttons: [
    {
      text: 'Update',
      submit: true,
      class: 'btn btn-primary ',
      handler: function(modal) {
      }
    }
  ]
});

$("#modal-add-project").fireModal({
  title: 'Create New Project',
  body: $("#modal-add-project-part"),
  footerClass: 'bg-whitesmoke',
  autoFocus: false,
  onFormSubmit: function(modal, e, form) {
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
		    if(result['error'] == false){
		    	location.reload();
		    }else{
		        modal.find('.modal-body').append('<div class="alert alert-danger">'+result['message']+'</div>');
		    }
		    modal.find('.modal-body').find('.alert').delay(4000).fadeOut();    
      		form.stopProgress();  
		}
    });

    e.preventDefault();
  },
  buttons: [
    {
      text: 'Create',
      submit: true,
      class: 'btn btn-primary ',
      handler: function(modal) {
      }
    }
  ]
});

$("#setting-form").submit(function(e) {
	e.preventDefault();
  let save_button = $(this).find('.savebtn'),
    output_status = $(this).find('.result'),
    card = $('#settings-card');

  let card_progress = $.cardProgress(card, {
    spinner: true
  });
  save_button.addClass('btn-progress');
  output_status.html('');
  
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
		    if(result['error'] == false){
		    	if(result['data']['full_logo'] != undefined && result['data']['full_logo'] != ''){
		    		$('#full_logo-img').attr('src', base_url+'assets/uploads/logos/'+result['data']['full_logo']);
		    	}
		    	if(result['data']['half_logo'] != undefined && result['data']['half_logo'] != ''){
		    		$('#half_logo-img').attr('src', base_url+'assets/uploads/logos/'+result['data']['half_logo']);
		    	}
		    	if(result['data']['favicon'] != undefined && result['data']['favicon'] != ''){
		    		$('#favicon-img').attr('src', base_url+'assets/uploads/logos/'+result['data']['favicon']);
		    	}
		    	output_status.prepend('<div class="alert alert-success">'+result['message']+'</div>');
		    }else{
		        output_status.prepend('<div class="alert alert-danger">'+result['message']+'</div>');
		    }
		    output_status.find('.alert').delay(4000).fadeOut();    
      		save_button.removeClass('btn-progress');  
      		card_progress.dismiss(function() {
		      $('html, body').animate({
		        scrollTop: output_status.offset().top
		      }, 1000);
		    });
		}
    });
});

$(document).on('change','#php_timezone',function(e){
    var gmt = $(this).find(':selected').data('gmt');
    $('#mysql_timezone').val(gmt);
});

$("#modal-forgot-password").fireModal({
  title: 'Forgot Password',
  body: $("#modal-forgot-password-part"),
  footerClass: 'bg-whitesmoke',
  autoFocus: false,
  onFormSubmit: function(modal, e, form) {
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
		    if(result['error'] == false){
		    	modal.find('.modal-body').append('<div class="alert alert-success">'+result['message']+'</div>');
		    }else{
		        modal.find('.modal-body').append('<div class="alert alert-danger">'+result['message']+'</div>');
		    }
		    modal.find('.modal-body').find('.alert').delay(4000).fadeOut();    
      		form.stopProgress();  
		}
    });

    e.preventDefault();
  },
  buttons: [
    {
      text: 'Send',
      submit: true,
      class: 'btn btn-primary ',
      handler: function(modal) {
      }
    }
  ]
});

$("#login").submit(function(e) {
	e.preventDefault();
  	let save_button = $(this).find('.savebtn'),
    output_status = $(this).find('.result'),
    card = $('#login');

  	let card_progress = $.cardProgress(card, {
    	spinner: true
  	});
  	save_button.addClass('btn-progress');
  	output_status.html('');
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
	    	card_progress.dismiss(function() {
			    if(result['error'] == false){
					output_status.prepend('<div class="alert alert-success">'+result['message']+'</div>');
					window.location.replace(base_url);
			    }else{
			        output_status.prepend('<div class="alert alert-danger">'+result['message']+'</div>');
			    }
			    output_status.find('.alert').delay(4000).fadeOut();
			    save_button.removeClass('btn-progress');      
			    $('html, body').animate({
			        scrollTop: output_status.offset().top
			    }, 1000);
		    });
		}
    });

  	return false;
});