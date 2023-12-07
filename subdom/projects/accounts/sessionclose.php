<?php
session_start();
session_unset();
$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();

$prihlasen = false;

  echo "<style>";
  include "style.css";
  echo "</style>";


echo "<div id=\"endof\">byl jste odhlasen!<br />";
echo "<a href='index.php'>prihlasit zde</a></div>";

?>
