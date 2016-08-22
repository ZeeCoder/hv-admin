<?php
	
	// Check for errors. 
	if ( $file['error'] == 4 ) {
		throw new Exception('Hiányzó fájl.');
	}
	if ( $file['error'] == UPLOAD_ERR_INI_SIZE ) {
		throw new Exception('A fájl mérete túllépi a megengedett határt!');
	}
	
	if ($file['error'] > 0)
		throw new Exception('Hiba a fájl feltöltésekor. Kód: '.print_r($file['error'], true));
	
	
	// Check the type. 
	$allowed_type=false;
	foreach ($types as $key => $value) {
		if (strpos($file["type"], $value)) {
			$allowed_type=true;
			break;
		}
	}
	if (!$allowed_type)
		throw new Exception('Nem engedélyezett fájlkiterjesztés. Engedélyezett fájltípusok: '.implode(', ', $types));
	
	// Parameters checked, start working with the configuration.
	$upped_filenames = array();
	foreach ($confs as $conf) {
					
		// Check directory. 
		$dir = $conf['dir'];
		if ( !is_dir($dir) ) {
			mkdir($dir);
		}
		
		// Target file. 
		$moveto = $dir.$conf['name'];
		if(file_exists($moveto)) {
			unlink($moveto);
		}
		
		// Converting to jpg format and save. 
		$image = new HVImage( $file['tmp_name'] );
		$image->resize_fit_or_bigger(453, 469);
		$image->crop(453, 469, array(
			'type' => 'aligns',
			'vertical' => 'center',
			'horizontal' => 'center',
		));
		$image->save($moveto);
		
		$src = $moveto;
			
		// Image corrections. 
		/*if (isset($conf['corrections'])) {
			foreach ($conf['corrections'] as $key => $value) {
				if (!is_array($value)) {
					$value = array($value);
				}
				call_user_func_array($key, $value);
			}
		}*/

		
		$upped_filenames[] = $moveto;
	}
	
	unlink( $file['tmp_name'] );

?>