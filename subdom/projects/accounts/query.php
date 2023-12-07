<?php
  require "sessionmaintain.php";
	include "connectfile.php";

  echo "<style>";
  include "style.css";
  echo "</style>";

if ($prihlasen == true){
  echo "<div id=\"loged\">prihlasen jako: ". $_SESSION['login'] ." <br /> <a href='sessionclose.php'>odhlasit</a></div>";

	if (strlen($aktiv) > 0) {
	echo "<div id=\"rule\">ucty kde je hodnota aktivni:" . $aktiv . "</div><br>";
	
		$result = mysql_query("SELECT * FROM account WHERE aktivni = $aktiv")or die("nepodarilo se splnit request");
	    echo "<div id=\"result\">";
		while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
    			echo "<br>login:" . $row[0] . "<br> heslo:" . $row[1] . "<br> datum:" . $row[2] . "<br> mail:" . $row[3] . "<br> aktiv:" . $row[4] . "<br>";  
		}
		echo "</div>";
	}

	if (strlen($log) >= 1) {
	echo "<div id=\"rule\">ucet s loginem:" . $log . "</div><br>";
			
		$sqlhu = "SELECT * FROM account WHERE login = '" . $log . "'";
		
		$result = mysql_query($sqlhu)or die("nepodarilo se splnit request");
	   echo "<div id=\"result\">";
		while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
    			echo "<br>login:" . $row[0] . "<br> heslo:" . $row[1] . "<br> datum:" . $row[2] . "<br> mail:" . $row[3] . "<br> aktiv:" . $row[4] . "<br>";  
		}
		echo "</div>";
	}

	if ($drive == '<') {
	echo "<div id=\"rule\">ucty zalozene pred datem:" . $dat . "</div><br>";
	
		$sqlhu1 = "SELECT * FROM account WHERE datum < '" . $dat . "'";

		$result = mysql_query($sqlhu1)or die("nepodarilo se splnit request");
	   echo "<div id=\"result\">";
		while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
    			echo "<br>login:" . $row[0] . "<br> heslo:" . $row[1] . "<br> datum:" . $row[2] . "<br> mail:" . $row[3] . "<br> aktiv:" . $row[4] . "<br>";  
		}
		echo "</div>";
	}

	if ($drive == '>') {
	echo "<div id=\"rule\">ucty zalozene po datu:" . $dat . "</div><br>";
	
		$sqlhu2 = "SELECT * FROM account WHERE datum > '" . $dat . "'";

		$result = mysql_query($sqlhu2)or die("nepodarilo se splnit request");
	   echo "<div id=\"result\">";
		while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
    			echo "<br>login:" . $row[0] . "<br> heslo:" . $row[1] . "<br> datum:" . $row[2] . "<br> mail:" . $row[3] . "<br> aktiv:" . $row[4] . "<br>";  
		}
		echo "</div>";
	}




	
	echo "<div id=\"endof\"> end of script</div>";

  include "browseform.htm";

}
else {
  echo "Neprihlaseni uzivatele nemaji pravo prohlizet ucty. <br />";
  echo "pro prihlaseni pokracujte zde: <a href='index.php'><i>prihlasit se</i></a><br/ >";

}

?>


<!-- "SELECT * FROM account WHERE aktivni = '0'" -->
<!-- "SELECT * FROM account WHERE datum >= '2011-04-01'" -->
