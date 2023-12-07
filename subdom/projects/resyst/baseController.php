<?php
/**
 * Base controller
 */
class BaseController {

	/**
	 * Command queue
	 * @var array
	 */
	protected $_queue = array();

	/**
	 * Construct
	 */
	public function __construct() {
		$_PARAM = $_GET + $_POST;
		foreach (get_class_methods($this) as $methodName) {
			if (array_key_exists($methodName, $_PARAM)) {
				$this->_queue[$methodName] = $_PARAM[$methodName];
				// temporary
				$this->$methodName($_PARAM[$methodName]);
			}
		}
	}


	/**
	 * is action in action queue?
	 *
	 * @param  string	$actionCode
	 * @return bool
	 */
	public function inQueue($actionCode = null) {
		return array_key_exists($actionCode, $this->_queue);
	}
}
