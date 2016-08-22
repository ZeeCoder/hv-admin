<?php
	
	function hv_shorten($str, $len, $appendEnd=true){

		if (!($str || $len))
			return;

		if (mb_strlen($str, 'utf-8')>$len)
			return (mb_substr($str, 0, $len, 'utf-8')).(($appendEnd)?'...':'');
		
		return $str;

	}

?>