<?php
header('Location: https://vvopice.zhouba.cz/');
/**
 * @author upadrian@gmail.com
 * Simplified version for array escape using mysql_real_escape_string
 * pouzita funkce od typka z komentaru ke clanku "mysql_real_escape_string".
 * mohl jsem napsat svou, ale bylo by to asi dost podobny:)
*/

function mres($q) {
  if(is_array($q))
    foreach($q as $k => $v)
      $q[$k] = mres($v); //recursive
  elseif(is_string($q))
    $q = mysql_real_escape_string($q);
return $q;
}

function DeScript($bsah){
  $huhu = strlen($bsah);
  for($indian = 0; $indian < ($huhu * 2); $indian++){
    if($indian % 2 && $indian != 0) $descriptor .= $bsah[$indian / 2];
    else $descriptor .= " ";
  }
  $bsah = "attempt to insert script detected! IP logged & banned..\n"
		. $descriptor;
return $bsah;
}

// dve funkce pro generator jmena anonyma

function GetMaxItemid($slovdruh){
  $r = mysql_query("SELECT Max(itemid) FROM opo WHERE slovnidruh = '$slovdruh'")
		or die("unable to comply : maxitemid");
	if (mysql_fetch_row($r))
	  $maxid = mysql_result($r, 0);
return $maxid;
}

function GetWord($slovdruh, $id){
  $resultx = mysql_query("SELECT slovo, rodpodst FROM opo WHERE slovnidruh = '$slovdruh' AND itemid = '$id' ;")
		or die("unable to comply: vybirani opicoidu");

    $rowbl = mysql_fetch_row($resultx);
    if ($slovdruh == 1)
      $ret = $rowbl[0] . $rowbl[1];
    else $ret = $rowbl[0];
return $ret;
}

function GetLastText(){
  $vyslor = mysql_query("SELECT Max(time) FROM forum;")
		or die("unable to comply: maxtime - time");
	if (mysql_fetch_row($vyslor)) $lastdt = mysql_result($vyslor, 0);
  $vyslok = mysql_query("SELECT text FROM forum WHERE time = '$lastdt';")
		or die("unable to comply: maxtime - data");
  if (mysql_fetch_row($vyslok)) $lasttext = mysql_result($vyslok, 0);
  return $lasttext;
}

include "connectdb.php";

// preventivni slashing $_POST a $_GET kvuli sql injection
$_GET = mres($_GET);
$_POST = mres($_POST);

if ( ! empty($_GET['rss'])) {
	include "rss.php";
	exit;
}

$jmeno = !empty($_POST['jmeno'])
	? $_POST['jmeno']
	: null;
$text = !empty($_POST['text'])
	? $_POST['text']
	: null;
$generate = !empty($_POST['generate'])
	? $_POST['generate']
	: null;
$jmeno = trim($jmeno);
$text = trim($text);

$adding = !empty($_POST['adding'])
	? $_POST['adding']
	: null;
$searching = !empty($_GET['searching'])
	? $_GET['searching']
	: null;
$searched = !empty($_GET['searched'])
	? $_GET['searched']
	: null;
$int = !empty($_GET['int'])
	? $_GET['int']
	: null;

if ($adding == "pivo" && ($text == null || $text == "" )) {
	$adding = false;
	echo "<span class=\"error\">není dovoleno přidávat prázdné příspěvky!</span><br />";
}elseif($adding == "pivo" && ($jmeno == null || $generate !== null)) {

	//generator jmena anonyma
	$podstret = GetWord(1, rand(1,GetMaxItemid(1)));
  $rodd = substr($podstret, -1);
  $podst = substr($podstret, 0, -1);
  $prid = GetWord(2, rand(1,GetMaxItemid(2)));
  if($rodd == 2) $prid .= "á";
  if($rodd == 3) $prid .= "é";
  if($rodd == 1){
		$specials = array("d", "t", "n");
		$mekkejsi = array("ž", "š", "č", "ř", "c", "j", "d", "t", "n");
		$souhlaska = array("b", "c", "d", "f", "g", "h", "j", "k", "l", "m",
			"n", "p", "q", "r", "s", "t", "v", "w", "x", "z");
		$mekkejsPridan = false;
		foreach($mekkejsi as $mekkejs) {
			if(substr($prid, -1) == $mekkejs && in_array(substr($prid, -2, 1), $souhlaska)
				|| substr($prid, -1) == $mekkejs && !in_array($mekkejs, $specials)
			){
				$prid .= "í";
				$mekkejsPridan = true;
				break;
			}
		}
    if(!$mekkejsPridan) {
    	$prid .= "ý";
		}
  }
  $op = GetWord(6, rand(1,GetMaxItemid(6))) ." ". $prid ." ". $podst;
  if (!empty($generate) && !empty($jmeno)) $op .= " $jmeno";
  $jmeno = $op;

}elseif($adding == "pivo" && (stristr($jmeno, '<script')
		|| stristr($jmeno, htmlentities('<script')))
	) {
  header("Location: ". $_SERVER['SCRIPT_URI']);
  die();   //$text = DeScript($text)
}

if ($adding == "pivo"){

  if (strcasecmp(html_entity_decode(GetLastText(), ENT_QUOTES, 'UTF-8'),
		str_replace( array("\r\n", "\n", "\r"), " <br /> ", $text)) != 0
	) {
	  if(stristr($text, '<script') || stristr($text, htmlentities('<script'))) { $text = DeScript($text);}
    $cas = Date("Y-m-d H:i:s");
		mysql_query("INSERT INTO forum(time, name, text, ip) VALUES('"
			. $cas . "', '" . $jmeno . "', '" . htmlentities(str_replace(
			array("\\r\\n", "\\n", "\\r"), " <br /> ", $text), ENT_QUOTES, "UTF-8")
			. "', '" . $REMOTE_ADDR . "')")or die();

    unset($_POST);
    $text = "";

    header("Location: ". $_SERVER['SCRIPT_URI'] . "?sent=1");

  }else{
    echo "<span class=\"gruen\">zabráněno odeslání duplicitního příspěvku</span><br /><br />";
  }
}

include "head.php";
include "forms.html";
include "posts.php";
include "getup.html";
include "links.html";
include "foot.html";
?>
