<?php

if ( !defined('TEMPLATE_DIRECTORY') ) define('TEMPLATE_DIRECTORY', get_template_directory());
if ( !defined('TEMPLATE_URI') )       define('TEMPLATE_URI',       get_template_directory_uri());
if ( !defined('THEME_DIRECTORY') )    define('THEME_DIRECTORY',    get_theme_root() . '/' . get_stylesheet());
if ( !defined('THEME_URI') )          define('THEME_URI',          get_stylesheet_directory_uri());

$required_files = array(
    '/inc/wpbp-activation.php',   // activation
    '/inc/wpbp-options.php',      // theme options
	'/inc/wpbp-security.php',     // security
    '/inc/wpbp-cleanup.php',      // cleanup
    '/inc/wpbp-hooks.php',        // hooks
    '/inc/wpbp-actions.php',      // actions
    '/inc/wpbp-filters.php',      // filters
    '/inc/wpbp-lib.php',          // library
    '/inc/wpbp-enqueue.php',      // takes care of enqueued scripts and stylesheets
    '/inc/wpbp-validation.php',   // form validation tool
    '/inc/wpbp-form-builder.php', // makes building forms easy
    '/inc/wpbp-framework.php',    // set of functions and tools
    '/inc/wpbp-extend.php',       // extend wordpress functions
    '/inc/wpbp-walkers.php',      // customer nav menu walkers
    '/inc/wpbp-mail.php',         // simple but powerful mail class
    '/inc/wpbp-widgets.php',      // widgets
    '/inc/wpbp-shortcodes.php',   // shortcodes
    '/inc/wpbp-utm.php',          // google analytics utm variables
    '/inc/wpbp-breadcrumb.php',   // breadcrumb
	'/inc/wpbp-adtrack.php',      // ad tracking
    '/inc/wpbp-custom.php'        // custom functions
);

foreach ( $required_files as $f ) {
	$theme_fpath = THEME_DIRECTORY . $f;
	$template_fpath = TEMPLATE_DIRECTORY . $f;
	$fpath = file_exists($theme_fpath) ? $theme_fpath : ( file_exists($template_fpath) ? $template_fpath : false );
	if ( $fpath ) {
		require_once($fpath);
	}
}

$wpbp_options = wpbp_get_theme_options();

function wpbp_setup()
{
	load_theme_textdomain('wpbp', TEMPLATE_DIRECTORY . '/lang');

	// tell the TinyMCE editor to use editor-style.css
	add_editor_style('editor-style.css');

	// http://codex.wordpress.org/Post_Thumbnails
	add_theme_support('post-thumbnails');

	add_theme_support('menus');

	register_nav_menus(array(
		'primary_navigation'   => __('Primary Navigation', 'wpbp'),
		'secondary_navigation' => __('Secondary Navigation', 'wpbp'),
	));
}
add_action('after_setup_theme', 'wpbp_setup');

function wpbp_register_sidebars($sidebars)
{
	if ( is_array($sidebars) && count($sidebars) > 0 ) {
		foreach ( $sidebars as $sidebar_id => $sidebar ) {
		    $args = array(
                'name'          => $sidebar,
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '<div class="clear"></div></div>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>'
            );

		    if ( !is_numeric($sidebar_id) ) {
		        $args['id'] = $sidebar_id;
            }

			register_sidebar($args);
		}
		return true;
	}
	return false;
}

// create widget areas: sidebar, footer
wpbp_register_sidebars(array( 'Sidebar' ));

// Enable shortcodes in widgets
add_filter('widget_text', 'do_shortcode');
