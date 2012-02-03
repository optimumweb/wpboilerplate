<?php global $wpbp_options; ?>
<?php get_template_part('head'); ?>

	<?php wpbp_wrap_before(); ?>
	<div id="wrap" role="document">
		<?php wpbp_header_before(); ?>
		<header id="header" role="banner">
			<?php wpbp_header_inside(); ?>
            <div class="container <?php echo $wpbp_options['container_class']; ?>">
            	<div id="site-title">
					<h1><a href="<?php echo home_url(); ?>/"><?php bloginfo('name'); ?></a></h1>
					<h3><?php bloginfo('description'); ?></h3>
			    </div>
			    <nav id="main-nav" role="navigation">
			    	<div class="container">
				    	<?php wp_nav_menu( array( 'theme_location' => 'primary_navigation' ) ); ?>
				    	<div class="clear"></div>
			    	</div>
			    </nav>
            </div>
		</header>
		<div class="clear"></div>
		<?php wpbp_header_after(); ?>