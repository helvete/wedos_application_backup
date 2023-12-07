<?php
function Reload(){
  header("Location: ".GetCurrentLocation()."");
}?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cs" lang="cs">


<html><head>
<link rel="shortcut icon" type="image/x-icon" href="nvgxu.ico" />	  
<title>unigal</title>

<style>
body {
	font-family: courier, monospace;
  background-color: #efefbf;
}
div a, a:active, a:visited{
  text-decoration: none;
}

.lnk    {
	color: #896921;
	font-size: 20px;
	font-weight: bold;
	margin: 0px;
}
input {
  background-color: #e0e0bf;
  font-family: courier, monospace;
  
}

img {
	
  border: 15px solid #e0e0bf;
}
span a {
	text-decoration: none;
	color: #896921;
}

h2{
	font-size: 28px;
	color: #691f00;
} 

.left {
	float: left;
	margin-top: 0px;
	margin-bottom: 0px; 
}

.ramecek {
	
	display: table; 
	position: absolute;
	left: 210px; top: 85px; 
	padding: 15px; 
  padding-top: 0px;
	margin-top: 0px; 
}
.info {
  display: table; 
	position: absolute;
	left: 210px; top: 15px; 
	padding: 5px;
  padding-top: 18px; 
	margin-top: 0px; 
	background-color: #efefbf;
  width: 400px;
  height: 46px;
  color: #691f00; 
}
.inactive{
	color: #c0c0c0;
	font-size: 20px;
	font-weight: bold;
	margin: 0px;
}
.files {
	position: absolute;
	left: 0px; top: 126px; 
	width: 180px;
	border: 3px solid #691f00;
	padding: 7px; 
  margin-left: 10px;
	margin-top: 0px; 
	background-color: #efefbf;
}
.ctrl {
  float: right;
	border: 3px solid #691f00;
	padding: 5px;
  margin: 5px; 
	margin-top: 0px; 
	background-color: #e0e0bf;
}
.ramecek a:link, .ramecek a:visited, .ramecek a:active, .ramecek a:hover{
  color:#efef9f;
  text-decoration: none;
}
     
.menudir {
  font-weight: bold;
  color: #691f00;  
  text-transform: uppercase;  
}
.menu{
  color: #691f00;
}
.menusel{
  color: #691f00;  
  background-color: #e0e0bf;
}
.adressor{
  width: 176px;
}
.padder{
  padding: 10px;
}
.pvy {
  font-weight: bold;
  color: #691f00; 
}
.err {
  background-color: white;
  position: absolute;
	left: 866px; top: 126px;
  border: 1px solid #000000;
  padding: 5px; 
}

</style>
</head><body>


<div class="padder">
<h2 class="left">gallery</h2>
<?php
function GetCurrentLocation(){            // fce vraci aktualni adresu kvuli presmerovani pri obnove cookie
  $out = $_SERVER['SCRIPT_URI'];          // ponejprve cestu ke scriptu
  $f = 0;
  foreach ($_GET as $key => $value) {     // a pote z $_GET pole vycucnem a pripojime do linku vse
    $f++;
    if($f == 1)
      $out .= "?";
    else
      $out .= "&"; //amp;
    $out .= $key . "=" . $value;  
  }
return $out; 
}
function GetRunData(){                     // funkce ze-souboru-behova-data-nacitajici
  global $file;
  $data = file_get_contents($file);
  $mutant = explode("\n", $data);
return $mutant;                            // vracime pole radek
}
function IsPartOfAllowed($expr){           // funkce pro kontrolu souboru, zda-li ma zobrazovanou priponu
  global $allowedfiletypes;                // v galerii typicky obrazek :-D
  $glu = false;
  foreach($allowedfiletypes as $ft){
    if($expr == $ft){
      $glu = true;
      break;
    }
  }  
return $glu;
}
function MaxId(){
  $r = mysql_query("SELECT Max(id) FROM galdata")or die("unable to comply : id"); //zjisteni posledniho id
	if (mysql_fetch_row($r))
	$Mx = mysql_result($r, 0);
return $Mx;
}
function MinDirId($dir){
  $r = mysql_query("SELECT Min(id) FROM galdata WHERE curdir LIKE '$dir'")or die("unable to comply : dir id"); //zjisteni prvniho id daneho adresare
	if (mysql_fetch_row($r))
	$Mx = mysql_result($r, 0);
return $Mx;
}
function MaxDirId($dir){
  $r = mysql_query("SELECT Max(id) FROM galdata WHERE curdir LIKE '$dir'")or die("unable to comply : dir id"); //zjisteni posledniho id daneho adresare
	if (mysql_fetch_row($r))
	$Mx = mysql_result($r, 0);
return $Mx;
}
function GetLine($line){
	global $currentdir; 
  $lt = mysql_query("SELECT * FROM galdata WHERE id = $line AND curdir LIKE '$currentdir'")or die("unable to comply : line"); //vraci radek z DB, v kterem jsou hledana data
	$row = mysql_fetch_array($lt, MYSQL_NUM);
return $row;
}
function GetLink($filename, $curdir){
		$lt = mysql_query("SELECT * FROM galdata WHERE filename LIKE '$filename' AND curdir LIKE '$curdir'")or die("unable to comply : link"); //zjisteni id pro vypis souboru
		$row = mysql_fetch_array($lt, MYSQL_NUM);
return $row; 
}

function LoadDir($directory){ //vykresluje jmena souboru jako linky do menu       
	global $basedir, $currentdir, $vari;                                        
  if(strlen($basedir) > 32) $shortdir = ".." . substr($basedir, -19);  // zkratime jmeno cesty aby se veslo   
  else $shortdir = substr($basedir, 13);                                                   
  echo "<form><input type=\"text\" class=\"adressor\" readonly value=\"". $shortdir ."\" /></form>"; // do type=text dame relativni cestu
  //echo $currentdir . "<br />";  
  $files = array();
  $dirs = array();
	foreach (new DirectoryIterator($directory) as $fileInfo) {         // pro vsechny objekty v adresari ...
		if(strlen($fileInfo->getFilename()) > 20) $shortname = substr($fileInfo->getFilename(), 0, 18). "..";  // zkratime jmeno linku aby se veslo
    else  $shortname =  $fileInfo->getFilename();                                                          // kdyz nezkratime, nacpeme do stejny promenny
    if($fileInfo->isFile()){                                         // ***pokud jsi soubor***
		  $temp = GetLink(addslashes($fileInfo->getFilename()), $currentdir); // nacti radek z DB - podle jmena souboru
      $tux = explode(".", $fileInfo->getFilename());                 // $fileInfo->getExtension() zatim nefunguje
      if($tux[count($tux)-1] != ""){                                 // tak priponu zjistime splitem ze jmena souboru
        if(IsPartOfAllowed($tux[count($tux)-1])){                    // a pokud mame obrazek - vypiseme link pro soubor
          if($temp[0] == null || !$temp[3] == $currentdir)           // bez zaznamu || v tomto adresari
            IndexFile($fileInfo->getFilename());                     // zaindexuj.
          if($temp[0] != $vari)
            $files[] = "<a href=\"simplegal.php?vari=" . $temp[0] . "&basedir=". $basedir ."&currentdir=". $currentdir ."\" class=\"menu\" title=\"display this image\">". $shortname . "</a><br>\n";          
          else          
            $files[] = "<a href=\"simplegal.php?vari=" . $temp[0] . "&basedir=". $basedir ."&currentdir=". $currentdir ."\" class=\"menusel\" title=\"display this image\">". $shortname . "</a><br>\n";
		    }
      }                                                             
    }                                                              
		elseif($fileInfo->isDir()){                                      // ***pokud jsi adresar***
      if($fileInfo->getFilename() == ".."){                                              // dvouteckovy rodicovsky adresar zobrazujeme, pokud nejsme v root-adresari.
          if ($currentdir != "" && $currentdir != "/"){                                  // kvuli spravnemu prochazeni velkou spleti adresaru    
          $tumpach = substr($basedir, 10, - (strlen($currentdir)));                      // a proti zaspamovani adresaru typu /model/test a redundatni /test zaznam
          $tumx = explode("/", $tumpach);                                                // z adr. struktury ubereme zaklad, zbytek splitnem podle "/"; 
          if($tumx[count($tumx)-1] != "") $tumpach = "/". $tumx[count($tumx)-1];         // to za posl. "/" je nas zaznam prave kdyz to neni jen "/" :-D
          echo "<a href=\"simplegal.php?basedir=". substr($basedir, 0, - (strlen($currentdir))) ."&currentdir=". $tumpach ."\" class=\"menudir\" title=\"display parent directory's contents\">". $fileInfo->getFilename() . "</a><br>\n";
          }
      }elseif($fileInfo->getFilename() == "."){}                   // jednoteckovy - aktualni adresar nezobrazujeme    
      else                                                         // vytiskneme link pro adresar
      $dirs[] = "<a href=\"simplegal.php?basedir=". $basedir ."/". $fileInfo->getFilename() . "&currentdir=/". $fileInfo->getFilename() ."\" class=\"menudir\" title=\"display this directory's contents\">". $shortname . "</a><br>\n";
	  }
  }                                                                
  foreach (array_merge($dirs, $files) as $value) {                 // pro vsechny prvky obou spojenych poli
    echo $value;                                                   // vytiskneme hodnotu = srovnany adresare nad soubory
  }                                                                // (jinak nesortime, at je to klidne podle casu zalozeni)
}            
             //indexujeme jen jmeno souboru, id, domovsky adresar - popisky se budou ukladat rucne - mozna jinym skriptem
function IndexFile($filename){
  global $basedir, $currentdir;
  mysql_query("INSERT INTO galdata(id, filename, curdir, linkoid) VALUES('" . (MaxId()+1) . "', '" . addslashes($filename) . "', '" . addslashes($currentdir) . "', '" . (substr($basedir, 10)) . "')")or die("unable to comply : index");
}
function deleteDirectoryFromDB(){
  global $basedir;
  $piii = (substr($basedir, 10));
  mysql_query("DELETE FROM galdata WHERE linkoid = '$piii' ")or die("unable to comply : delete");
  Reload();
}

include "connectdb.php";
$file = 'varidata.dat';                                     // jakypak soubor si precteme
if (!isset($_COOKIE['rundata'])){                           // pokud mame schovanou susenku (ulozeny cookie)
  if(GetRunData()){                                         // pokud je vse jak ma (funkce ze-souboru-nacitajici neselhala)      
  $mutant = GetRunData();                                   // do pomocny promenny sypeme jednotlivy radky souboru
  $mutant[0] = "";                                          // smazneme heslo - aby jednoduse nebylo v poli, tudiz v cookie
  if($uc){
    $mutant[3] = $wi. " " .$he;
  }                                          
  $hilffoid = implode("*", $mutant);                        // nasypeme do jednoho stringu, at nemame vice susenek, nez je treba
  setcookie("rundata", $hilffoid, time()+31536000);         // ulozeni cookie - na rok - pro galerii adekvatni, rekl bych
  }else echo "shit, cannot load a data from cookie";  // selhalo.. ac nemelo by       
    
  Reload();
  
}elseif(isset($_COOKIE['rundata']) && $uc){                 // pokud cookie uz mame (serverovou), ale chceme zmenit
  $mutant = GetRunData();                                   // preferovany rozmer prohlizeneho obrazku
  $mutant[0] = "";                                          // vse stejne jako v podmince nahore, az na
  $mutant[3] = $wi. " " .$he;                               // tenhle radek - vlozime onen rozmer
  $hilffoid = implode("*", $mutant);                        
  setcookie("rundata", false);                              // smazeme default cookie
  setcookie("rundata", $hilffoid, time()+31536000);         // zrobime nasi cookie
  header("Location: ". $uc ."");                            // prenacteme stranku - v $uc mame shodou okolnosti link na misto,
                                                            // kde jsme byli predtim:-D
}else{

if($debug)
  echo "<span class=\"ctrl\"><a href=\"". substr(GetCurrentLocation(), 0, -8) ."\" class=\"menu\">settings</a></span><br /><br />";
else{
  if($vari || $currentdir) $spojka = "&amp;";
  else $spojka = "?";
  echo "<span class=\"ctrl\"><a href=\"".GetCurrentLocation(). $spojka . "debug=1\" class=\"menu\">settings</a></span><br /><br />";
}
                                                           
//inicializace behovych dat z cookie ;-)
$rrdd = explode("*", $_COOKIE['rundata']); // louskneme cookie podle *
if($basedir == null)                       // pokud se jiz nepohybujeme galerii 
$basedir = $rrdd[1];                       // definujeme "root of the gallery"
$allowedfiletypes = explode(" ", $rrdd[2]);// a vzdy definujeme zobrazovane typy souboru (jsou oddeleny mezerou)

if(!$rrdd[3] == ""){                       // pokud byly zmeneny vychozi rozmery obrazku 
  $tempsize = explode(" ", $rrdd[3]);      
  $maxwidth = $tempsize[0];                // naplni se uzivatelskymi daty
  $maxheight = $tempsize[1];               //  -||-  
}else{
  $maxwidth = 600;                         // pokud ne, nacpeme default
  $maxheight = 500;
}
  
if($currentdir == null)
$currentdir = "";  // current directory

if($vari == null){
$vari = MaxDirId($currentdir); // po vykresleni stranky zobrazime nejmladsi obrazek
}
if($vari){                     // (pokud tam nejaky je :-D)
  $prvky = Getline($vari);     // -||-
  // pocitadlo obrazku
  echo "<span class=\"left\">" . ($vari) .".picture of ". (MaxId())."</span><br />";
  // tlacitka previous a next vcetne jejich neaktivnich variant
  if ($vari != MinDirId($currentdir))
  echo "<span class=\"lnk\"><a href=\"simplegal.php?vari=". ($vari-1) ."&basedir=". $basedir ."&currentdir=". $currentdir ."\" title=\"previous picture\">previous </a></span>";
  else 
  echo "<span class=\"inactive\">previous </span>";
  if ($vari != MaxDirId($currentdir))
  echo "<span class=\"lnk\"><a href=\"simplegal.php?vari=". ($vari+1) ."&basedir=". $basedir ."&currentdir=". $currentdir ."\" title=\"next picture\"> next</a> </span>";                                         
  else 
  echo "<span class=\"inactive\"> next</span>";

  $filnm = "../../vv/pics" . substr($basedir, 10) . "/" . $prvky[2];
  $handle = @file($filnm);
  
  if(count($handle) == 1 || count($handle) == 0){
    deleteDirectoryFromDB();
    //  debugovaci div - na vypisovani chyb a obsahu promennych..
    echo "<div class=\"err\">". $handle ." ". print_r($handle) ." ". $filnm ." ". $prvky[2] ." ". (substr($basedir, 10)) ."</div>";
    //  debugovaci div
  }  
  

  echo "<div class=\"ramecek\"><a href=\"../../vv/pics" . substr($basedir, 10) . "/" . $prvky[2] ."\" class=\"picturelink\"><img src=\"../../vv/pics" . substr($basedir, 10) . "/" . $prvky[2] ."\" title=\"". $prvky[1] ." (click the image to enlarge)\" alt=\"picture displayed by gallery\" style=\"max-width: " . $maxwidth . "px; max-height: " . $maxheight . "px;\" /></a></div>";
  echo "<div class=\"info\"><span class=\"pvy\">$prvky[2]</span> <br />$prvky[1]</div>";
}else echo "<div class=\"info\"><span class=\"pvy\">There are no pictures in this directory:-) (if there are some, please refresh this page)</span>";
echo "<div class=\"files\">";
echo LoadDir($basedir) ."</div>";
}
if($debug){
  echo "<div class=\"ramecek\" style=\"z-index: 1; background-color: #efefbf; margin: 15px; margin-left: 30px;\">";
  echo "<span class=\"pvy\">User settings: </span><br />
    you can set the prefered maximal size of <br />
    an image while wiewed. the setting is stored <br />
    within a cookie => so it will be active <br />
    for a next year to be used. <br />
    <form method=\"post\" action=\"simplegal.php\">
    <span class=\"pvy\"><label>max. image width: <input type=\"text\" name=\"wi\" value=\"" .$maxwidth. "\" />px</label></span><br />
    <span class=\"pvy\"><label>max. image height: <input type=\"text\" name=\"he\" value=\"" .$maxheight. "\" />px</label></span><br />
    <input type=\"hidden\" name=\"uc\" value=\"". GetCurrentLocation() ."\" />
    <input type=\"submit\" value=\"set\" />
    </form><br />
    <span class=\"pvy\">
    <a href=\"control.php\" class=\"ctrl\" style=\"color: #691f00;\">administrator</a>
    <a href=\"". substr(GetCurrentLocation(), 0, -8) ."&amp;delcook=1\" class=\"ctrl\" style=\"color: #691f00;\">delete the cookie</a>
    <a href=\"". substr(GetCurrentLocation(), 0, -8) ."\" class=\"ctrl\" style=\"color: #691f00;\">close</a>
    </span><br />
    <span class=\"pvy\">Main Features: </span><br />
    *automatic directory indexing/reindexing<br />
    *photo labeling via administrator section<br />    
    *default directory settable using relative path<br />
    *changeable supported (=displayed) filetypes<br />
    *administrator-set default image max-dimensions<br />
    *client-settable overriding image max-dimensions<br />
    <span class=\"pvy\">php-mysql gallery programmed by helvete</span><br />           
        
    ";              
  echo "</div>";
}
if($delcook){                                                
  setcookie("rundata", false);
  header("Location: ". substr(GetCurrentLocation(), 0, -10) ."");
}    
?>

      

</div>
</body></html>