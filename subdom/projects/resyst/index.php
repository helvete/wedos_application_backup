<?php
$time = -microtime(true);
include "./init.php";
include APPLICATION_PATH . "/config.php";
include APPLICATION_PATH . "/pdo_connect.php";
include APPLICATION_PATH . '/post.php';
include APPLICATION_PATH . '/posts.php';
include APPLICATION_PATH . '/tag.php';
include APPLICATION_PATH . "/baseController.php";
include APPLICATION_PATH . "/publicController.php";
include APPLICATION_PATH . "/adminController.php";
include APPLICATION_PATH . "/statLib.php";
include APPLICATION_PATH . "/view.php";

$login = AuthLib::getLoggedUser();
$displayName = AuthLib::getDisplayNameByLogin($login);
if ($displayName) {
	$ctrller = new AdminController();
}
$ctrller = new PublicController();


View::addHeadLine('<title>R2</title>');
View::printPageStart();
echo $displayName
	? "<div class=\"userAction\">Logged as <b>$displayName</b> &nbsp; "
	: '<div>';
AuthLib::getAction();

echo '</div>';

$ctrller->printHtml();

$time += microtime(true);
echo '<div class="stats">Memory used: ' . memory_get_usage(true)/1024
	. "kiB | Time consumed: {$time}s </div>";

View::printPageEnd();
