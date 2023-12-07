<?
	// auth token
	define('AUTH_TOKEN', '<token>');

	$vars = array(
		'token',
		'emailsubject',
		'emailbody',
		'sender',
		'addressee',
	);
	// assign vars using POST data
	foreach ($vars as $varName) {
		$$varName = !empty($_POST[$varName])
			? $_POST[$varName]
			: false;
	}

	/**
 	 * Handle parameters
 	 */

	// Unauthorized
	if (!$token || $token !== AUTH_TOKEN) {
		echo "Unauthorized. Please supply valid token for authorization";
		$message = "WARN Attempt to unauthorized use of service from "
			. "{$_SERVER['REMOTE_ADDR']} ({$_SERVER['HTTP_REFERER']}).";
		logAction('esa_common.log', $message);
		exit();
	}

	// Are we able to send an email?
	if (!$emailsubject || !$emailbody || !$addressee) {
		echo "Insufficient email properties supplied. Missing params:";
		$pars = '';
		foreach(array('emailsubject', 'emailbody', 'addressee') as $varName) {
			if (!$$varName) {
				$pars .= " $varName,";
			}
		}
		echo substr($pars, 0, -1) . '.';

		$message = "WARN Missing POST params on request from "
			. "{$_SERVER['REMOTE_ADDR']} ({$_SERVER['HTTP_REFERER']}).";
		logAction('esa_common.log', $message);

		exit();
	}

	// set default sender identifier if empty
	if (!$sender) {
		$message = "NOTICE Missing sender identifier on request from "
			. "{$_SERVER['REMOTE_ADDR']} ({$_SERVER['HTTP_REFERER']}).";
		logAction('esa_common.log', $message);
		$sender = 'Esa v0.1 <no-reply@bahno.net>';
	}

	/**
 	 * Send email
 	 */
	$headers   = array();
	$headers[] = "MIME-Version: 1.0";
	$headers[] = "Content-type: text/plain; charset=UTF-8";
	$headers[] = "From: $sender";
	$headers[] = "Subject: $emailsubject";
	$headers[] = "X-Mailer: PHP/" . phpversion();

	$result =
		mail($addressee, $emailsubject, $emailbody, implode("\r\n", $headers));

	$message = $result
		? "OK Email '$emailsubject' successfully sent to '$addressee'."
		: "ERROR Sending email to '$addressee' failed.";

	logAction('esa_common.log', $message);

	/**
 	 * Log action
 	 *
 	 * @param  string	$fileName
 	 * @param  string $logEntryText
 	 * @return void
 	 */
	function logAction($fileName, $logEntryText) {
		$time = date('Y-m-d H:i:s');
		file_put_contents($fileName, "$time: $logEntryText\n", FILE_APPEND);
	}

	function printDebugForm() {
		echo <<<HTM
<form method="POST">
	<input type="hidden" name="sender" value="Email Sender API <no-reply@bahno.net>" />
	<input type="hidden" name="token" value="5It8VdndMKiG8QfBsYCQBw" />
	<input type="hidden" name="emailsubject" value="Usage instructions" />
	<input type="hidden" name="emailbody" value="Request info:
		URL: https://m.bahno.net
		Method: POST

		Mandatory params:
		'token' -> '5It8VdndMKiG8QfBsYCQBw'
		'emailsubject' -> 'Your subject'
		'emailbody' -> 'Actual email body'
		'addressee' -> 'recipient@email.com'

		Optionial params:
		'sender' -> 'Named part <emailaddr@part.com>'

		Notes:
		1/ All actions are logged
		2/ 0 Bytes ('') response is returned on success, error message otherwise
		3/ All incoming requests are automatically redirected to HTTPS
	" />
	<input type="text" name="addressee" value="" />
	<input type="submit" name="sent" value="Send" />
</form>
HTM;
}
