<?php

function initialize ($login) {
echo "<div class=\"rectangle redkral\" style=\"top: 1px; left: 450px;\"></div>";
echo "<div class=\"rectangle redkralovna\" style=\"top: 1px; left: 400px;\"></div>";
echo "<div class=\"rectangle redstrelec\" style=\"top: 1px; left: 350px;\"></div>";
echo "<div class=\"rectangle redstrelec\" style=\"top: 1px; left: 500px;\"></div>";
echo "<div class=\"rectangle redkun\" style=\"top: 1px; left: 300px;\"></div>";
echo "<div class=\"rectangle redkun\" style=\"top: 1px; left: 550px;\"></div>";
echo "<div class=\"rectangle redvez\" style=\"top: 1px; left: 250px;\"></div>";
echo "<div class=\"rectangle redvez\" style=\"top: 1px; left: 600px;\"></div>";

echo "<div class=\"rectangle redpijon\" style=\"top: 51px; left: 250px;\"></div>";
echo "<div class=\"rectangle redpijon\" style=\"top: 51px; left: 300px;\"></div>";
echo "<div class=\"rectangle redpijon\" style=\"top: 51px; left: 350px;\"></div>";
echo "<div class=\"rectangle redpijon\" style=\"top: 51px; left: 400px;\"></div>";
echo "<div class=\"rectangle redpijon\" style=\"top: 51px; left: 450px;\"></div>";
echo "<div class=\"rectangle redpijon\" style=\"top: 51px; left: 500px;\"></div>";
echo "<div class=\"rectangle redpijon\" style=\"top: 51px; left: 550px;\"></div>";
echo "<div class=\"rectangle redpijon\" style=\"top: 51px; left: 600px;\"></div>";

echo "<div class=\"rectangle bluekral\" style=\"top: 351px; left: 450px;\"></div>";
echo "<div class=\"rectangle bluekralovna\" style=\"top: 351px; left: 400px;\"></div>";
echo "<div class=\"rectangle bluestrelec\" style=\"top: 351px; left: 350px;\"></div>";
echo "<div class=\"rectangle bluestrelec\" style=\"top: 351px; left: 500px;\"></div>";
echo "<div class=\"rectangle bluekun\" style=\"top: 351px; left: 300px;\"></div>";
echo "<div class=\"rectangle bluekun\" style=\"top: 351px; left: 550px;\"></div>";
echo "<div class=\"rectangle bluevez\" style=\"top: 351px; left: 250px;\"></div>";
echo "<div class=\"rectangle bluevez\" style=\"top: 351px; left: 600px;\"></div>";

echo "<div class=\"rectangle bluepijon\" style=\"top: 301px; left: 250px;\"></div>";
echo "<div class=\"rectangle bluepijon\" style=\"top: 301px; left: 300px;\"></div>";
echo "<div class=\"rectangle bluepijon\" style=\"top: 301px; left: 350px;\"></div>";
echo "<div class=\"rectangle bluepijon\" style=\"top: 301px; left: 400px;\"></div>";
echo "<div class=\"rectangle bluepijon\" style=\"top: 301px; left: 450px;\"></div>";
echo "<div class=\"rectangle bluepijon\" style=\"top: 301px; left: 500px;\"></div>";
echo "<div class=\"rectangle bluepijon\" style=\"top: 301px; left: 550px;\"></div>";
echo "<div class=\"rectangle bluepijon\" style=\"top: 301px; left: 600px;\"></div>";


//mysql_query("INSERT INTO chessplay(hra, figura, top, left, out) VALUES('" . $login . "', 'redpijon', '1', '1', 'live')") or die ("DOOM");
for ($i = 0; $i<8; $i++){
  mysql_query("INSERT INTO chessplay(hra, figura, shora, zleva, vyhozena) VALUES('" . $login . "', 'redpijon', '1', '" . $i ."', 'live')") or die ("nedaří se zapsat počáteční pozice hry do databáze");
  mysql_query("INSERT INTO chessplay(hra, figura, shora, zleva, vyhozena) VALUES('" . $login . "', 'bluepijon', '6', '" . $i . "', 'live')") or die ("nedaří se zapsat počáteční pozice hry do databáze");
}

$pole = array ("vez", "kun", "strelec", "kralovna", "kral", "strelec", "kun", "vez");

for ($i=0; $i<8; $i++) {
$bf = "blue" . $pole[$i];
$rf = "red" . $pole[$i];
  mysql_query("INSERT INTO chessplay(hra, figura, shora, zleva, vyhozena) VALUES('" . $login . "', '" . $rf . "', '0', '" . $i . "', 'live')") or die ("nedaří se zapsat počáteční pozice hry do databáze");
  mysql_query("INSERT INTO chessplay(hra, figura, shora, zleva, vyhozena) VALUES('" . $login . "', '" . $bf . "', '7', '" . $i . "', 'live')") or die ("nedaří se zapsat počáteční pozice hry do databáze");

}
}

function canmove ($login, $natahu, $who, $password, $look) {
 echo "<div class=\"statusdiv\">Hra jménem: <i>";
 echo $login . "</i><br />";
 echo "na tahu je:";
 
 if ($natahu == "b"){ 
  echo "<span style=\"color: blue;\"> modrý</span> <br />";
//  $colo = "blue";
//  $notcolor = "red";
//  $logged = "r";
  }
  else {
  echo "<span style=\"color: red;\"> červený</span> <br />"; 
//  $colo = "red";
//  $notcolo = "blue";
//  $logged = "b";
  }
  
  echo "jste přihlášen jako: ";
 
 if ($who == 'b') {
  echo "<span style=\"color: blue;\"> modrý</span> <br />";

 }
 else {
  echo "<span style=\"color: red;\"> červený</span> <br />";

 }

 echo "<br /> <a href=\"index.php\" title=\"odhlášení\" class=\"warning\">Odhlásit</a>";
 echo "<form name=\"oneform\" action=\"core.php\" method=\"post\">";
 echo "<input type=\"hidden\" name=\"login\" value=\"$login\">";
 echo "<input type=\"hidden\" name=\"password\" value=\"$password\">";
 echo "<input type=\"hidden\" name=\"tahoun\" value=\"$who\">";
 echo "<input type=\"hidden\" name=\"action\" value=\"log-in\">";
 echo "<input type=\"hidden\" name=\"look\" value=\"$look\">";
 echo "<br /><input type=\"text\" readonly value=\"Obnovit\" class=\"warning2\" onclick=\"document.oneform.submit()\">"; // class=\"warning\"
 
 echo "</form>";
 echo "<br /></div>";

 echo "<div class=\"movediv\">Váš tah:";
 
 include "moveform.php";
 
 echo "<input type=\"hidden\" value=\"$look\" name=\"look\">";
 echo "<input type=\"hidden\" name=\"login\" value=\"$login\">";
 echo "<input type=\"hidden\" name=\"password\" value=\"$password\">";
 echo "<input type=\"hidden\" name=\"tahoun\" value=\"$who\">";
 echo "<input type=\"hidden\" name=\"action\" value=\"log-in\">";
 echo "</form></div>";
}

function validmove($ch, $koefx, $koefy, $color, $zx, $zy, $nax, $nay, $login) {
$dilatace = 1;
if ($color == "red"){
//  $koefx = $koefx * -1;
//  $koefy = $koefy * -1;
  $dilatace = -1;        
}


switch ($ch) {
//neslape: pijon sebrat, vez preskakuje figurku.. KURVAPICE KURVAPICE !!  --------- vez snad slape, pijon asi taky, kralovna je opravena
// v okamziku kdy se zpracovava tahle metoda jeste neni nacteny seznam figurek.. proto to nefunguje

    case "pijon":   
        //echo $koefx . $koefy;
        //echo $nax . $nay;
        //echo $figures[$nax][$nay];
        
        if ($koefx == 0){  //kdyz jde pijon rovne, jedno ci dve policka
         if (abs($koefy) == 1 && $koefy == $dilatace){
          if (!isintheway($nax, $nay, $login)){
           return true;
          }
         }
         if (abs($koefy) == 2 &&  $koefy == $dilatace * 2 && !isintheway($nax, $nay + $dilatace, $login) && !isintheway($nax, $nay, $login) && ($zy == 1 || $zy == 6)){
          return true;       
         }
        }
        else if (abs($koefx) == 1 && $koefy == $dilatace && isintheway($nax, $nay, $login)){
         return true;         
        }        
        break;
    case "vez":
        if ($koefx == 0){                      //abs($koefy) -1
         for ($i = 1; $i < abs($koefy); $i ++){
          $iproy = $i * ($koefy / abs($koefy));
          //echo $i .", ";
          if (isintheway($zx, $zy - $iproy, $login)){
          //echo $zx . $zy - $i . $login;
           return false;
          }         
         }
         return true;
        }
        if ($koefy == 0){
         for ($i = 1; $i < abs($koefx); $i ++){
         $iprox = $i * ($koefx / abs($koefx));
          if (isintheway($zx - $iprox, $zy, $login)){
           return false;
          }
         }
         return true;
        }
        break;
    case "kun":
        if (abs($koefx) == 2 && abs($koefy) == 1 || abs($koefx) == 1 && abs($koefy) == 2){
         return true;
        }
        break;
    case "strelec":
        if (abs($koefx) == abs($koefy)){
         for ($i = 1; $i < abs($koefx); $i++){
          $iprox = $i * ($koefx / abs($koefx));
          $iproy = $i * ($koefy / abs($koefy));
          //echo "<br />x: " . $zx - $iprox . " y: " . $zy - $iproy;
          if (isintheway($zx - $iprox, $zy - $iproy, $login)){
           return false;
          }
         }
         return true;
        }
        break;
    case "kralovna":
        //if (validmove("vez", $koefx, $koefy, $color) || validmove("strelec", $koefx, $koefy, $color)){
        
        //vez-kopie
        if ($koefx == 0){                      //abs($koefy) -1
         for ($i = 1; $i < abs($koefy); $i ++){
          $iproy = $i * ($koefy / abs($koefy));
          //echo $i .", ";
          if (isintheway($zx, $zy - $iproy, $login)){
          //echo $zx . $zy - $i . $login;
           return false;
          }         
         }
         return true;
        }
        if ($koefy == 0){
         for ($i = 1; $i < abs($koefx); $i ++){
         $iprox = $i * ($koefx / abs($koefx));
          if (isintheway($zx - $iprox, $zy, $login)){
           return false;
          }
         }
         return true;
        }
        //strelec-kopie
        if (abs($koefx) == abs($koefy)){
         for ($i = 1; $i < abs($koefx); $i++){
          $iprox = $i * ($koefx / abs($koefx));
          $iproy = $i * ($koefy / abs($koefy));
          //echo "<br />x: " . $zx - $iprox . " y: " . $zy - $iproy;
          if (isintheway($zx - $iprox, $zy - $iproy, $login)){
           return false;
          }
         }
         return true;
        }        
        break;
    case "kral":
        if (abs($koefx) <= 1 && abs($koefy) <= 1){
         return true;
        }
        break;        
}
 return false;
}

function isintheway($x, $y, $login){
 $result = mysql_query("SELECT * FROM chessplay WHERE hra = '". $login ."' AND shora = $y AND zleva = $x");
 while($row = mysql_fetch_array($result, MYSQL_NUM)){
   if ($row[0] == $login && $row[4] != "dead"){
   //echo "row[-]" . $row[0];
   return true;
  }
 }
 return false;
}


include "connectfile.php";
$figures = array();


if (strlen($chlapik) > 0) {
  if (strpos($chlapik, "blue") !== false) {
   $ch = substr($chlapik, 4);
   $tablahni = "r";
   $color = "blue";   
  }
  else {
   $ch = substr($chlapik, 3);
   $tablahni = "b";
   $color = "red";     
  }
  $koefx = $zx - $nax;
  $koefy = $zy - $nay;

  if (validmove($ch, $koefx, $koefy, $color, $zx, $zy, $nax, $nay, $login)) {
    if (strlen($obet) > 0) {
     mysql_query("UPDATE chessplay SET vyhozena = 'dead' WHERE hra = '". $login ."' AND figura = '". $obet ."' AND shora = ". $nay ." AND zleva = ". $nax .";") or die ("nedaří se vyhodit figurku");
    }

//    if ($obet == "bluekral" || $obet == "redkral"){
//      mysql_query("DELETE * FROM chessplay WHERE hra = '". $login ."'");
//      mysql_query("UPDATE chesscontrol SET natahu = '". $tablahni + 1 ."' WHERE hra = '". $login ."'");
//    }
//    else {
     mysql_query("UPDATE chessplay SET shora = ". $nay .", zleva = ". $nax ." WHERE hra = '". $login ."' AND figura = '". $chlapik ."' AND shora = ". $zy ." AND zleva = ". $zx .";") or die ("nedaří se vykonat požadovaný tah");
     mysql_query("UPDATE chesscontrol SET natahu = '". $tablahni ."' WHERE hra = '". $login ."'") or die (mysql_error());
//    }
  }
  else {
   echo "<div class=\"warning\">Neplatný tah</div>";
  }
}

if ($look > 1){
include "header2.htm";
} else {
include "header.htm"; }

include "movescript.htm";
echo "<div class=\"hlavni\">";
echo "<div class=\"highlight\" id=\"highlight\"></div>";
echo "<div class=\"highlight2\" id=\"highlight2\"></div>";

//v bazi: zaznam ma atributy -> hra, figura, top, left, out?
// vyznam: identifikuje se hra, podle loginu, typ figury kvuli pohybum a pozice
//v bazi tabulka chess-control -> hra, heslo, natahu ..

if (strlen($login) > 0) {

 
  $resultE = mysql_query("SELECT * FROM chesscontrol WHERE hra = '$login' LIMIT 1");
  $exist = 0;
  $pword = "";
  $natahu = "";
   
  while ($row = mysql_fetch_array($resultE, MYSQL_NUM)) {
    $exist += 1;
    $pword = $row[1];
    $natahu = $row[2];    
  }
  if ($action == "create"){
  if ($exist > 0){
   echo "Hra s tímto názvem již existuje!";
   echo "<br /><a href=\"index.php\" title=\"následujte tento odkaz, abyste mohli zadat jiný název hry\">Zadejte jiný název</a>";
  }
  else {
   //$query_string = "INSERT INTO chess-control(hra, heslo, natahu) VALUES('" . $login . "', '" . $password . "', 'b')"; 
  mysql_query("INSERT INTO chesscontrol(hra, heslo, natahu) VALUES('" . $login . "', '" . $password . "', 'b')") or die ("nedaří se přidat hru do databáze");

   initialize($login);
  }
 }
 if ($action == "log-in") {
 
  if ($pword != $password) { 
    echo "zadal jste špatné heslo k této hře, nebo takto pojmenovaná hra neexistuje";
    echo "<br /><a href=\"index.php\" title=\"následujte tento odkaz, pro zadání správného hesla\">Zadejte správné heslo</a>"; 
  }
  else {
    $result = mysql_query("SELECT * FROM chessplay WHERE hra = '$login'") or die ("nedaří se načíst rozložení partie");
    while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
        if ($row[4] == "dead" && ($row[1] == "bluekral" || $row[1] == "redkral")){
          echo "<div class=\"ending\">Hra je skončena.<br />Gratuluji vítězi!</div>";
          mysql_query("DELETE FROM chesscontrol WHERE hra = '". $login ."'");
          mysql_query("DELETE FROM chessplay WHERE hra = '". $login ."'");
          
        }
        else {
        if ($row[4] != "dead") {
          $horni = 1 + (50 * $row[2]);
          $leva = 250 + (50 * $row[3]);
          echo "<div class=\"rectangle ". $row[1] ."\" style=\"top: ". $horni ."px; left: ". $leva ."px;\"";
          if ($natahu == $tahoun){
            if (($natahu == "b" && strpos($row[1], "blue") !== false) || ($natahu == "r" && strpos($row[1], "red") !== false)) {
              echo " onclick=\"moveset('". $row[1] ."',$row[2],$row[3]);\"";
            }
            else {
              echo " onclick=\"executemove($row[2],$row[3], '". $row[1] ."');\"";
            }          
          }
          echo "></div>";
//          $figures[$row[3]][$row[2]] = $row[1];
        }
        }
        }

        /*for ($i=0; $i<8; $i++){
         for ($j=0; $j<8; $j++){
          echo "<br />" . $i . $j;
          echo $figures[$i][$j];
         }        
        } */       
         canmove($login, $natahu, $tahoun, $password, $look);
      }
    }
}

include "board.htm";
include "footer.htm";

?>
