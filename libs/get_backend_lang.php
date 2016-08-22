<?php
	
	function get_backend_lang() {
		
		$inc = dirname(__FILE__).'/';
		
		$lang = array();
		if (is_file($inc.'../language/backend_'.LANGUAGE.'.php')) {
			include $inc.'../language/backend_'.LANGUAGE.'.php';
		}
		include $inc.'../language/backend_'.DEF_LANG.'.php';
		
		if (isset($lang[1])) {
			$lang = array_replace_recursive( $lang[1], $lang[0] );
		} else {
			$lang = $lang[0];
		}
		
		return $lang;
		
	}
	
?>