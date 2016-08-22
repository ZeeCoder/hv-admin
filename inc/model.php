<?php
	
	$db = new HVMysqliHandler(array(
		'db_host' => DB_HOST,
		'db_name' => DB_NAME,
		'db_user' => DB_USER,
		'db_pass' => DB_PASS
	), false);

	$params = hv_get_url_params();
	
	@DEFINE('ADMIN', $_SESSION['admin']);
	
	if (ADMIN) {
		
		// Handling url.
			
			// Redirect to base url if parameter count isn't right. 
			/*if (count($params)!=2) {
				header('HTTP/1.1 301 Moved Permanently');
				header('Location: '.BASE_URL.'admin/'.key($allowed_subpages) );
				exit();
			}*/
			
			// Check if parameters are correct. 
			$allowed_page = false;
			if (isset($params[1])) {
				foreach ($allowed_subpages as $key => $value) {
					if ($key==$params[1]) {
						$allowed_page = true;
					}
				}
			}
			
			if ( !$allowed_page ) {
				header('HTTP/1.1 301 Moved Permanently');
				header('Location: '.BASE_URL.'admin/fooldal');
				exit();
			}
			
			
			DEFINE('PAGE', $params[1]);
				
				
	} else {
		
		if (count($params)>1) {
			header('HTTP/1.1 301 Moved Permanently');
			header('Location: '.BASE_URL.'admin');
		}
		
	}

	// Setup styles and scripts according to the url parameters. 
		// Default.
		if ( ADMIN ) {
			$styles = array(
				'../css/admin.less',
				'../css/cssreset-min.css'
			);
			$scripts = array(
				'../js/hv_ajax_loader/hv_ajax_loader.js',
				'../js/admin.js',
				'../js/all.js'
			);
			if (isset($params[1])) {
				$styles[] = '../css/'.safe_string($params[1]).'.less';
				$scripts[] = '../js/'.safe_string($params[1]).'.js';
			}

		} else {
			$styles = array(
				'../css/login.less'
			);
			$scripts = array(
				'../js/login.js'
			);
		}
			
	// Concatenating for the script. 
	$styles = implode(',', $styles);
	$scripts = implode(',', $scripts);
	
?>