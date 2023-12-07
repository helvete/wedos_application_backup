<html>
<head>
<title></title>
</head>
<body>
<?php

//</tr></table>

echo "<table align=\"center\" cellpadding=\"0\" cellspacing = \"0\" border=\"0\" valign=\"top\"><tr><td cellpadding=\"0\">";

include "palette.php";

$varr = (hexdec($red) + hexdec($green) + hexdec($blue))/3;
if ($varr < 128) $varr = 'white';
else $varr = 'black';

echo "</td><td align=\"center\"  bgcolor=\"#" . $red . $green . $blue . "\"><font color= ". $varr .">";

echo "<h1>Barevna php michacka</h1><br />";
echo "vysledna barva:  #". $red . $green . $blue. "<br /><br /><input type=submit value=\"obnovit\"></form></td>";

echo "</body></html>";

?>
