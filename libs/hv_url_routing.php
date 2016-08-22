<?php

	function hv_get_url_params(){
		$params = explode('/',  (ROOT!='/')?str_replace(ROOT, '', $_SERVER['REQUEST_URI']):$_SERVER['REQUEST_URI'] );
		/* Clean */
			foreach ($params as $key => $value) {
				if (empty($value))
					unset($params[$key]);
			}
		return array_values($params); //Correct array keys if needed.
	}

?>