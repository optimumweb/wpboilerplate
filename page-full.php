<?php
/*
Template Name: Full Width
*/
get_header(); ?>
	<?php wpbp_content_before(); ?>
	<div id="content">
		<div class="container <?php echo $wpbp_options['container_class']; ?>">
			<?php wpbp_main_before(); ?>
			<div id="main" role="main">
				<div class="container full-width">
					<?php wpbp_loop_before(); ?>
					<?php get_template_part('loop', 'page'); ?>
					<?php wpbp_loop_after(); ?>
				</div>
			</div>
			<?php wpbp_main_after(); ?>
		</div>
	</div>
	<div class="clear"></div>
	<?php wpbp_content_after(); ?>
<?php get_footer(); ?>