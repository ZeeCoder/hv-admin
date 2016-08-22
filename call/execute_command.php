<?php

	/*
	 * Executing given sql.
	 */

	session_start();
	require('../../config.php');

	// Result format. 
	$result = array(
		'type' => 'success',
		'result' => null
	);
		

	
	try {
		if ( !isset($_SESSION['admin'])||!isset($_POST['cmd']) )
			throw new Exception('Missing parameter(s).');
			
		require('../libs/hv_mysqli_handler.php');
		$db = new HVMysqliHandler(array(
			'db_host' => DB_HOST,
			'db_name' => DB_NAME,
			'db_user' => DB_USER,
			'db_pass' => DB_PASS
		));
			
		/* Query types. */
			require '../inc/parameter_checkers.php';
				
				// User parameter checkings
				if ($_POST['cmd']=='insertcontestant'||$_POST['cmd']=='updatecontestant') {
					
					try {
						check_length($_POST['name'], 5, 500);
					} catch (Exception $e) {
						throw new Exception('A név mező hossza legalább 5, és legfeljebb 500 karakter hosszú kell legyen.');
					}/*
					try {
						check_length($_POST['city'], 2, 300);
					} catch (Exception $e) {
						throw new Exception('A váos mező hossza legalább 2, és legfeljebb 300 karakter hosszú kell legyen.');
					}*/
					$_POST['city']='';
					
				}
				
					// Insert user.
					if ($_POST['cmd']=='insertcontestant') {
						
						$q = "insert into contestants
						      	(
						      		`name`,
						      		`city`,
						      		`active`
						      	)
						      	values (
						      		'".$db->clean( $_POST['name'] )."',
						      		'".$db->clean( $_POST['city'] )."',
						      		'".$db->clean( $_POST['active'] )."'
						      	)";
						
						$db->query($q);
						$result['result'] = $db->obj->insert_id;
						
					}

					// Update user. 
					if ($_POST['cmd']=='updatecontestant') {
						
						if ( !isset($_POST['id']) ){
							throw new Exception('Missing or bad parameter(s).');
						}
						
						$q = "update contestants set 
							`name` = '".$db->clean( $_POST['name'] )."',
							`active` = '".$db->clean( $_POST['active'] )."',
							`city` = '".$db->clean( $_POST['city'] )."'
							where id = ".$_POST['id'];
						
						$result['result'] = $db->query($q);
						
					}
					
					// Delete user. 
					if ($_POST['cmd']=='deletecontestant') {
						
						if ( !isset($_POST['id']) ) {
							throw new Exception('Missing or bad parameter(s).');
						}
						
						$q = "delete from contestants where id = ".$db->clean($_POST['id']);
						
						$src = '../../img/contestants/contestant_'.$_POST['id'].'.jpg';
						if (is_file($src)) {
							unlink($src);
						}
						
						$result['result'] = $db->query($q);
						
					}

					if ($_POST['cmd']=='close_vote') {

					    $fh = fopen('../../closed.txt', 'w');

					    fwrite($fh, '');

					    fclose($fh);

					    $result['result'] = true;

					}

					if ($_POST['cmd']=='open_vote') {

						$src = '../../closed.txt';
						if (is_file($src)) {
							unlink($src);
						}

					    $result['result'] = true;

					}

					if ($_POST['cmd']=='reset_db') {

						$q = "
							truncate table `contestants`
						";
						$db->query($q);

						$q = "
							truncate table `votes`
						";
						$db->query($q);
/*
						$q = "
							update `prizes` set `text` = ''
						";
						$db->query($q);*/

						$arr = glob('../../img/contestants/*.jpg');
						if (is_array($arr)) {
							foreach ($arr as $pic) {
								unlink($pic);
							}
						}

					    $result['result'] = true;

					}
				
				
			
	} catch (Exception $e) {
		$result['type'] = 'error';
		$result['result'] = 'Hiba az adatbázisműveletek során, később próbálja meg újra.';
		
		$result['result'] = $e->getMessage();
	}
	
	
	// Print out result. 
	header('Content-type: application/json');
	echo json_encode( $result );

?>