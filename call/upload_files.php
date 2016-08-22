<?php

	/*
	 * Works with a single file input.
	 */

	require '../../config.php';
	require '../inc/hv_image.php';
	require '../inc/image_procedures.php';

	// Result format. 
	$result = array(
		'type' => 'success',
		'result' => 'success'
	);
	$upped_filenames = array();
	
	// Allowed types. 
	$types = array(
		'jpg', 'jpeg', 'png', 'gif'
	);
	
	try {
		session_start();
		
		if ( !isset($_SESSION['admin'])||!isset($_POST['cb'])||!isset($_POST['cmd']) )
			throw new Exception('Missing parameter(s).');
		
		

		/*
		 * Configuration options:
		 * 'dir': The directory to upload to.

		 // Just string mode with one uploaded file.
		 * 'name':        if not set the file's name will be the original's md5 hash;
		 *                if set the file's name will be the specified string, or
		 *                if it's an array, it'll depend on the following options.
		 *         'prefix': The name's prefix.
		 *         'infix': The name's infix.
		 *         'suffix': The name's suffix.

		 * 'corrections': Call corrections on the image, from the image_corrections.php file's functions.
		 *                Syntax: 'function_name' => array( 'param1'=>1, 'param2'=>2 ), ...
		 *         
		 */
		
		// Getting the file information. 
		if (isset($_FILES['file'])) {
			$_FILES['files'] = array($_FILES['file']);
			$filesnum = 1;
		} else {
			$filesnum = count($_FILES['files']['name']);
		}
		

		// Configurations.
		if ($_POST['cmd']=='upload_userfile') {

			if ( !isset($_POST['id']) )
				throw new Exception('Missing parameter(s).');

			$confs = array(
				array(
					'dir' => '../../img/users/',
					'name' => 'user_'.$_POST['id'].'.jpg',
					'corrections' => array(
							'crop_pic' => array(
								&$src,
								225,
								190
							)
					)
				)/*,
				array(
					'dir' => '../../img/users/',
					'name' => 'user_'.$_POST['id'].'_big.jpg',
					'corrections' => array(
							'crop_pic' => array(
								&$src,
								225,
								380
							)
					)
				)*/
			);
		}
		
		for ( $i=0;$i<$filesnum;$i++ ) {
			if (!isset($_FILES['file'])) {
				foreach ($_FILES['files'] as $key => $value) {
					$file[$key] = $value[$i];
				}
			} else {
				$file = $_FILES['files'][$i];
			}
		
			// Call file handler.
			require 'upload_file_operations.php';
			
		}
			
		// Result 
		$result['result'] = $upped_filenames;
		
		
	} catch (Exception $e) {
		$result['type'] = 'error';
		$result['result'] = $e->getMessage();
	}

// Calling JS callback with results. 
echo '<script>parent.'.$_POST['cb'].'('.json_encode( $result ).')</script>';