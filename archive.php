<?php get_header(); ?>
	<?php wpbp_content_before(); ?>
	<section id="content">
		<div class="container <?php wpbp_option('container_class'); ?>">
			<div class="<?php wpbp_option('main_class'); ?>">
				<?php wpbp_main_before(); ?>
				<section id="main" role="main">
					<?php wpbp_main_inside_before(); ?>
					<div class="container">
						<h1 class="page-title">
							<?php if ( is_day() ) : ?>
								<?php printf( __('Daily Archives: %s', 'wpbp'), get_the_date() ); ?>
							<?php elseif ( is_month() ) : ?>
								<?php printf( __('Monthly Archives: %s', 'wpbp'), get_the_date('F Y') ); ?>
							<?php elseif ( is_year() ) : ?>
								<?php printf( __('Yearly Archives: %s', 'wpbp'), get_the_date('Y') ); ?>
                            <?php else : ?>
                                <?php $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); echo $term->name; ?>
							<?php endif; ?>
						</h1>
						<?php wpbp_loop_before(); ?>
						<?php get_template_part('loop', 'archive'); ?>
						<?php wpbp_loop_after(); ?>
					</div>
					<?php wpbp_main_inside_after(); ?>
				</section>
				<?php wpbp_main_after(); ?>
			</div>
			<div class="<?php wpbp_option('sidebar_class'); ?>">
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