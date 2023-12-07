<form action="<?php echo $link['action']; ?>" method="post">
	<?php foreach ($link['operations'] as $name => $val) { ?>
		<input type="hidden" name="<?php echo $name; ?>" value="<?php echo $val; ?>" />
	<?php } ?>
	<input type="submit" value="<?php echo $link['submit-val']; ?>" />
</form>
