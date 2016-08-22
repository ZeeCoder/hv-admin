/* Scripts needed on every page. */

var black_cloud_closeable = true;

function nbit($number, $n) {
	return ($number >> $n-1) & 1;
}

/* Black cloud hiding on esc. */
	$(document).keyup(function(e) {
		if (e.keyCode == 27) {
			black_cloud_closeable = true;
			hide_form();
		}
	});

function close_vote_cb(response) {
	if (response.type=='success') {
	  // alert('A szavazás sikeresen lezárásra került.');
	  location.reload();
	} else {
	  alert(response.result);
	}
}

function close_vote() {
	if (confirm('Biztosan lezárod a szavazást?')) {
		var input = prompt('Add meg a szavazás lezárásához szükséges jelszót.');
		if (input=='zárol') {
			ajax_call(close_vote_cb, 'execute_command', {
				cmd: 'close_vote'
			});
		} else {
			alert('Helytelen jelszó.');
		}
	}
}
function open_vote_cb(response) {
	if (response.type=='success') {
	  // alert('A szavazás sikeresen elindult.');
	  location.reload();
	} else {
	  alert(response.result);
	}
}

function open_vote() {
	if (confirm('Biztosan elindítod a szavazást? (Ne felejtsd el felvinni az új nyereményeket és újraindítani az adatbázist az alkalmazás indítása előtt.)')) {
		ajax_call(open_vote_cb, 'execute_command', {
			cmd: 'open_vote'
		});
	}
}
function reset_db_cb(response) {
	if (response.type=='success') {
	  // alert('A szavazás sikeresen elindult.');
	  location.reload();
	} else {
	  alert(response.result);
	}
}

function reset_db() {
	if (confirm('Biztosan újraindítod az adatbázist? Az összes eddig felvitt versenyző, és leadott szavazás törlésre fog kerülni.')) {
		ajax_call(reset_db_cb, 'execute_command', {
			cmd: 'reset_db'
		});
	}
}

/* Insert/modify form appearance/hide. */
	function show_form() {
		$('.black_cloud .form_aligner').mouseenter(function(){
			black_cloud_closeable=false;
		}).mouseleave(function(){
			black_cloud_closeable=true;
		});
		$('.black_cloud').fadeIn(200);
		$('body').css('overflow', 'hidden');
	}
	function hide_form() {
		$('.black_cloud')
			.fadeOut(200);
		$('body').css('overflow', 'auto');
		$('.black_cloud form').unbind('mouseenter mouseleave');
	}


/* Logout call. */
	function logout() {
		$.ajax({
			url: 'call/logout.php'
		})
		.done(function(){
			location.href=DOMAIN+ROOT+'admin/';
		})
		.fail(function(){
			alert('Ismeretlen hiba kilépéskor, kérjük később próbálja meg újra.');
		});
	}

/* If DOM loaded. */
$(function(){
	
	/* Black cloud hiding. */
		$('.black_cloud')
			.click(function(){
				if ( black_cloud_closeable )
					hide_form();
			});
	
	/* Setup ajax loaders. */
		$('.datatable').hv_ajax_loader({
			align: 'center',
			valign: 'top'
		});
		
		$('.datatable .hv-ajax-loader').css('margin-top', '50px');
		
		$('.form_aligner').hv_ajax_loader({
			align: 'center',
			valign: 'center'
		});
	
});