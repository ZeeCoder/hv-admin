<?php
	/**
	 *
	 * HV Include Function ( hv_include() )
	 * Includes the given list of files.
	 *
	 * @author   Hubert Viktor
	 * @version  1.0
	 * @since    2013-04-10
	 *
	 */

	/**
	 *
	 * @param include_list A list of files need to be included. Delimiter: ','.
	 * @return Returns nothing, throws an Exception if a parameter is missing.
	 *
	 */
		function hv_include($include_list) {

			if (!$include_list)
				return false;

			$a_include_list = explode(',', $include_list);

			foreach ($a_include_list as $key => $value) {
				if (!@include($value))
					throw new Exception('Couldn\'t find "'.$value.'", cannot include.');
			}

		}

?>