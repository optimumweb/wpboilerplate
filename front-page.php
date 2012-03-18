<?php get_header(); ?>
	<?php wpbp_content_before(); ?>
	<div id="content">
		<div class="container <?php echo $wpbp_options['container_class']; ?>">
			<?php wpbp_main_before(); ?>
			<div id="main" class="<?php echo $wpbp_options['main_class']; ?>" role="main">
				<div class="container">
					<?php wpbp_loop_before(); ?>
					<?php get_template_part('loop', 'page'); ?>
					<?php wpbp_loop_after(); ?>
				</div>
			</div>
			<?php wpbp_main_after(); ?>
			<?php wpbp_sidebar_before(); ?>
			<aside id="sidebar" class="<?php echo $wpbp_options['sidebar_class']; ?>" role="complementary">
				<?php wpbp_sidebar_inside_before(); ?>
				<div class="container">
					<?php get_sidebar(); ?>
				</div>
				<?php wpbp_sidebar_inside_after(); ?>
			</aside>
			<?php wpbp_sidebar_after(); ?>
			<div class="clear"></div>
		</div>
	</div>
	<?php wpbp_content_after(); ?>
<?php get_footer(); ?>