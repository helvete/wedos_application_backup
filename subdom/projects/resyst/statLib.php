<?php
/**
 * Authentication library class
 */
class AuthLib {

	/**
	 * Display login / logout button
	 *
	 * @return void
	 */
	static public function getAction() {
		$links = array();
		if (isset($_SESSION['user'])) {
			$links[] = array(
				'action' => 'login.php',
				'operations' => array(
					'logout' => 1,
				),
				'submit-val' => 'Logout',
			);
			$links[] = array(
				'action' => '',
				'operations' => array(
					'listActions' => 1,
				),
				'submit-val' => 'Actions',
			);
		} else {
			$links[] = array(
				'action' => 'login.php',
				'operations' => array(),
				'submit-val' => 'Login',
			);
		}
		foreach ($links as $link) {
			include './postLink.template.php';
		}
	}


	/**
	 * Get logged user if there is one
	 *
	 * @return string|false
	 */
	static public function getLoggedUser() {
		return isset($_SESSION['user'])
			? $_SESSION['user']
			: false;
	}


	/**
	 * Validate login credentials
	 *
	 * @param  string	$login
	 * @return string|false
	 */
	static public function getDisplayNameByLogin($login) {
		if ($login === false) {
			return $login;
		}
		include_once "./pdo_connect.php";
		$pdo = DBConnector::getPDO();

		$query = "
			SELECT display_name
			FROM resyst_user
			WHERE login = :login
			";
		$stmt = $pdo->prepare($query);
		$stmt->execute(array(
			':login' => $login,
		));

		return $stmt->fetch(PDO::FETCH_COLUMN);
	}


	/**
	 * Validate login credentials
	 *
	 * @param  string	$login
	 * @param  string	$password
	 * @return string|false
	 */
	static public function validateLogin($login, $password) {
		include_once "./pdo_connect.php";
		$pdo = DBConnector::getPDO();

		$query = "
			SELECT COUNT(1)
			FROM resyst_user
			WHERE login = :login
				AND password = MD5(:password)
			";
		$stmt = $pdo->prepare($query);
		$stmt->execute(array(
			':login' => $login,
			':password' => $password,
		));

		return (int)$stmt->fetch(PDO::FETCH_COLUMN);
	}
}
//TODO:FIXME:Called every time
ini_set('session.cookie_httponly', true);
ini_set('session.cookie_secure', true);
session_start();
session_regenerate_id();
