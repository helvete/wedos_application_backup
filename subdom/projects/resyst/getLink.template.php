<a class="link" href="
	<?php
	$target = BASE_URI;
	$first = true;
	foreach ($link['operations'] as $name => $val) {
		if ($first) {
			$target .= '?';
			$first = !$first;
		} else {
			$target .= '&';
		}
		$target .= "$name=$val";
	}
	echo $target;
	?>
"><?php echo $link['submit-val']; ?></a>
