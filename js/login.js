
$(function(){
	
	$('#login_form').submit(function(e){
		e.preventDefault();
		$.ajax({
			url: 'call/login.php',
			data: {
				'nick': $('#nick').val(),
				'pass': $('#pass').val()
			}
		})
		.done(function(data){
			if (data!=1)
				alert('Sikertelen belépés.');
			else
				location.href=DOMAIN+ROOT+'admin/';
		})
		.fail(function(){
			alert('Ismeretlen hiba a belépéskor, kérjük később próbálja meg újra.');
		});
	});
	
});
