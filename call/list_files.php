<?php

	/* Result format. */
		$result = array(
			'type' => 'success',
			'result' => null
		);
	
	try {
		
		if (!isset($_REQUEST['folder']))
			throw new Exception('Missing parameter.');
			
		$result = glob($_REQUEST['folder'].'*');
		if (!$result||!is_array($result))
			throw new Exception('Missing parameter.');
		usort($result, create_function('$a,$b', 'return filemtime($b) - filemtime($a);'));
		
		foreach ($result as $key => $value) {
			$result[ $key ] = array(
				'id' => md5($value),
				'src' => $value
			);
		}
		
	} catch (Exception $e) {
		$result['type'] = 'error';
		$result['result'] = $e->getMessage();
	}
	
	
	/* Print out result. */
		header('Content-type: application/json');
		echo json_encode( $result );

?>