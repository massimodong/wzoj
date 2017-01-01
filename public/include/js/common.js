function addAlertSuccess(message){
	$('#alerts').append("<div class='alert alert-success alert-dismissable fade in'>"
		    	    + "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"
			    + message
			    + "</div>");
	window.setTimeout(function() { $(".alert-success").alert('close') }, 800);
}

function addAlertWarning(message){
	$("#alerts").append("<div class='alert alert-warning alert-dismissable'>"
			    + "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"
			    + message
			    + "</div>");
}

function alertEach(key, message){
	addAlertWarning(message);
}

function sendForm(form){
	var action;
	if(form.attr('action')){
		action = form.attr('action');
	}else{
		action = window.location.pathname;
	}
	$.post(action, form.serialize())
		.done(function( data ){
			addAlertSuccess("操作成功!");
		})
		.fail(function( data ){
			$.each(data.responseJSON, alertEach);
		});
}

function showOrHideCode(){
	if(typeof showOrHideCode.show == 'undefined'){
		showOrHideCode.show = 1;
	}
	showOrHideCode.show = 1 - showOrHideCode.show; //reverse
	if(showOrHideCode.show == 1){
		$('#code_pre').css('display', 'block');
		$('#code_button').html('—');
	}else{
		$('#code_pre').css('display', 'none');
		$('#code_button').html('+');
	}
}
