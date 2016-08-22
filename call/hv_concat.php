<?php
	/**
	 *
	 * HV Concatenation
	 * Concatenating given files. (CSS or JS)
	 *
	 * @author   Hubert Viktor
	 * @version  1.0
	 * @since    2013-04-10
	 *
	 */

	/**
	 *
	 * @param files The files that needs to be concatenated. Delimiter: ','.
	 * @param type File type, needed for the content-type information. Default: 'css'. Possible values: 'css', 'js'.
	 * @param content-type Custom content-type, if given, the script ignores the 'type' parameter and uses this one instead.
	 * @return Returns nothing, throws an Exception if a parameter is missing.
	 *
	 */
	
	// header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 31536000));
	
	if (isset($_GET['content-type']))
		header('Content-Type: '.$_GET['content-type']);

	try {
		/* Including HV Parameter Checker. */
			$value = '../libs/hv_check_parameters.php';
			if (!@include($value))
				throw new Exception('Missing "'.$value.'" file, cannot include.');

		/* Including HV Includer. */
			$value = '../libs/hv_include.php';
			if (!@include($value))
				throw new Exception('Missing "'.$value.'" file, cannot include.');


		/* Checking for 'files' parameter. */
			hv_check_parameters('files');

		if (!isset($_GET['content-type'])) {
			if ($_GET['type']=='css')
				header("Content-type: text/css");
			else if ($_GET['type']=='js')
				header('Content-Type: application/javascript');
			else
				header('Content-Type: '.$_GET['content-type']);
		}

		/* Including the needed files. */
			hv_include($_GET['files']);


	} catch (Exception $e) {
		echo '/*'.$e->getCode().': '.$e->getMessage().'*/';
	}

?>