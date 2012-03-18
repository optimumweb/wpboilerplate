<?php
/*
Template Name: Full Width
*/
get_header(); ?>
    <?php wpbp_content_before(); ?>
	<div id="content">
		<div class="container <?php echo $wpbp_options['container_class']; ?>">
			<?php wpbp_main_before(); ?>
			<section id="main" role="main">
				<?php wpbp_main_inside_before(); ?>
				<div class="container">
					<?php wpbp_loop_before(); ?>
					<?php get_template_part('loop', 'page'); ?>
					<?php wpbp_loop_after(); ?>
				</div>
				<?php wpbp_main_inside_after(); ?>
			</section>
			<?php wpbp_main_after(); ?>
		</div>
	</div>
	<?php wpbp_content_after(); ?>
<?php get_footer(); ?>