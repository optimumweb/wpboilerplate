<?php get_header(); ?>
	<?php wpbp_content_before(); ?>
	<div id="content">
		<div class="container <?php echo $wpbp_options['container_class']; ?>">
			<?php wpbp_main_before(); ?>
			<div id="main" class="<?php echo $wpbp_options['main_class']; ?>" role="main">
				<div class="container">
					<h1 class="page-title">
						<?php printf( __('Author: &laquo; %s &raquo;', 'wpbp'), single_author_title('', false) ); ?>
					</h1>
					<div class="author-info">
						<?php
							$author_info = array_plot( array(
								'user_login', 'user_nicename', 'user_email', 'user_url',
								'display_name', 'nickname', 'first_name', 'last_name',
								'description', 'jabber', 'aim', 'yim', 'google_profile'
							), 'get_the_author_meta' );
							var_dump($wp_query);
							var_dump( $author_info );
							var_dump( get_the_author_meta('google_profile') );
						?>
						<?php if ( $author_info['google_profile'] ) : ?>
							<a href="<?php echo $author_info['google_profile']; ?>" rel="me">
								<?php printf( __("%s's Google Profile", "wpbp"), $author_info['display_name'] ); ?>
							</a>
						<?php endif; ?>
					</div>
					<?php wpbp_loop_before(); ?>
					<?php get_template_part('loop', 'author'); ?>
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