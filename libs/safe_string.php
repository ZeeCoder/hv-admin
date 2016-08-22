<?php

	function safe_string($str) {
		
		return strtolower(
			str_replace(
				' ', '-',
				preg_replace('/[^a-zA-Z0-9_ -]/s', '', to_ascii($str) )
			));
		
	}

?>