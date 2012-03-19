<?php get_header(); ?>
	<?php wpbp_content_before(); ?>
	<section id="content">
		<div class="container <?php echo $wpbp_options['container_class']; ?>">
			<div class="<?php echo $wpbp_options['main_class']; ?>">
				<?php wpbp_main_before(); ?>
				<section id="main" role="main">
					<?php wpbp_main_inside_before(); ?>
					<div class="container">
						<?php wpbp_loop_before(); ?>
						<?php get_template_part('loop', 'single'); ?>
						<?php wpbp_loop_after(); ?>
					</div>
					<?php wpbp_main_inside_after(); ?>
				</section>
				<?php wpbp_main_after(); ?>
			</div>
			<div class="<?php echo $wpbp_options['sidebar_class']; ?>">
				<?php wpbp_sidebar_before(); ?>
				<aside id="sidebar" role="complementary">
					<?php wpbp_sidebar_inside_before(); ?>
					<div class="container">
						<?php get_sidebar(); ?>
					</div>
					<?php wpbp_sidebar_inside_after(); ?>
				</aside>
				<?php wpbp_sidebar_after(); ?>
			</div>
		</div>
	</section>
	<?php wpbp_content_after(); ?>
<?php get_footer(); ?>