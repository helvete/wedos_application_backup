<?php

/*
 * insert single post
 *
 * @param  string	$name
 * @param  string	$text
 * @param  string	$tag
 * @return int
 */
function insertPost($name, $text, $tag = null){
	global $pdo;

	$time = new DateTime();
	$mysqlTime = $time->format("Y-m-d H:i:s");

	$query = $pdo->prepare('
		INSERT INTO resystdb(time, name, text, tag)
		VALUES(:time, :name, :text, :tag)
	');

	$query->execute(array(
		':time' => $mysqlTime,
		':name' => $name,
		':text' => implode(' <br /> ', explode('\r\n', $text)),
		':tag' => getTagId($tag),
	));
	return $query->rowCount();
}
	include "connectdb.php";
include "pdo_connect.php";

	// preventivni slashing $_POST a $_GET kvuli sql injection
	$_GET = mres($_GET);
	$_POST = mres($_POST);

	$inputVars = array_merge($_GET, $_POST);
	foreach($inputVars as $key => $val){
		if(!empty($key)) $$key = $val;
	}

	if(!empty($newpost)){
		if (insertPost($jmeno, $text, $tag)) $mazec = "?added=true";
		else $mazec = "?change=true";
		header("Location: ". $_SERVER['SCRIPT_URI'] . $mazec);
	}

	// editace clanku / prispevku
	if(!empty($editpost)){
		mysql_query("UPDATE resystdb SET name = '" .pacified($jmeno) . "' , text = '"
		.  htmlentities(str_replace( array("\r\n", "\n", "\r"), " <br /> ", pacified($text)), ENT_QUOTES, "UTF-8")
		. "' , tag = '" . getTagId($tag) . "' WHERE id = '$editpost' ")
		or die("error updating post");
		header("Location: ". $_SERVER['SCRIPT_URI'] ."?changed=true&editposts=true");
	}

	  // mazeme clanek
	  if(!empty($delete)){
		Delete($delete);
		header("Location: ". $_SERVER['SCRIPT_URI'] ."?deleted=true&editposts=true");
	  }
	  // mnohonasobne mazani - kvuli tomu se vyplatilo udelat Delete funkci a jen ji volat. v lisce je, kolik prispevku jsme zmazali
	  if(!empty($multipledelete)){
		$fox = 0;
		foreach($md as $boxik) {
		  if($boxik != "") {
			Delete($boxik);
			$fox++;
		  }
		}
		header("Location: ". $_SERVER['SCRIPT_URI'] ."?multidel=". $fox ."&editposts=true");
	  }
	  //var_dump($_SERVER);
	  //exit;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cs" lang="cs">
  <head>
  <meta http-equiv="content-language" content="cs" />
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <meta name="author" content="codeall: helvete" />
  <meta name="robots" content="index, follow" />
  <meta name="description" content="Popis stranky" />

  <link rel="stylesheet" href="ss.css" type="text/css"	media="screen" />
  <link rel="shortcut icon" type="image/x-icon" href="icon.ico" />
  <title>resyst - dev</title>
  </head>
  <body>
	<div id="alldevouring" class="bordered">
	  <table border="1" class="posts"><tr><td>
		<div id="topleft">
			<a href="<?php echo "http" . ( $_SERVER['HTTPS'] ? "s" : "" ) . "://" . $_SERVER['SERVER_NAME'] . $_SERVER['REDIRECT_URL']; ?>" title="helvete's temporary virtual home">
				<img src="./logo.png" class="logo0" alt="helvete's temporary virtual home page's logo" />
			</a>
		</div>
	  </td><td id="vertop">
		<div id="topright">
		  <?php
			// odkazy vedle loga, pokud je prihlasen autor - publikator
			if(!empty($change) || !empty($changed)
				|| !empty($deleted) || !empty($multidel)){
		  ?>
			<a href="<?php echo $_SERVER['SCRIPT_URI'] ."?change=true&addone=true"; ?>" title="">add posts</a>&nbsp;
			<a href="<?php echo $_SERVER['SCRIPT_URI'] ."?change=true&editposts=true"; ?>" title="">edit posts</a>
		  <?php
			}else{		   // odkazy vedle loga, pokud je prihlasen navstevnik
		  ?>
			<a href="<?php echo $_SERVER['SCRIPT_URI']."#2"; ?>" title="">reload</a>
		  <?php
			};
		  ?>
		</div>
	  </td></tr></table>

	<br />
	<?php
	// --zacatek funkci

	/**
	 * @author upadrian@gmail.com
	 * Simplified version for array escape using mysql_real_escape_string
	 * pouzita funkce od typka z komentaru ke clanku "mysql_real_escape_string". mohl jsem napsat svou, ale bylo by to asi dost podobny:)
	*/
	function mres($q) {
	  if(is_array($q))
		foreach($q as $k => $v)
		  $q[$k] = mres($v); //recursive
	  elseif(is_string($q))
		$q = mysql_real_escape_string($q);
	return $q;
	}

	/**
	 * function for home links
	 */
	function home($link){
	  $link = split('?', $link);
	return $link[0];
	}

	// funkce pro vytisk jednoho prispevku s regexovymi zamenami: vyroba linku z odkazu, obrazku z odkazu n a dobrazky, nasype do tabulky
	function PrintSinglePost($row){
	  // odstraneni bugu "<br </a>/>"
		  $row[2]= ereg_replace("<br />", " <br /> ", $row[2]);
		  // vyroba odkazu z retezcu
	  $words = explode(" ", $row[2]);
	  foreach($words as &$word){
		if (substr($word, 0, 4) == "http"){
		  if (substr($word, -3, 3) == "png" || substr($word, -3, 3) == "jpg" || substr($word, -3, 3) == "gif" || substr($word, -4, 4) == "jpeg"){
			$word = ereg_replace("(http[^ ]+\.[^ ]+)", " <img class=\"postimg\" src=\"\\1\" alt=\"user-inserted picture\" />", $word);
		  }else{
			$word = ereg_replace("(http[^ ]+\.[^ ]+)", " <a href=\"\\1\">\\1 </a> ", $word);
		  }
		}
	  }
	  // vyroba cesty tagu - subtemata temat:-)
	  $famtre[0] = $row[3];
	  while(getParent($row[3])){
		$hhh = getParent($row[3]);
		$famtre[] = $hhh;
		$row[3] = $hhh;
	  }

	  //zmena barvy odkazu kvuli kontrastu
	  $v1 = substr($row[5], 0, 2);
	  $v2 = substr($row[5], 2, 2);
	  $v3 = substr($row[5], 4, 2);
	  $varr = ((hexdec($v1) + hexdec($v2) + hexdec($v3)) / 3);
	  if ($varr < 128) $varr = 'white';
	  else $varr = 'black';


	  // a vyrobime z nich odkazy!:)
	  foreach($famtre as &$tago){
		$tago = "<a href=\"" . $_SERVER['SCRIPT_URI'] . "?distag=" . $tago . "\" title=\"display articles having tag ". $tago ."\" style=\"color: ". $varr ."\" >" . $tago . "</a>";
	  }
	  $row[3] = implode(" -> ", array_reverse($famtre));
	  // konec vypisu cesty tagu

	  $row[2] = implode(" ", $words);
	  echo "<table class=\"bordered posts\" border=\"0\" title=\"$row[0]\"><tr><td class=\"tagged paddedtwo\" style=\"background-color: #". $row[5] .";\" ><span class=\"ttle\">" .$row[1]. "</span><span class=\"tags\">&nbsp;&nbsp;(" . $row[3] . ")</span></td><td class=\"linked paddedtwo\" style=\"background-color: #". $row[5] .";\" ><a href=\"". $_SERVER['SCRIPT_URI'] ."#". $row[4] . "\" title=\"link this article - among other ones\" style=\"color: ". $varr ."\" >&#35;</a><a name=\"" .$row[4]. "\">&nbsp;</a><a href=\"". $_SERVER['SCRIPT_URI'] ."?target=". $row[4] . "\" title=\"link this article - as a sole one\" style=\"color: ". $varr ."\" >&#43;</a></td></tr><tr><td colspan=\"2\" class=\"text\"> " . html_entity_decode($row[2], ENT_QUOTES, 'UTF-8') . "</td></tr></table> <br />";
	}

	// funkce, ktera cyklicky vytiskne vsechny prispevky (pomoci fce pro jednotlive prispevky)
	function PrintPosts($stream){
		  while ($row = mysql_fetch_array($stream, MYSQL_NUM)) {
			PrintSinglePost($row);
	  }
	}
	// xss blokovaci funkce - deaktivuje uzivatelem vlozeny javascript (likvidaci syntaxe)
	function DeScript($bsah){
	  $huhu = strlen($bsah);											   // huhu je delka retezce zpravy
	  for($indian = 0; $indian < ($huhu * 2); $indian++){				   // cyklus do delky (retezce zpravy * 2)
		if($indian % 2 && $indian != 0) $descriptor .= $bsah[$indian / 2]; // kdyz je index nenulovy sudy, vlozime znak z prispevku
		else $descriptor .= " ";										   // jinak vlozime mezeru
	  }
	  $bsah = "attempt to insert script detected! IP logged & banned..\n" . $descriptor; // + povidani na zacatek;-)
	return $bsah;
	}
	function pacified($input){
	  if(stristr($input, '<script') || stristr($input, htmlentities('<script'))) $input = DeScript($input);
	return $input;
	}

	function Delete($co){
	  //mysql_query("DELETE FROM resystdb WHERE id = '$co' ")or die("<script>alert('delete(".$co.") died');</script>");
	  mysql_query("SELECT 1+1")or die("Delete action failed");
	}
	// fce vraci rodice tagu - kvuli zobrazeni urovni tagu. zatim nevyuzito..
	function getParent($descendant){
		  $resultdesc = mysql_query("SELECT * FROM resysttag WHERE tagname = '$descendant' ;")or die("unable to comply: printing tagline");
		  $rettt = mysql_fetch_array($resultdesc, MYSQL_NUM);
		  return $rettt[2];
		}
	function getChild($parent){
		  $resultchild = mysql_query("SELECT resysttag.tagname FROM resysttag WHERE parenttag = '$parent' ;")or die("unable to comply: printing tagline");
		  $rettt5 = mysql_fetch_array($resultchild, MYSQL_NUM);
		  return $rettt5;
		}
	function printChildren($tagos){
	  foreach(getChild($tagos) as $parent){
		echo "direct subcategories of current tag( $tagos ) are: <a href=\"" . $_SERVER['SCRIPT_URI'] . "?distag=" . $parent . "\" title=\"display articles having tag ". $parent ."\" >" . $parent . "</a><br /><br /> ";
	  }
	}
	function getTagId($tagname){
		  $resultti = mysql_query("SELECT * FROM resysttag WHERE tagname = '$tagname' ;")or die("unable to comply: printing tagline");
		  $retu = mysql_fetch_array($resultti, MYSQL_NUM);
		  return $retu[0];
		}
	// --konec funkci

	// vypis hlasky po pridani clanku
	if(!empty($added)) echo "Current post successfully stored<br /><br />";

	// pokud chceme spravovat (menit) anebo uz jsme nejaky clanek zmenili nebo smazali(i vicero)
	if(!empty($change) || !empty($changed)
		|| !empty($deleted) || !empty($multidel)){
	  if(!empty($changed)) echo "Post successfully updated<br /><br />"; // vypis hlasky po uprave clanku
	  if(!empty($deleted)) echo "Post successfully deleted<br /><br />"; // vypis hlasky po smazani clanku
	  if(!empty($multidel)) echo $multidel . " posts successfully deleted<br /><br />"; // vypis hlasky po smazani vicero clanku
	  if(!empty($addone)) include "form0.php";	// pokud chceme pridat, pouze zobrazime form a Schluss
	  if(!empty($editposts)){ // pokud ale chceme menit clanky (tak si vsechny nacteme do resultu)
		$result = mysql_query("SELECT resystdb.time, resystdb.name, resystdb.text, resysttag.tagname, resystdb.id, resysttag.colour  FROM resystdb INNER JOIN resysttag ON resystdb.tag = resysttag.id ORDER BY id DESC ;")or die("unable to comply: printing results - edit");
		if(!empty($editone)){ // pokud vybereme editaci konkretniho clanku (id je v $editone)
		  $resultone = mysql_query("SELECT resystdb.time, resystdb.name, resystdb.text, resysttag.tagname, resystdb.id, resysttag.colour  FROM resystdb INNER JOIN resysttag ON resystdb.tag = resysttag.id WHERE resystdb.id = '$editone' ;")or die("unable to comply: printing results - edit");
		  $rowone = mysql_fetch_row($resultone); // nacteme konkr. zaznam v DB a pak ho nasypeme do includovaneho formulare (jinak by to nebyla moc editace)
		  include "form0.php";
		}
		// zobrazime tabulku (zatim) vsech prispevku s moznosti editace a mazani. Z kazdeho prispevku je v tbl. videt tema, tag a cas vlozeni + krizek na smazani
		echo "<table border=1><form id=\"form1\" method=\"post\" action=\"index.php\"><tr><td class=\"toptd\">topic</td><td class=\"toptd\">date of creation</td><td class=\"toptd\">tag</td><td class=\"toptd\">x</td><td class=\"toptd\"><input type=\"submit\" value=\"X\" id=\"submit\" title=\"Warning, you ar about to delete multiple entries!\" /></td></tr>";
		while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
		  echo "<tr><td> $row[1] </td><td> <a href=\"". $_SERVER['SCRIPT_URI'] . "?change=true&editposts=true&editone=". $row[4] ."\" title=\"edit post\" > $row[0]</a> </td><td style=\"background-color: #". $row[5] .";\" > $row[3] </td><td> <a href=\"" . $_SERVER['SCRIPT_URI'] ."?change=true&editposts=true&delete=" .$row[4] . "\" title=\"delete post\" >X</a> </td><td style=\"padding-left: 5px;\"> <input type=\"checkbox\" name=\"md[]\" value=\"". $row[4] ."\" title=\"select for mass deletion\" /> </td></tr>";
		}
		echo "<input type=\"hidden\" name=\"multipledelete\" value=\"true\" /></form></table>";
	  }

	}else{ // pokud ale zadna uprava neprobehla,
	  echo "<table border=\"0\"><tr><td id=\"menuleft\"></td><td>";
	  echo "<div class=\"\" id=\"clanky\">";
	  // pokud chceme zobrazit jediny clanek, udelame to..
	  if(!empty($target)){ // target je jakjinak nez id
		$resulttarget = mysql_query("SELECT resystdb.time, resystdb.name, resystdb.text, resysttag.tagname, resystdb.id, resysttag.colour  FROM resystdb INNER JOIN resysttag ON resystdb.tag = resysttag.id WHERE resystdb.id = '$target' ;")or die("unable to comply: printing results - target");
		$rowtarget = mysql_fetch_row($resulttarget); // nacteme konkr. zaznam v DB
		PrintSinglePost($rowtarget);
	  }elseif(!empty($distag)){				   //doladit funkci tag vs. tag id
		$resultdt = mysql_query("SELECT resystdb.time, resystdb.name, resystdb.text, resysttag.tagname, resystdb.id, resysttag.colour  FROM resystdb INNER JOIN resysttag ON resystdb.tag = resysttag.id WHERE resysttag.tagname = '$distag' ORDER BY id DESC;")or die("unable to comply: printing results");
		printChildren($distag);
		PrintPosts($resultdt);
		printChildren($distag);
	  }else{
		// ..jinak zobrazime vsechny clanky - nejdriv vyzdimem z DB, pak zavolame funkci pro vlastni vykresleni celyho streamu (po jednom:-))
		$result = mysql_query("SELECT resystdb.time, resystdb.name, resystdb.text, resysttag.tagname, resystdb.id, resysttag.colour  FROM resystdb INNER JOIN resysttag ON resystdb.tag = resysttag.id ORDER BY resystdb.id DESC ;")or die("unable to comply: printing results");
		PrintPosts($result);
	  }

	  echo "</div></td></tr></table>";
	  echo phpversion() . " " . mysql_client_encoding();

	}
	  // uprava vysledku z formulare
	  if(!empty($newtag)) {
		mysql_query("INSERT INTO resysttag(tagname, parenttag, colour) VALUES('" . pacified($newtag) . "', '" . $tag . "', '" . $kalr . "')")or die();
		$tag = $newtag;
	  }
	  // novy clanek / prispevek

	?>
	</div>
	<div id="foot">
	  naběsnil: <a href="./ab.php">helvete</a>
	</div>
  </body>
</html>
