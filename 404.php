<?php get_header(); ?>
	<?php wpbp_content_before(); ?>
	<div id="content">
		<div class="container <?php echo $wpbp_options['container_class']; ?>">
			<?php wpbp_main_before(); ?>
			<div id="main" role="main">
				<div class="container full-width">
					<h1><?php _e('File Not Found', 'wpbp'); ?></h1>
					<div class="error">
						<p class="bottom"><?php _e('The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'wpbp'); ?></p>
					</div>
					<p><?php _e('Please try the following:', 'wpbp'); ?></p>
					<ul> 
						<li><?php _e('Check your spelling', 'wpbp'); ?> </li>
						<li><?php printf(__('Return to the <a href="%s">home page</a>', 'wpbp'), home_url()); ?></li>
						<li><?php _e('Click the <a href="javascript:history.back()">Back</a> button', 'wpbp'); ?></li>
					</ul>
				</div>
			</div>
		<?php wpbp_main_after(); ?>
		</div>
	</div>
	<div class="clear"></div>
	<?php wpbp_content_after(); ?>
<?php get_footer(); ?>