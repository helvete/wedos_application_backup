<?php
/**
 * Configuration class
 * TODO
 */
class Conf {

	const CONFIG_FILE_NAME = CONFIG_FILE_NAME;

	/**
	 * Config cache
	 * @var array
	 */
	static private $_cache;

	/**
	 * Get config data
	 *
	 * @param  $valueName
	 * @return PDO
	 */
	static public function get($valueName) {
		if (empty (self::$_cache)) {
			self::$_cache = self::_loadConfig();
		}

		return !empty(self::$_cache[$valueName])
			? self::$_cache[$valueName]
			: null;
	}


	/**
 	 * Retrieve config file
 	 *
 	 * @return array
 	 */
	static private function _loadConfig() {
		if (!file_exists(self::CONFIG_FILE_NAME)) {
			throw new Exception('Config file missing.');
		}
		require(self::CONFIG_FILE_NAME);
		if (empty($configArray)) {
			throw new Exception('Invalid configuration file structure.');
		}

		return $configArray;
	}
}
