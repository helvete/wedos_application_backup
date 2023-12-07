<?php session_start();
 if(!setcookie("loginC", $loginH, 0, "/")){
    echo "neni mozne upect susenku!";
    exit();
 }
$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
?>
