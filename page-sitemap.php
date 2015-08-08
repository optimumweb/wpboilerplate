<?php
/*
Template Name: Sitemap
*/
get_header(); ?>
	<?php wpbp_content_before(); ?>
	<section id="content">
		<div class="container">
			<div class="<?php wpbp_option('main_class'); ?>">
				<?php wpbp_main_before(); ?>
				<section id="main" role="main">
					<?php wpbp_main_inside_before(); ?>
                    <?php wpbp_loop_before(); ?>
                    <?php get_template_part('loop', 'page'); ?>
                    <?php wpbp_loop_after(); ?>
                    <h2><?php _e('Pages', 'wpbp'); ?></h2>
                    <ul><?php wp_list_pages('sort_column=menu_order&depth=0&title_li='); ?></ul>
                    <h2><?php _e('Posts', 'wpbp'); ?></h2>
                    <ul><?php wp_list_categories('title_li=&hierarchical=0&show_count=1'); ?></ul>
                    <h2><?php _e('Archives', 'wpbp'); ?></h2>
                    <ul><?php wp_get_archives('type=monthly&limit=12'); ?></ul>
					<?php wpbp_main_inside_after(); ?>
				</section>
				<?php wpbp_main_after(); ?>
			</div>
			<div class="<?php wpbp_option('sidebar_class'); ?>">
				<?php wpbp_sidebar_before(); ?>
				<aside id="sidebar" role="complementary">
					<?php wpbp_sidebar_inside_before(); ?>
                    <?php get_sidebar(); ?>
					<?php wpbp_sidebar_inside_after(); ?>
				</aside>
				<?php wpbp_sidebar_after(); ?>
			</div>
		</div>
	</section>
	<?php wpbp_content_after(); ?>
<?php get_footer(); ?>