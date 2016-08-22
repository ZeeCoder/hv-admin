
// GLOBALS 

var g_modify_id = false;
	
// GLOBALS 

// Reset form, switch to insert mode. 
function reset_form($form) {
    $form.find('input:text, input:password, input:file, select, textarea').val('');
    $form.find('input:radio, input:checkbox')
         .removeAttr('checked').removeAttr('selected');
         
	g_modify_id = false;
	$('#pictures').hide();
	$('#file_upload input[type=file]').val('');
}
	
// Resetting form, then show it. 
function new_record() {
	reset_form($('#upload_form'));
	$('#upload_form .info').hide();
	show_form();
}

function sync_table( data ) {

	if (data.type=='error') {
		alert('Hiba a tábla lekérésekor.');
		return;
	}
	
	$news_table = $('.datatable tbody');
	$.each(data.result, function(i, d){
		
		if ( !$('#row_'+d.id).length || $('#row_'+d.id).html()=='' ) {
				if ( !$('#row_'+d.id).length ) {
					var row =
						$('<tr/>')
							.attr('id', 'row_'+d.id)
							.data('data', d);
				} else {
					var row = $('#row_'+d.id).data('data', d);
				}
				
				
				var content =
					'<td>'+d.name+'</td>'+
					'<td class="textcentered">'+(d.active==1 ?'Igen':'Nem')+'</td>'+
					'<td class="textcentered">'+d.added+'</td>'+
					'<td class="mod">M</td>'+
					'<td class="del">T</td>';
				
				
				row.append(content);
			if ( !$('#row_'+d.id).length ) {
				$('.datatable.allusers tbody').prepend(row);
			}
		}
	});
	setTimeout(function(){
		$('.datatable').hv_ajax_loader('toggle_loading', 'off');
	}, 100);
}

function sync_call() {
	$('.datatable').hv_ajax_loader('toggle_loading', 'on');
	
	ajax_call( sync_table, 'retrieve_data', { cmd: 'allcontestants' } );
}


function submit_end(data) {
	
	if (!data||data.type == 'success') {
		if (g_modify_id) {
			$('#pictures img')
				.attr('src', '')
				.attr('src', BASE_URL+'img/contestants/contestant_'+g_modify_id+'.jpg?r='+Math.random() );
			$('#pictures img').load(function(){
				$(this).parent().show();
			});
			$('#row_'+g_modify_id).empty();
			$('#file_upload input[type=file]').val('');
		} else {
			hide_form();
		}
		
	} else {
		alert(data.result);
	}
	
	sync_call();
	setTimeout(function(){
		$('.form_aligner').hv_ajax_loader('toggle_loading', 'off');
	}, 100);
}

$(function(){

	// Setup uploader form. 
	file_uploader_form( $('#file_upload'), 'submit_end' );
		
	// Upload form events. 
	$('#submitbtn').click(function(e){
		$('.form_aligner').hv_ajax_loader('toggle_loading', 'on');
		e.preventDefault();
		
		var params = {
			name: $('#name').val(),
			age: $('#age').val(),
			city: $('#city').val(),
			active: ($('#active').prop('checked')==true ?1:0)
		};
		
		if (g_modify_id) {
			params.cmd = 'updatecontestant';
			params.id = g_modify_id;
		} else {
			params.cmd = 'insertcontestant';
		}
		
		ajax_call(function(result){
			
				if (result.type=='error') {
					setTimeout(function(){
						$('.form_aligner').hv_ajax_loader('toggle_loading', 'off');
					}, 100);
					alert(result.result);
				} else {
					
					if ( $('#file_upload input[type=file]').val()!='' ) {
						if (!g_modify_id) {
							$('#id').val( result.result );	
						}
						$('#file_upload').submit();
					} else {
						submit_end();
					}
					
				}
				
			},
			'execute_command',
			params
		);
	});
		
	// Modify/Delete events. 
	$('.datatable').click(function(e){
		// Modify event. 
		if ( $(e.target).hasClass('mod') ) {
			var $row = $(e.target).parent();
			var data = $row.data().data;
			$form = $('#upload_form');
			
			reset_form( $form );
			
			g_modify_id = data.id;
			
			$('#id').val( data.id );
			$('#name').val( data.name );
			$('#age').val( data.age );
			$('#city').val( data.city );
			if (data.active==1) {
				$('#active').prop('checked', true);
			}
			
			$('#pictures').hide();
			$('#pictures img')
				.attr('src', '')
				.attr('src', BASE_URL+'img/contestants/contestant_'+data.id+'.jpg?r='+Math.random() );
			$('#pictures img').load(function(){
				$(this).parent().show();
			});
			
			// $('#upload_form .info').show();
			show_form();
			
		}
			
		// Delete event. 
		if ( $(e.target).hasClass('del') ) {
			var $row = $(e.target).parent();
			var data = $row.data().data;
			
			if ( !confirm('Biztosan törlöd a versenyzőt: "'+data.name+'"?') )
				return;
			
			$('.datatable').hv_ajax_loader('toggle_loading', 'on');
			
			$row.css('opacity', .8);
			
			ajax_call(function(result) {
				if (result.type=='success') {
					setTimeout(function(){
						$('.datatable').hv_ajax_loader('toggle_loading', 'off');
						$('#row_'+data.id)
							.animate({'opacity': 0}, 200, function(){
								$(this).empty().animate({
									'height': 0
								}, 200, function() {
									$(this).remove();
								})
							});
					}, 100);
				} else {
					setTimeout(function(){
						$('.datatable').hv_ajax_loader('toggle_loading', 'off');
					}, 100);
					alert("Hiba a versenyző törlésekor.\n"+result.result);
				}
			},
			'execute_command',
			{
				cmd: 'deletecontestant',
				id: data.id
			});
		}
	});
				
	sync_call();
	
});