<div class="post" id="<?php echo $post->id; ?>">
	<div class="post-header">
		<h2><?php echo $post->title; ?></h2>&nbsp;
		<?php Tag::generateHtmlUpTree($post->tag->name); ?>
		<span class="post-links">
			<span class="target-link" title="Link for the sole post">
				<?php $post->getIndividual(); ?>
			</span>
			<span class="anchor-link" title="Link for the post among others">
				<a href="<?php echo $post->getAnchor(); ?>">#</a>
			</span>
		</span>
	</div>
	<div class="post-contents" title="<?php echo $post->timestamp; ?>">
	<?php echo $post->contents; ?>
	</div>
	<div class="post-footer" title="<?php echo $post->timestamp; ?>">&nbsp;</div>
</div>
