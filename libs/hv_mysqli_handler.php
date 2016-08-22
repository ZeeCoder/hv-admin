<?php
	/**
	 *
	 *	HV - Mysqli Handler
	 *	New version, works with the PHP predefined constants, called:
	 *	DB_HOST, DB_NAME, DB_USER, DB_PASS
	 *
	 *	@author: Hubert Viktor
	 *	@since: 2012
	 *
	 */

	/*
		A nice function to make our job easier.
		Checking for multiple key's existence in an array.
	*/
		function array_keys_exist($keyArray, $array) {
			foreach($keyArray as $key) {
				if(!array_key_exists($key, $array))
					return false;
			}
			return true;
		}
	
	class HVMysqliHandler {
		var $obj;
		var $c;
		
		/*
			Setting up the obj.
			If no parameter is given, it won't automatically call the 'open' method.
		*/
		function __construct( $c, $open=true ) {
			
			if (!(
				isset($c)&&
				is_array($c)&&
				array_keys_exist(array(
					'db_host','db_name','db_user','db_pass'
				), $c)
			) && !isset( $this->c ) )
				throw new Exception('No configuration could be set up. The following keys are needed: "db_host", "db_name", "db_user", "db_pass", in a form of an associative array.');
			
			if (isset($c)) {
				$this->c = $c;
			}
			
			if ($open) {
				$this->open( );
			}
		}
		
		/*
			Starts the obj.
			The constructor calls this function too.
		*/
		function open( ) {
			
			$c = $this->c;
			
			$this->obj = new mysqli( $c['db_host'], $c['db_user'], $c['db_pass'], $c['db_name'] );
			if ($this->obj->connect_errno)
				throw new Exception( 'MysqliHandler Error: Couldn\'t setup obj. (' . $this->obj->connect_errno . ": " . $this->obj->connect_error . ')', -101 ); return;
			
			return true;
		}
		
		/*
			Executes a query.
		*/
		function query( $q ) {
			$this->obj->set_charset( 'utf8' );
			$r = $this->obj->query( $q );
			if ( $r )
				return $r;
			else
				throw new Exception( 'MysqliHandler Error: Couldn\'t execute given query. ('.($this->obj->error).')', -102);
			return false;
		}
		
		/*
			Closing the obj.
		*/
		function close() {
			$this->obj->close();
		}
		
		/*
			Clean the incoming data.
		*/
		function clean( $str ) {
			$str = trim( $str );
			if(get_magic_quotes_gpc())
				$str = stripslashes( $str );
			return $this->obj->real_escape_string( $str );
		}
	}
	
?>