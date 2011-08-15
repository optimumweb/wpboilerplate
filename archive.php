<?php get_header(); ?>
	<?php wpbp_content_before(); ?>
	<div id="content">
		<div class="container <?php echo $wpbp_options['container_class']; ?>">
			<?php wpbp_main_before(); ?>
			<div id="main" class="<?php echo $wpbp_options['main_class']; ?>" role="main">
				<div class="container">
					<h1 class="page-title">
						<?php if ( is_day() ) : ?>
							<?php printf( __('Daily Archives: &laquo; %s &raquo;', 'wpbp'), get_the_date() ); ?>
						<?php elseif ( is_month() ) : ?>
							<?php printf( __('Monthly Archives: &laquo; %s &raquo;', 'wpbp'), get_the_date('F Y') ); ?>
						<?php elseif ( is_year() ) : ?>
							<?php printf( __('Yearly Archives: &laquo; %s &raquo;', 'wpbp'), get_the_date('Y') ); ?>
						<?php elseif ( is_category() ) : ?>
							<?php printf( __('Category: &laquo; %s &raquo;', 'wpbp'), single_cat_title('', false) ); ?>
						<?php elseif ( is_tag() ) : ?>
							<?php printf( __('Tag: &laquo; %s &raquo;', 'wpbp'), single_tag_title('', false) ); ?>
						<?php elseif ( is_author() ) : ?>
							<?php printf( __('Author: &laquo; %s &raquo;', 'wpbp'), single_author_title('', false) ); ?>
						<?php endif; ?>
					</h1>
					<hr />
					<?php wpbp_loop_before(); ?>
					<?php get_template_part('loop', 'category'); ?>
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
	<div class="clear"></div>
	<?php wpbp_content_after(); ?>
<?php get_footer(); ?>