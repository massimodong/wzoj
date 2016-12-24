function sendForm(form){
	var action;
	if(form.attr('action')){
		action = form.attr('action');
	}else{
		action = window.location.pathname;
	}
	$.post(action, form.serialize())
		.done(function(){
			alert('yes!');
		})
		.fail(function(){
			alert('no!');
		});
}