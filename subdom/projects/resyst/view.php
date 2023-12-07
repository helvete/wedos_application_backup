<?php
/**
 * View class
 */
class View {

	/**
 	 * Head meta tags array
 	 */
	static private $_headLine = array();


	/**
	 * Page start
	 *
	 * @return void
	 */
	static public function printPageStart() {
		echo <<<HTM
<html>
	<head>
HTM;
		foreach (self::$_headLine as $line) {
			echo $line;
		}
		echo <<<HTM
	</head>
	<body>
HTM;
	}


	/**
	 * Page end
	 *
	 * @return void
	 */
	static public function printPageEnd() {
		echo <<<HTM
	</body>
</html>
HTM;
	}


	/**
	 * Set head meta tags
	 *
	 * @param  string	$line
	 * @return void
	 */
	static public function addHeadLine($line) {
		self::$_headLine[] = $line;
	}
}
$ss = '<link rel="stylesheet" href="r2stylesheet.css" type="text/css" '
	. 'media="screen" />';
View::addHeadLine($ss);
