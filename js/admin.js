
/*
 * Makes an ajax call to the requested php script from the 'call' folder.
 * Gives the result in JSON format.
 */
function ajax_call(cb, script_name, parameters) {

	if (!cb||!script_name) {
		console.log('"ajax_call()": Missing parameter(s).')
		return;
	}
	
	$.ajax({
		url: 'call/'+script_name+'.php',
		data: parameters,
		type: 'post',
		dataType: 'json'
	})
	.done(function(data){
		cb(data);
	})
	.fail(function(){
		cb({
			type: 'error',
			result: 'Ajax error.'
		});
	});
	
}

/*
 * Make a file form be able to submit files in the background for uploading,
 * then receive the result in JSON.
 */
function file_uploader_form($form, cb){

	if (!$form||!cb) {
		console.log('"file_uploader_form()": Missing parameter(s).')
		return;
	}

	var iframe_id = 'hiddeniframe_'+$form.attr('id');
	$form.append('<input type="hidden" name="cb" value="'+cb+'" />');
	$form.attr('target', iframe_id);
	$form.after('<iframe id="'+iframe_id+'" src="" frameborder="0" style="display: none"></iframe>');
	
}