<?php
$req = $_GET + $_POST;
$poss = array(
	'heslo' => false,
	'hideform' => false,
	'sent' => false,
	'km' => false,
	'liter' => false,
	'label' => false,
);
foreach ($poss as $varName => $initial){
	if (array_key_exists($varName, $req))
		$$varName = $req[$varName];
	else
		$$varName = $initial;
}

$passwd = "psiprdel";
if($heslo == $passwd) $auth = true;
else $auth = false;
if($sent && !$auth) echo "Nemate pravo vkladat zadna data<br />";
$fileData = file_get_contents('./data.txt');
if($fileData){
	$total = array('km' => 0, 'l' => 0);
	$datei = array();
	$labels = array();
	$lines = explode("\n", $fileData);
	foreach($lines as $line){
		$twoParts = explode("|", $line);
		if(!empty($twoParts[1])){
			array_push($datei, ($twoParts[1]/$twoParts[0])*100);
			$total['km'] += $twoParts[0];
			$total['l'] += $twoParts[1];
			array_push($labels, "\"".$twoParts[2]."\"");
		}
	}
	$printData = implode(",", $datei);
	$printLabels = implode(",", $labels);
}
if($sent && $km && $liter && $auth){
	$output = $fileData . "\n" . $km . "|" . $liter . "|" . $label;
	$file = "./data.txt";
	file_put_contents($file, $output);
	sleep(2);
	header("Location: ". $_SERVER['SCRIPT_URI'] ."?redirected=true");
}
?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="http://code.highcharts.com/highcharts.js"></script>
<script type="text/javascript">
var data = [<?php echo $printData; ?>];
var labels = [<?php echo $printLabels; ?>];
var serdat = new Array();
var space = " ";
for (var inc = 0; inc < data.length; inc++) {
	var obj = new Object();
	obj.y = data[inc];
	obj.label = labels[inc];
	serdat.push(obj);
}
$(function () {
		$('#chart').highcharts({
			chart: {
				type: 'line',
				marginRight: 20,
				marginBottom: 25
			},
			credits: {
				enabled: false
			},
			title: {
				text: 'Prumerna spotreba na nadrz',
				x: -20 //center
			},
			yAxis: {
				title: {
					text: 'Spotreba (v litrech LPG na 100km) '
				},
				plotLines: [{
					value: 0,
					width: 1,
					color: '#808080'
				}]
			},
			xAxis: {
				allowDecimals: false
			},
			tooltip: {
				formatter: function() {
					return this.point.label + "<br /><b>" + Highcharts.numberFormat(this.y, 2) + "</b> l " + space + "LPG/100km";
				}
			},
			legend: {
				layout: 'vertical',
				align: 'right',
				verticalAlign: 'top',
				x: -10,
				y: 100,
				borderWidth: 0
			},
			series: [{
				name: 'Spotreba',
				showInLegend : false,
				data: serdat
			}]
		});
	});
</script>
<?php if(!$hideform){ ?>
<div id="formal">
<form id="form0" method="post">
<label for="km"> kms: </label><input type="text" name="km" value="" />
<label for="liter"> liters: </label><input type="text" name="liter" value="" />
<label for="label"> label: </label><input type="text" name="label" value="" /><br /><br />
<label for="heslo"> heslo: </label><input type="text" name="heslo" value="" />
<input type="submit" value="poslat" id="submit" />
<input type="hidden" name="sent" value="true" />
</form>
</div>
<?php }else echo ""; ?>

<div id="chart" style="width = 95%; height: 450px; background-color: gray;">

</div>
<h4>Statistiky</h4>
<dl>
	<dt>Projetych kilometru:</dt>
	<dd><?php echo $total['km'] . " km";?></dd>
	<dt>Spotrebovanych litru:</dt>
	<dd><?php echo $total['l']. " l";?></dd>
	<dt>Prumerna spotreba:</dt>
	<dd><?php echo $t = round(($total['l']/$total['km'])*100, 2) . " l/100km";?></dd>
	<dt>Prumerny dojezd na litr</dt>
	<dd><?php echo round(($total['km']/$total['l']), 2) . " km/l";?></dd>
	<dt>Odhad celkove ceny paliva (predpoklad: 1l LPG == 16.5 CZK):</dt>
	<dd><?php echo round($total['l']*16.5, 2) . " CZK";?></dd>
	<dt>Odhad prumerne ceny paliva na kilometr</dt>
	<dd><?php echo round($t/100*16.5, 2) . " CZK/km";?></dd>
</dl>
<center>
	Fuel meter v 0.21 (2015-04-17)
</center>
