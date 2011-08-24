<?php

$required_files = array(
	'/inc/wpbp-activation.php',	// activation
	'/inc/wpbp-options.php',	// theme options
	'/inc/wpbp-cleanup.php',	// cleanup
	'/inc/wpbp-htaccess.php',	// rewrites for assets, h5bp htaccess
	'/inc/wpbp-hooks.php',		// hooks
	'/inc/wpbp-actions.php',	// actions
	'/inc/wpbp-extend.php',		// extend functions
	'/inc/wpbp-widgets.php',	// widgets
	'/inc/wpbp-shortcodes.php',	// shortcodes
	'/inc/wpbp-breadcrumb.php',	// breadcrumb
	'/inc/wpbp-custom.php'		// custom functions
);

echo get_theme_root() . get_stylesheet();

foreach ( $required_files as $f ) {
	$fpath = ( file_exists( get_theme_root() . $f ) ) ? get_theme_root() . $f : ( file_exists( get_template_directory() . $f ) ? get_template_directory() . $f : false );
	if ( $fpath ) {
		echo "Including: " . $fpath . "<br />\n";
		require_once( $fpath );
	}
}

$wpbp_options = wpbp_get_theme_options();

function wpbp_setup() {

	load_theme_textdomain('wpbp', get_template_directory() . '/lang');
	
	// tell the TinyMCE editor to use editor-style.css
	// if you have issues with getting the editor to show your changes then use the following line:
	// add_editor_style('editor-style.css?' . time());
	add_editor_style('editor-style.css');
	
	// http://codex.wordpress.org/Post_Thumbnails
	add_theme_support('post-thumbnails');
	//set_post_thumbnail_size(150, 150, false);
	
	// http://codex.wordpress.org/Post_Formats
	//add_theme_support('post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat'));

	function wpbp_custom_image_header_site() { }
	function wpbp_custom_image_header_admin() { ?>
		<style type="text/css">
			.appearance_page_custom-header #headimg { min-height: 0; }
		</style>
	<?php }
	add_custom_image_header('wpbp_custom_image_header_site', 'wpbp_custom_image_header_admin');
		
	add_theme_support('menus');
	register_nav_menus(array(
		'primary_navigation' => __('Primary Navigation', 'wpbp')
	));	
}

add_action('after_setup_theme', 'wpbp_setup');

// create widget areas: sidebar, footer
$sidebars = array('Nav', 'Sidebar', 'Footer');
foreach ($sidebars as $sidebar) {
	register_sidebar(array('name'=> $sidebar,
		'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="container">',
		'after_widget' => '</div><div class="clear"></div></div><hr />',
		'before_title' => '<h3>',
		'after_title' => '</h3>'
	));
}

?>