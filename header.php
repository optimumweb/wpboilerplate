<?php get_template_part('head'); ?>
	<?php wpbp_wrap_before(); ?>
	<div id="wrap" role="document">
		<?php wpbp_header_before(); ?>
		<header id="header" role="banner">
			<?php wpbp_header_inside_before(); ?>
            <div class="container">
                <div class="grid_4">
                    <hgroup id="site-title">
                        <h1><a href="<?php echo home_url(); ?>/"><?php bloginfo('name'); ?></a></h1>
                        <h3><?php bloginfo('description'); ?></h3>
                    </hgroup>
                </div>
                <div class="grid_8">
                    <nav id="main-nav" role="navigation">
                        <div class="container">
                            <?php wp_nav_menu( array( 'theme_location' => 'primary_navigation' ) ); ?>
                            <div class="clear"></div>
                        </div>
                    </nav>
                </div>
            </div>
            <?php wpbp_header_inside_after(); ?>
		</header>
		<?php wpbp_header_after(); ?>