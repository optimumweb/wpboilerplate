<?php get_header(); ?>
	<?php wpbp_content_before(); ?>
	<div id="content">
		<div class="container <?php echo $wpbp_options['container_class']; ?>">
			<div class="<?php echo $wpbp_options['main_class']; ?>">
				<?php wpbp_main_before(); ?>
				<section id="main" role="main">
					<?php wpbp_main_inside_before(); ?>
					<div class="container">
						<h1 class="page-title">
							<?php if ( is_day() ) : ?>
								<?php printf( __('Daily Archives: &laquo; %s &raquo;', 'wpbp'), get_the_date() ); ?>
							<?php elseif ( is_month() ) : ?>
								<?php printf( __('Monthly Archives: &laquo; %s &raquo;', 'wpbp'), get_the_date('F Y') ); ?>
							<?php elseif ( is_year() ) : ?>
								<?php printf( __('Yearly Archives: &laquo; %s &raquo;', 'wpbp'), get_the_date('Y') ); ?>
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
	</div>
	<?php wpbp_content_after(); ?>
<?php get_footer(); ?>