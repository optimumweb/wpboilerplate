<?php

function wpbp_get_scripts()
{
	if ( is_admin() ) return;

	// Available Javascript Librairies
	// You will need to enqueue the ones you want in your child theme
	
	// jQuery
	wpbp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js', array(), '1.7.1');
	
	// jQuery UI
	wpbp_register_script('jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js', array(), '1.8.16');
	
	// jQuery Plugins
	wpbp_register_script('scrollTo', 'http://firecdn.net/libs/scrollTo/jquery.scrollTo.min.js', array('jquery'), '1.4.2');
	wpbp_register_script('cycle', 'http://firecdn.net/libs/cycle/jquery.cycle.min.js', array('jquery'), '2.9998');
	wpbp_register_script('powerslide', 'http://firecdn.net/libs/powerslide/js/powerslide.min.js', array('jquery'), '1.1');
	wpbp_register_script('lightbox', 'http://firecdn.net/libs/lightbox/js/lightbox.min.js', array('jquery'), '2.51');
	wpbp_register_script('hoverIntent', 'http://firecdn.net/libs/hoverIntent/jquery.hoverIntent.min.js', array('jquery'), '6.0');
	
	// Twitter Bootstrap
	wpbp_register_script('bootstrap', 'http://firecdn.net/libs/bootstrap/js/bootstrap.min.js', array('jquery'), '2.0.2');
	
	// Ext JS
	wpbp_register_script('ext-core', 'https://ajax.googleapis.com/ajax/libs/ext-core/3.1.0/ext-core.js', array(), '3.1.0');
	
	// Dojo
	wpbp_register_script('dojo', 'https://ajax.googleapis.com/ajax/libs/dojo/1.6.1/dojo/dojo.xd.js', array(), '1.6.1');
	
	// MooTools
	wpbp_register_script('mootools', 'https://ajax.googleapis.com/ajax/libs/mootools/1.4.1/mootools-yui-compressed.js', array(), '1.4.1');
	
	// Modernizr
	wpbp_register_script('modernizr', 'http://firecdn.net/libs/modernizr/modernizr.js', array(), '2.0.6');
	
	// LessCSS
	wpbp_register_script('lesscss', 'http://firecdn.net/libs/less/less.min.js', array(), '1.2.1');
	
	// Sizzle
	wpbp_register_script('sizzle', 'http://firecdn.net/libs/sizzle/sizzle.min.js', array(), '1.5.1');
	
	// Highcharts
	wpbp_register_script('highcharts', 'http://firecdn.net/libs/highcharts/highcharts.min.js', array(), '2.1.9');
	
	// WPBP.js
	wpbp_register_script('wpbp', TEMPLATE_URI . '/js/wpbp.min.js', array('jquery'), '3.1.0');
    
    if ( wpbp_get_option('js_files') ) {
        foreach ( ( preg_split('/\r\n|\r|\n/', wpbp_get_option('js_files')) ) as $js_file ) {
        	wpbp_add_script( pathinfo($js_file, PATHINFO_FILENAME), $js_file );
        }
    }

	return;
}

function wpbp_register_script($handle, $src = false, $deps = array(), $ver = false, $in_footer = false)
{
	wp_deregister_script($handle);
	wp_register_script($handle, $src, $deps, $ver, $in_footer);
}

function wpbp_add_script($handle, $src = false, $deps = array(), $ver = false, $in_footer = true)
{
	wpbp_register_script($handle, $src, $deps, $ver, $in_footer);
	wp_enqueue_script($handle);
}

function wpbp_enqueue_scripts( $scripts = array() )
{
	if ( is_array( $scripts ) ) {
		foreach ( $scripts as $handle ) {
			wp_enqueue_script($handle);
		}
	}
}

function wpbp_get_styles()
{
	if ( is_admin() ) return;

	// 960gs
	wpbp_register_style('960gs', 'http://firecdn.net/libs/960gs/960.min.css');
	
	// jQuery UI
	wpbp_register_style('jquery-ui', 'http://firecdn.net/libs/jquery-ui/css/base/jquery.ui.all.css');
	wpbp_register_style('jquery-ui-smoothness', 'http://firecdn.net/libs/jquery-ui/css/smoothness/jquery-ui.css');
	wpbp_register_style('jquery-ui-lightness', 'http://firecdn.net/libs/jquery-ui/css/ui-lightness/jquery-ui.css');
	wpbp_register_style('jquery-ui-darkness', 'http://firecdn.net/libs/jquery-ui/css/ui-darkness/jquery-ui.css');
	wpbp_register_style('jquery-ui-redmond', 'http://firecdn.net/libs/jquery-ui/css/redmond/jquery-ui.css');
	wpbp_register_style('jquery-ui-blitzer', 'http://firecdn.net/libs/jquery-ui/css/blitzer/jquery-ui.css');
	
	// Twitter Bootstrap
	wpbp_register_style('bootstrap', 'http://firecdn.net/libs/bootstrap/css/bootstrap.min.css');
	wpbp_register_style('bootstrap-responsive', 'http://firecdn.net/libs/bootstrap/css/bootstrap-responsive.min.css');
	
	// Lightbox
	wpbp_register_style('lightbox', 'http://firecdn.net/libs/lightbox/css/lightbox.min.css');
	
	// PowerSlide
	wpbp_register_style('powerslide', 'http://firecdn.net/libs/powerslide/css/powerslide.css');
	
	// WPBP
	wpbp_register_style('default', 'http://firecdn.net/libs/wpbp/css/default.css');
	wpbp_register_style('wpbp', TEMPLATE_URI . '/css/wpbp.css');
	
	// WP Meta
	wpbp_register_style('wp-meta', get_stylesheet_directory_uri() . '/style.css');
	
	if ( wpbp_get_option('css_files') ) {
        foreach ( ( preg_split('/\r\n|\r|\n/', wpbp_get_option('css_files')) ) as $css_file ) {
            wpbp_add_style( pathinfo($css_file, PATHINFO_FILENAME), $css_file );
        }
    }

	return;
}

function wpbp_register_style($handle, $src = false, $deps = array(), $ver = false, $media = 'all')
{
	wp_deregister_style($handle);
	wp_register_style($handle, $src, $deps, $ver, $media);
}

function wpbp_add_style($handle, $src = false, $deps = array(), $ver = false, $media = 'all')
{
	wpbp_register_style($handle, $src, $deps, $ver, $media);
	wp_enqueue_style($handle, $src, $deps, $ver, $media);
}

function wpbp_enqueue_styles( $styles = array() )
{
	if ( is_array( $styles ) ) {
		foreach ( $styles as $handle ) {
			wp_enqueue_style($handle);
		}
	}
}

