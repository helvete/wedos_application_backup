<?php
/**
 * DBConnector class
 * TODO
 */
class DBConnector {

	// connection string constants
	const DSN = 'mysql:dbname=d34308_001;host=wm25.wedos.net';
	const USER = 'w34308_001';
	const PASSWORD = '<password>';

	/**
	 * PDO cache holder
	 * @var PDO
	 */
	static private $_pdo;

	/**
	 * Get PDO instance
	 *
	 * @return PDO
	 */
	static public function getPDO() {
		if (empty (self::$_pdo)) {
			self::$_pdo = new PDO(self::DSN, self::USER, self::PASSWORD);
		}

		return self::$_pdo;
	}
}
