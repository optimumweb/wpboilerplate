<?php global $wpbp_options; ?>
<?php get_template_part('head'); ?>

	<?php wpbp_wrap_before(); ?>
	<div id="wrap" role="document">
		<?php wpbp_header_before(); ?>
		<header id="header" role="banner">
			<?php wpbp_header_inside(); ?>
            <h1 class="site-title">
                <a href="<?php echo home_url(); ?>/"><?php bloginfo('name'); ?></a>
				<span><?php bloginfo('description'); ?></span>
			</h1>
			<nav id="main-nav" role="navigation">
				<div class="container <?php echo $wpbp_options['container_class']; ?>">
					<?php dynamic_sidebar("Nav"); ?>
					<div class="clear"></div>
				</div>
			</nav>
		</header>
		<div class="clear"></div>
		<?php wpbp_header_after(); ?>