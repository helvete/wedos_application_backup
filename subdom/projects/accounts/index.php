<?php
  include "sessionstart.php";
	include "connectfile.php";

  echo "<style>";
  include "style.css";
  echo "</style>";


//var_dump($_SERVER);
//echo hmac_md5('heslo', 'pepper');
//exit;

if ($_SESSION['ip'] == $_SERVER['REMOTE_ADDR'] && $_SESSION['login'] == $_COOKIE['loginC'] && strlen($_SESSION['login'])>0){
}
else{
  $chall = time();
  mysql_query("INSERT INTO challenges(id) VALUES(". $chall .")") or die("nepodarilo se zapsat challenge do databaze, nebude mozne se prihlasit");
  
  $vcera = time() - (60*60*24);
  mysql_query("DELETE FROM challenges WHERE id < ". intval($vcera));
  //echo mysql_affected_rows();
}

if ($typ == 'vlozit') {
  session_destroy();
  
  if(filter_var($mailH, FILTER_VALIDATE_EMAIL)){

    $casek = Date("Y-m-d H:i:s");

    mysql_query("INSERT INTO account(login, heslo, datum, mail, aktivni) VALUES('" . $loginH . "', '" . $hesloH . "', '" . $casek . "', '" . $mailH . "', '0')")or die("nepovedlo se pridat zaznam " . $loginH);
    echo "vytvoren ucet: " . $loginH . ", " . $mailH;
    $odkaz = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REDIRECT_URL'] . "?typ=activate&account=" . $loginH . "&time=" . urlencode($casek);
    $message = "Na tento e-mail neodpovidejte. \n Vytvorili jste ucet s nasledujicimi udaji: \n login: $loginH \n heslo: $hesloH \r\n Pro aktivovani uctu kliknete na nasledujici odkaz: \n" . $odkaz;

    if(mail($mailH, 'Aktivace uctu - bahno.net', $message)) {
      echo "<br />E-mail byl uspesne odeslan.";
      echo "<br />Vyckejte na aktivacni mail prosim.";
    }
    else {
     echo "E-mail se nepodarilo odeslat.";
    }
  }
  else {
    echo "Zadana e-mailova adresa neni validni!";
  }
  
  
}

if ($typ == 'porovnat') {

  $result = mysql_query("SELECT * FROM account WHERE login = '$loginH'")or die("nepodarilo se splnit request");

  while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
    $pwd = $row[1];
    $act = $row[4]; 	
  }
  
  mysql_query("DELETE FROM challenges WHERE id =". intval($challenge)." LIMIT 1");
  
  if (mysql_affected_rows() > 0){
    $pom = $pwd . $challenge;  
    $pwd = md5($pom);
  }

  if ($pwd == $hesloH && $act > 0) { 
    echo "<div id=\"loged\">Budiz prihlasen uzivateli " . $loginH;
    $_SESSION['login'] = $loginH;
    $_COOKIE["loginC"] = $loginH;
    
    echo "<br />pro prohlizeni uctu pokracovat sem: <a href='query.php'><i>prohlizet ucty</i></a>";
    echo " <br /> <a href='sessionclose.php'>odhlasit</a></div>";
  }
  else { 
    //echo "testovaci hash: ". hmac_md5(md5('heslo'), 'pepper') . "<br />";
    //echo "hesloH: ". $hesloH . "<br />pwd: ". $pwd;
    echo "toto heslo neodpovida uctu " . $loginH . " nebo ucet nebyl aktivovan.";
    session_destroy(); 
  }
    
    
    
    
}

if ($typ == 'activate') {
  mysql_query("UPDATE account SET aktivni = '1' WHERE login = '" . $account . "' AND datum= '". $time ."'")or die("nepovedlo se aktivovat ucet " . $loginH);
  echo "Vas ucet $loginH byl aktivovan, nyni se muzete prihlasit.";
  session_destroy();
}

include "pridani.php";

?>


