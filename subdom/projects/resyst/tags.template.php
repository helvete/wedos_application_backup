<?php
echo "<span class=\"post-tag\">";
$link = array('action' => '', 'operations' => array(), 'submit-val' => 'root',);
include './getLink.template.php';
echo "</span>";

$i = 0; do {
	echo "<span class=\"arrow\">&nbsp;>>&nbsp;</span>";
?>
<span class="post-tag l<?php echo $i; ?>"
	title="Category tag level <?php echo $i; ?>"
	style="border-top: 2px solid #<?php echo $tag->colour?>;">
	<?php
		$link = array(
			'action' => '',
			'operations' => array(
				'displayTag' => $tag->name,
			),
			'submit-val' => $tag->name,
		);
		include './getLink.template.php';
		$i++;
	?>
</span>
<?php
	$tag = $tag->parent;
} while ( ! empty($tag));
?>
