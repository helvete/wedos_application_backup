<?php

$feb = range(8, 31);
$feb = array_flip(array_map(function ($val) {
	return str_pad($val, 5, '.07', STR_PAD_RIGHT);
}, $feb));
$mar = range(1, 4);
$mar = array_flip(array_map(function ($val) {
	return str_pad(str_pad($val, 2, '0', STR_PAD_LEFT), 5, '.08', STR_PAD_RIGHT);
}, $mar));

$allDays = $feb + $mar;

$fh = fopen('gray-history.log', 'r');
$completedDays = [];

while ($line = fgets($fh, 6)) {
	$completedDays[$line] = 1;
}
fclose($fh);


//fill & remove
if (isset($_POST['complete']) && array_key_exists($_POST['complete'], $allDays)) {
	if (array_key_exists($_POST['complete'], $completedDays)) {
		$completedSaved = file_get_contents('gray-history.log');
		file_put_contents('gray-history.log', str_replace(PHP_EOL . $_POST['complete'], '', $completedSaved));
	} else {
		file_put_contents('gray-history.log', PHP_EOL . $_POST['complete'], FILE_APPEND);
	}
	header('Location: ' . $_SERVER['PHP_SELF']);
	exit(0);
}

//print
echo "<table border='1'><tr>";
$iterator = 1;
foreach ($allDays as $date => $whatever) {
	$completed = array_key_exists($date, $completedDays);
	echo '<td style="' . ($completed ? "background-color: gray;" : "") . '"><form method="POST"><input name="complete" type="submit" value="' . $date . '"></form></td>';
	if ($iterator % 7 === 0 && $iterator !== count($allDays)) {
		echo "</tr><tr>";
	}
	$iterator++;
}
echo "</tr></table>";