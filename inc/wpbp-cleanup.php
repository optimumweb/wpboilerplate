<?php

// add to robots.txt
// http://codex.wordpress.org/Search_Engine_Optimization_for_WordPress#Robots.txt_Optimization
function wpbp_robots() {
	echo "Disallow: /cgi-bin\n";
	echo "Disallow: /wp-admin\n";
	echo "Disallow: /wp-includes\n";
	echo "Disallow: /wp-content/plugins\n";
	echo "Disallow: /plugins\n";
	echo "Disallow: /wp-content/cache\n";
	echo "Disallow: /wp-content/themes\n";
	echo "Disallow: /trackback\n";
	echo "Disallow: /feed\n";
	echo "Disallow: /comments\n";
	echo "Disallow: /category/*/*\n";
	echo "Disallow: */trackback\n";
	echo "Disallow: */feed\n";
	echo "Disallow: */comments\n";
	echo "Disallow: /*?*\n";
	echo "Disallow: /*?\n";
	echo "Allow: /wp-content/uploads\n";
	echo "Allow: /assets";
}
add_action( 'do_robots', 'wpbp_robots' );

// WPML fixes
// prevent loading WPML css & js
if ( ! defined( 'ICL_DONT_LOAD_NAVIGATION_CSS' ) ) define( 'ICL_DONT_LOAD_NAVIGATION_CSS', true );
if ( ! defined( 'ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS' ) ) define( 'ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS', true );
if ( ! defined( 'ICL_DONT_LOAD_LANGUAGES_JS' ) ) define( 'ICL_DONT_LOAD_LANGUAGES_JS', true );

// set the post revisions to 5 unless the constant
// was set in wp-config.php to avoid DB bloat
if ( ! defined( 'WP_POST_REVISIONS' ) ) define( 'WP_POST_REVISIONS', 5 );

// Remove stupid WP Emoji
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );
