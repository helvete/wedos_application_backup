<html><head><title>redirector</title></head><body>
<div>
<div style="padding: 10px; margin: auto; background-color: #eeeeaa; width: 450px;">
<form id="fo" method="post">
dlouhy link: <input type=text width="80" name="inp">
<input type=hidden name="active" value="true">
<input type=submit value="zkrat!" class="submit">

</form>


<?php

function MaxId(){
$r = mysql_query("SELECT Max(id) FROM redir;")or die("unable to comply"); //zjisteni posledniho data
	if (mysql_fetch_row($r))
	$Mx = mysql_result($r, 0);
return $Mx;
}



include "connectdb.php";

$a = isset($_GET['a'])
	? $_GET['a']
	: null;

if(!$a == null){


$lt = mysql_query("SELECT link FROM redir WHERE id = $a")or die("unable to comply");
	if (mysql_fetch_row($lt))
	$hu = mysql_result($lt, 0);
echo $hu;
header("Location: ". $hu ."");
}
if($a>MaxId()){
	echo "kdopak se nam to stoura v URL?:-D";
}

$active = isset($_POST['active'])
	? $_POST['active']
	: null;
$inp = isset($_POST['inp'])
	? $_POST['inp']
	: null;

if($active && !$inp == null){


$temp = MaxId()+1;

mysql_query("INSERT INTO redir(id, link) VALUES('" . $temp . "', '" . $inp . "')")or die();

//echo "<br />";<br />
echo "vas odkaz: <a href=\"" .$inp.  "\">" . $inp . "</a> je uchovavan jako: ";
echo "<a href=\"http://r.bahno.net/?a=" . $temp . "\"> http://r.bahno.net/?a=" . $temp . "</a>";

}
if($active && $inp == null){
	echo "toto neni link, alobrz to je nic, rekl zajic";
}


?>
</div></div>
</body></html>
