<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" class="settings-form">
			
	<?php $replace = ""; if ($this->options['replace'])  $replace = implode("\n",$this->options['replace']); ?>
	<?php $willLinkback = "no"; if ($this->options['willLinkback'])  $willLinkback = $this->options['willLinkback']; ?>
	<?php $linkbackPostId = ""; if ($this->options['linkbackPostId'])  $linkbackPostId = $this->options['linkbackPostId']; ?>

	<h3>Step 1: Enter text/HTML to remove (one per line)</h3>
	<p><textarea name="find" id="find" class="small-text code" rows="6" style="width: 100%;"><?php if ($this->options['find']) echo htmlentities(implode("\n",$this->options['find'])); ?></textarea></p>
	<h3>Step 2: Enter your own footer credit (one per line)</h3>
	<?php wp_editor( $replace, 'replace', $settings = array('quicktags' => true, 'wpautop' => false,'editor_height' => '100', 'teeny' => false) ); ?>
	<h3>Step 3: Please support my work and spread the word (optional)</h3>
	<p>Help keep this plugin free by providing one link back at the bottom of one of your posts/pages.</p>
	<label><input type="radio" name="willLinkback" value="no" class="js-linkback" <?php if ($willLinkback == 'no') echo 'checked="checked"' ?>> No, thanks.</label><br>
	<label><input type="radio" name="willLinkback" value="yes" class="js-linkback" <?php if ($willLinkback == 'yes') echo 'checked="checked"' ?>> Yes, I will support you!</label>

	<div class="js-linkback-panel" style="<?php if ($willLinkback == 'no') echo 'display: none;' ?> margin-top: 15px;">
		<?php $post_args = array(
			'posts_per_page'   => -1,
			'orderby'          => 'title',
			'order'            => 'asc',
			'post_type'        => 'post',
			'post_status'      => 'publish',
			'suppress_filters' => true
		);
		$page_args = array(
			'posts_per_page'   => -1,
			'orderby'          => 'title',
			'order'            => 'asc',
			'post_type'        => 'page',
			'post_status'      => 'publish',
			'suppress_filters' => true
		);
		$posts_array = get_posts( $post_args );
		$pages_array = get_posts( $page_args );
		?>
		<strong>Select a post/page:</strong><br>
		<select name="linkbackPostId" style="margin-bottom: 15px;">
			<?php if (sizeof($posts_array) > 0) { ?>
				<option disabled>-- Posts --</option>
				<?php foreach ($posts_array as $item) { ?>
				<option value="<?php echo $item->ID ?>" <?php if ($linkbackPostId == $item->ID) echo 'selected=selected'?>><?php echo $item->post_title ?></option>
				<?php } ?>
			<?php } ?>
			<?php if (sizeof($pages_array) > 0) { ?>
				<option disabled>-- Pages --</option>
				<?php foreach ($pages_array as $item) { ?>
					<option value="<?php echo $item->ID ?>" <?php if ($linkbackPostId == $item->ID) echo 'selected=selected'?>><?php echo $item->post_title ?></option>
				<?php } ?>
			<?php } ?>
		</select>

		<div>
			<strong>The text below will appear at the bottom of the selected post/page.</strong><br>
			Get WordPress help, plugins, themes and tips at <a href="https://www.machothemes.com">MachoThemes.com</a>.
		</div>
	</div>
	<div style="margin-top: 20px;">
		<input type="submit" class="button" value="Save" />
	</div>
</form>
<script>
	jQuery('.js-linkback').change(function() {
		jQuery('.js-linkback-panel').toggle();
	});

</script>