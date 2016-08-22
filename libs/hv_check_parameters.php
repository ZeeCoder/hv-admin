<?php
	/**
	 *
	 * HV Check Parameters Function ( hv_check_parameters() )
	 * Checking GET/POST (or both) parameters, throwing exception upon error.
	 *
	 * @author   Hubert Viktor
	 * @version  1.0
	 * @since    2013-04-10
	 * @todo     Conditional parameter checking. Example: '(a,b)||(c,d)' which would mean either 'a' and 'b' parameters are compulsory, or 'c' and 'd'.
	 *
	 */

	/**
	 *
	 * @param requested_params The parameters requested for checking. Delimiter: ','.
	 * @param paramtype paramtype Default: get. The parameter's type. Possible values: 'get', 'post', 'request'.
	 * @return Returns nothing, throws an Exception if a parameter is missing.
	 *
	 */
		function hv_check_parameters($requested_params, $paramtype='get') {

			if (!$requested_params)
				return false;

			$paramtype = strtolower($paramtype);

			if ($paramtype=='get')
				$a_parameters = $_GET;
			else if ($paramtype=='post')
				$a_parameters = $_POST;
			else if ($paramtype=='request')
				$a_parameters = $_REQUEST;

			$a_needed_parameters = explode(',', $requested_params);

			if (!is_array($a_parameters))
				throw new Exception('No parameter array could be get.');

			$i = 0;
			foreach ($a_needed_parameters as $key => $value) {
				if (!$a_parameters[$value])
					throw new Exception('Missing parameter: "'.$value.'"', $i);
				$i++;
			}

		}

?>