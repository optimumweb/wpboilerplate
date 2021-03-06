<?php get_header(); ?>
	<?php wpbp_content_before(); ?>
	<section id="content">
		<div class="<?php wpbp_container_class(); ?>">
			<div class="<?php wpbp_option( 'main_class' ); ?>">
				<?php wpbp_main_before(); ?>
				<section id="main" role="main">
					<?php wpbp_main_inside_before(); ?>
					<?php if ( $author = get_queried_object() ) : ?>
						<h1 class="page-title">
							<?php printf( __( 'Author: %s', 'wpbp'), $author->display_name ); ?>
						</h1>
						<div class="author-description">
							<?php the_author_meta( 'description' ); ?>
						</div>
					<?php endif; ?>
                    <?php wpbp_loop_before(); ?>
                    <?php get_template_part( 'loop', 'author' ); ?>
                    <?php wpbp_loop_after(); ?>
					<?php wpbp_main_inside_after(); ?>
				</section>
				<?php wpbp_main_after(); ?>
			</div>
			<div class="<?php wpbp_option( 'sidebar_class' ); ?>">
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
