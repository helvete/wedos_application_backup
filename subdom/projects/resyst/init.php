<?php
define('APPLICATION_PATH', realpath(__DIR__));
define('CONFIG_FILE_NAME', APPLICATION_PATH . '/configuration.data.php');
define('BASE_URI', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")
	. "://{$_SERVER['HTTP_HOST']}". explode('?', $_SERVER['REQUEST_URI'])[0]);
?>
