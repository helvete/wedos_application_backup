<?php
include "./statLib.php";

// get referer
$referer = !empty($_SERVER['HTTP_REFERER'])
	? $_SERVER['HTTP_REFERER']
	: null;

// logout action
if (!empty($_POST['logout'])) {
	unset($_SESSION['user']);
	session_destroy();
	$referer = $referer
		? $referer
		: 'index.php';
	header("Location: $referer");
	exit();
}

$valid = true;

// has the form just been submitted?
if (!empty($_POST['loginAttempt'])) {
	$login = !empty($_POST['login'])
		? $_POST['login']
		: false;
	$passwd = !empty($_POST['password'])
		? $_POST['password']
		: false;
	$referer = !empty($_POST['referer'])
		? $_POST['referer']
		: $referer;

	if ($login !== false && $passwd !== false) {
		$valid = AuthLib::validateLogin($login, $passwd);
		if ($valid) {
			$_SESSION['user'] = $login;
			if ($referer) {
				$refererParts = explode(':', $referer);
				array_shift($refererParts);
				$refererParts = array_merge(array('https'), $refererParts);
				$referer = implode(':', $refererParts);
			} else {
				$referer = 'index.php';
			}
			header("Location: $referer");
			exit();
		}
		sleep(1);
	}
}

printLoginForm($referer, $valid);


/**
 * Print html of login form
 *
 * @param  string	$referer
 * @return void
 */
function printLoginForm($referer = '', $valid) {
	if (!$valid)
	echo "<div>Invalid login credentials</div>";
	echo <<<HTM
<form action="" method="POST">
	<label for="login">login:</label>
	<input id="login" type="text" class="login-name" name="login" autocomplete="off" />
	<label for="password">password:</label>
	<input id="password" type="password" class="login-password" name="password" autocomplete="off" />
	<input type="hidden" name="referer" value="$referer" />
	<input type="submit" name="loginAttempt" value="log in">
</form>
HTM;
}

?>
