<?php get_template_part('head'); ?>
	<?php wpbp_wrap_before(); ?>
	<div id="wrap" role="document">
		<?php wpbp_header_before(); ?>
		<header id="header" role="banner">
			<?php wpbp_header_inside_before(); ?>
            <div class="<?php wpbp_container_class(); ?>">
                <div class="grid_4 mobile-center">
                    <hgroup id="site-title">
                        <h1><a href="<?php echo home_url(); ?>/"><?php bloginfo('name'); ?></a></h1>
                        <h3><?php bloginfo('description'); ?></h3>
                    </hgroup>
                </div>
                <div class="grid_8 text-right mobile-center">
                    <nav id="main-nav" role="navigation">
                        <?php wp_nav_menu(array( 'theme_location' => 'primary_navigation' )); ?>
                    </nav>
                </div>
            </div>
            <?php wpbp_header_inside_after(); ?>
		</header>
		<?php wpbp_header_after(); ?>