<?php
/*
Template Name: List Subpages
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
                    <?php $children = wp_list_pages('title_li=&child_of='.$post->ID.'&echo=0'); ?>
                    <?php if ($children) : ?>
                    <ul>
                        <?php echo $children; ?>
                    </ul>
                    <?php endif; ?>
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