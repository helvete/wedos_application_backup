<?php
function ColorPart($color, $hc){

	echo "<table padding=\"0\" margin = \"0\" border=\"0\">"; 	// height=\"10\" width=\"10\"
	$increm = 0;
	for($i = 1; $i < 9; $i++){
		$temp = $i-1;
		$temp *= 32;
		echo "<tr><td align=\"right\">". substr(dechex($temp), 0, 1) ."</td>";

		for($j = 1; $j < 33; $j++){
			if(strlen(dechex ($increm))<2) $hex = ("0" . dechex ($increm));
			else $hex = dechex ($increm);
				if($color == r)
					if($hc == $hex){
					echo "<td bgcolor=\"#" . $hex . "0000\"><input type=\"radio\" checked name=\"red\" value=\"". $hex ."\"></td>";
					}else{
					echo "<td bgcolor=\"#" . $hex . "0000\"><input type=\"radio\" name=\"red\" value=\"". $hex ."\"></td>";
					}
				if($color == g)
					if($hc == $hex){
					echo "<td bgcolor=\"#00" . $hex . "00\"><input type=\"radio\" checked name=\"green\" value=\"". $hex ."\"></td>";
					}else{
					echo "<td bgcolor=\"#00" . $hex . "00\"><input type=\"radio\" name=\"green\" value=\"". $hex ."\"></td>";
					}
				if($color == b)
					if($hc == $hex){
					echo "<td bgcolor=\"#0000" . $hex . "\"><input type=\"radio\" checked name=\"blue\" value=\"". $hex ."\"></td>";
					}else{
					echo "<td bgcolor=\"#0000" . $hex . "\"><input type=\"radio\" name=\"blue\" value=\"". $hex ."\"></td>";
					}

			$increm++;

		}
		echo "</tr>";
	}
	echo "</table>"; if($red == $hex){ }

}
foreach (array('red', 'green', 'blue') as $col) {
	$$col = isset($_GET[$col])
		? $_GET[$col]
		: 0;
}


echo "<form action=\"index.php\" method = get>";
echo "<table cellpadding=\"1\" cellspacing = \"0\" border=\"0\"><tr><td>";

ColorPart(r, $red);

echo "</td></tr><tr><td>";

ColorPart(g, $green);

echo "</td></tr><tr><td>";

ColorPart(b, $blue);

echo "</td></tr></table>";
?>
