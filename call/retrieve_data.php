<?php

	/*
	 * Retrieving data based on the parameters.
	 */
	
	session_start();
	
	include('../../config.php');

	// Result format. 
	$result = array(
		'type' => 'success',
		'result' => null
	);
		
	
	try {
		if ( !isset($_SESSION['admin'])||!isset($_POST['cmd']) )
			throw new Exception('Missing parameter(s).');
		
		// Setup database connection. 
		include('../libs/hv_mysqli_handler.php');
		$db = new HVMysqliHandler(array(
			'db_host' => DB_HOST,
			'db_name' => DB_NAME,
			'db_user' => DB_USER,
			'db_pass' => DB_PASS
		));
		
		// Data retrievals 
			// All user retrieval. 
			if ($_POST['cmd']=='allcontestants') {
				
				$q = "select * from contestants order by id asc";
				$r = $db->query( $q );
				if ( gettype($r)=='boolean' ) {
					$result['result'] = $r;
				} else {
					$result['result'] = array();
					while( $row = $r->fetch_assoc() ) {
						$result['result'][] = $row;
					}
				}
				
			}
			
	} catch (Exception $e) {
		$result['type'] = 'error';
		$result['result'] = $e->getMessage();
		
		
		$result['type'] = 'error';
		$result['result'] = 'Hiba az adattábla lekérése során, később próbálja meg újra.';

		ini_set('error_log', '../log/retrieve_data.php.log');
		error_log($e->getMessage());
	}
	
	// Print out result. 
	header('Content-type: application/json');
	echo json_encode( $result );

?>