<?php

	function check_date_format($value) {
		$value = explode('-', $value);
		if(!is_array($value)||count($value)<3||!checkdate(intval($value[1]),intval($value[2]),intval($value[0]))) {
			throw new Exception('', 1);
		}
	}

	// Check for input length. 
	function check_length($value, $min, $max) {
		
		if (strlen($value)<$min)
			throw new Exception('', 1);

		if (strlen($value)>$max)
			throw new Exception('', 2);

	}
	
	function check_password($value, $value2, $insertmode=true) {
		
		if ( $insertmode&&(empty($value)||empty($value2)) )
			throw new Exception('', 1);
		
		if (!empty($value)&&$value!=$value2)
			throw new Exception('', 2);
		
	}
	function check_value_range($value, $array) {
		
		if (!in_array($value, $array)) {
			throw new Exception('', 1);
			
		}
		
	}
	
	function check_db_value($value, $tablename, $colname, $insertmode=true) {
		
		global $db;
		if ($insertmode) {
			$q = "select * from `{$tablename}` where `{$colname}` = '{$value}'";
		} else {
			$q = "select * from `{$tablename}` where `{$colname}` = '{$value}' and `id` != ".$db->clean($_POST['id']);
		}
		// echo $q;
		$r = $db->query($q);
		
		$num = $r->num_rows;
		$r->free();
		if ( $num>0 ) {
			throw new Exception('', 1);
		}
		
	}
	
	
	
	/* EZ NEM FOG KELLENI */
	
	

	/*
	 * These functions checks the inputs before uploading it to the database.
	 */
	function check_user_nick($value, $insertmode=true) {
		if (empty($value))
			throw new Exception('', 1);
		
		if (strlen($value)>200)
			throw new Exception('A "felhasználónév" mező maximum 200 karakteres lehet.');
		
		/* Check for users with the same name. */
			global $db;
			
			$q = "select * from users";
			
			$r = $db->query( $q );
			$all_nicks = array();
			while( $row = $r->fetch_assoc() ) {
				if ( !$insertmode&&$_POST['id']==$row['id'] )
					continue;
					
				$all_nicks[] = $row['nick'];
			}
			
			if ( in_array( $value, $all_nicks) )
				throw new Exception('A "'.$value.'" felhasználónév már foglalt.');
		
	}
	function check_product_name($value, $insertmode=true) {
		
		/* Check for users with the same name. */
		global $db;
		
		$q = "select * from products";
		
		$r = $db->query( $q );
		$all = array();
		while( $row = $r->fetch_assoc() ) {
			if ( !$insertmode&&$_POST['id']==$row['id'] )
				continue;
				
			$all[] = $row['name'];
		}
		
		if ( in_array( $value, $all) )
			throw new Exception('A "'.$value.'" terméknév már foglalt.');
		
	}

	function check_user_name($value) {/*
		if (empty($value))
			throw new Exception('A "név" mező kitöltése kötelező.'.$value);*/
		if (strlen($value)>200)
			throw new Exception('A "teljes név" mező maximum 200 karakteres lehet.');
			
	}

	function check_group_name($value) {
		
		if (empty($value))
			throw new Exception('A "név" mező kitöltése kötelező.');
		
		if (strlen($value)>100)
			throw new Exception('A "név" mező maximum 100 karakteres lehet.');
			
	}

	function check_parameter_name($value) {
		
		if (empty($value))
			throw new Exception('A "név" mező kitöltése kötelező.');
		
		if (strlen($value)>200)
			throw new Exception('A "név" mező maximum 200 karakteres lehet.');
			
	}
	function check_user_password($value, $value2, $insertmode=true) {
		if ( $insertmode&&(empty($value)||empty($value2)) )
			throw new Exception('A jelszó mezők kitöltése kötelező.');
		
		if (!empty($value)&&$value!=$value2)
			throw new Exception('Nem egyeznek meg a jelszó mezők.');
		
		if (!empty($value)&&strlen($value)<5)
			throw new Exception('A jelszó legalább 5 karakter hosszú kell legyen.');
			
	}
	
	function check_user_rights(&$value, $insertmode=true) {
		
		if (!$insertmode&&$_POST['id']==1) {
			$value = 2;
			return;
		}
		
		if ($value!=0&&$value!=1)
			throw new Exception('Ilyen jogosultság nem osztható ki.'.$value);
			
	}
	
	
	function check_category_name($value, $insertmode=true) {
		if (empty($value))
			throw new Exception('A "név" mező kitöltése kötelező.');
		
		if (strlen($value)>500)
			throw new Exception('A "név" mező maximum 500 karakteres lehet.');
		
		/* Check for users with the same name. */
			global $db;
			
			$q = "select * from categories";
			
			$r = $db->query( $q );
			$all_nicks = array();
			while( $row = $r->fetch_assoc() ) {
				if ( !$insertmode&&$_POST['id']==$row['id'] )
					continue;
					
				$all_nicks[] = strtolower( $row['name'] );
			}
			
			if ( in_array( strtolower($value), $all_nicks) )
				throw new Exception('A "'.$value.'" kategórianév már foglalt.');
	}
	
	function check_category_description($value) {
		if (empty($value))
			throw new Exception('A "leírás" mező kitöltése kötelező.');
		
		if (strlen($value)>1000)
			throw new Exception('A "leírás" mező maximum 1000 karakteres lehet.');
	}
	
	function check_category_listplace($value) {
		if (!in_array($value, array(0, 1, 2, 4, 3, 5, 6, 7)))
			throw new Exception('Ez az érték nem vihető fel.');
	}
	
?>