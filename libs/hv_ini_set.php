<?php

	function hv_ini_set($name, $src='logs/') {
		if (!isset($name))
			return;

		if (!is_dir($src))
			mkdir($src);

		ini_set('error_log', $src.$name);
	}

?>