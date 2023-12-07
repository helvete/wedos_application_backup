<?php

session_start();


//echo "<br />cookie login:" . $_COOKIE['loginC'];
 
//exit();

if ($_SESSION['ip'] == $_SERVER['REMOTE_ADDR'] && $_SESSION['login'] == $_COOKIE['loginC'] && strlen($_SESSION['login'])>0){

  $prihlasen = true;
}
else {
  
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
  echo "prohehlo odhlaseni!";  
}


?>
