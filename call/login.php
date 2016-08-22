<?php
	
	session_start();
	header('Content-type: text/plain');


	try {
		
		// if ( $_GET['nick']!='oXXo' || hash('sha512', $_GET['pass'] ) != 'b28b37426ea7ec8e266e1b8348357f5bbe2aa989b346eeebc39eb7a2798eef75119ec4d68882407724c28f6d1022378e42e937141e3d1770a927092fbe3103ec' )
		if ($_GET['pass']!='izgágák!') {
			throw new Exception('Hibás belépési adatok.');
		}
		
		$_SESSION['admin']=true;
		echo 1;
		
	} catch (Exception $e) {
		echo 0;
		// echo $e->getMessage();
	}

?>