<?php

	function date_correction($date) {
		$date = explode('-', $date);
		
		switch ($date[1]) {
			
			case 01:
				$date[1] = 'január'; break;
			case 02:
				$date[1] = 'február'; break;
			case 03:
				$date[1] = 'március'; break;
			case 04:
				$date[1] = 'április'; break;
			case 05:
				$date[1] = 'május'; break;
			case 06:
				$date[1] = 'június'; break;
			case 07:
				$date[1] = 'július'; break;
			case 08:
				$date[1] = 'augusztus'; break;
			case 09:
				$date[1] = 'szeptember'; break;
			case 10:
				$date[1] = 'október'; break;
			case 11:
				$date[1] = 'november'; break;
			case 12:
				$date[1] = 'december'; break;
			
		}
		
		return $date[0].'. '.$date[1].' '.$date[2].'.';
		
	}

?>