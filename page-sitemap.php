<?php
/*
Template Name: Sitemap
*/
get_header(); ?>
	<?php wpbp_content_before(); ?>
	<div id="content">
		<div class="container <?php echo $wpbp_options['container_class']; ?>">
			<?php wpbp_main_before(); ?>
			<div id="main" class="<?php echo $wpbp_options['main_class']; ?>" role="main">
				<div class="container">
					<?php wpbp_loop_before(); ?>
					<?php get_template_part('loop', 'page'); ?>
					<?php wpbp_loop_after(); ?>
					<h2><?php _e('Pages', 'wpbp'); ?></h2>
					<ul><?php wp_list_pages('sort_column=menu_order&depth=0&title_li='); ?></ul>
					<h2><?php _e('Posts', 'wpbp'); ?></h2>
					<ul><?php wp_list_categories('title_li=&hierarchical=0&show_count=1'); ?></ul>
					<h2><?php _e('Archives', 'wpbp'); ?></h2>
					<ul><?php wp_get_archives('type=monthly&limit=12'); ?></ul>
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
		</div>
	</div>
	<?php wpbp_content_after(); ?>
<?php get_footer(); ?>