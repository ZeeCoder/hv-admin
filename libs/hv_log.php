<?php
	
	/**
	 * Logging to the database.
	 * If it's unsuccesful, it logs to the harddrive. (+ notification email to the developer.)
	 * If the log's level is 'warning', or 'error', the developer gets a notification email about it.
	 *
	 * @param 'message': The message to log.
	 * @param 'location': The location where the logging occured.
	 * @param 'type': The type of the log.
	 *
	 */

	function hv_log($message, $location, $type='notice') {

		// Handle parameters
		$type = strtolower($type);
		if ($type!='notice'&&$type!='warning'&&$type!='error') {
			$type = 'notice';
		}

		if (isset($subdirs)&&!is_array($subdirs)) {
			$subdirs = array($subdirs);
		}

		try {
			// Try logging into the database.
			require_once 'hv_mysqli_handler.php';
			$db = new HVMysqliHandler(array(
				'db_host' => DB_HOST,
				'db_name' => DB_NAME,
				'db_user' => DB_USER,
				'db_pass' => DB_PASS
			));
				$message = $db->clean($message);
				$q = "insert into log(`location`, `type`, `message`) values('{$location}', '{$type}', '{$message}')";
				$db->query($q);

			$db->close();
		} catch (Exception $e) {
			// Error logging into the database, log to the harddrive.

			// Setup path.
			$target = dirname(__FILE__);
			$subdirs = array(
				'../log',
				$type,
				$location,
				date('Y-m-d')
			);

			foreach ($subdirs as $key => $value) {
				$target .= '/'.$value;
				if (!is_dir($target)) {
					mkdir($target);
				}
			}

			$target .= '/log.log';

			ini_set('error_log', $target);
			error_log($message);

			// Error logged in the harddrive, not in the DB -> notify the developer.
			require_once 'PHPMailer/class.phpmailer.php';

			$mail = new PHPMailer;

			$mail->From = 'info@zalehy.com';
			$mail->FromName = 'Zalehy Logger - '.PALIAS;
			$mail->AddAddress('error@zalehy.com', 'Zalehy Error');  // Add a recipient

			//$mail->IsHTML(true);                                  // Set email format to HTML

			$mail->SetLanguage('hu', 'PHPMailer/language/');
			$mail->CharSet = 'UTF-8';

			$mail->Subject = PALIAS." ({$location}): Log file a merevlemezen.";
			$mail->Body    = "A következő hibát nem sikerült menteni az adatbázisba, helyette a merevlemezre került:\n".$message."\n\nAdatbázishiba: '".$e->getMessage()."'\n\nQuery: '{$q}'";

			$mail->Send();

		}
			
		// If it's an error or warning, notify the developer!
		if ($type=='warning'||$type=='error') {
			require_once 'PHPMailer/class.phpmailer.php';

			$mail = new PHPMailer;

			$mail->From = 'info@zalehy.com';
			$mail->FromName = 'Zalehy Logger - '.PALIAS;
			$mail->AddAddress('error@zalehy.com', 'Zalehy Error');  // Add a recipient

			//$mail->IsHTML(true);                                  // Set email format to HTML

			$mail->SetLanguage('hu', 'PHPMailer/language/');
			$mail->CharSet = 'UTF-8';

			$mail->Subject = PALIAS." ({$location}): ".$type;
			$mail->Body    = $message;

			$mail->Send();
		}

	}

?>