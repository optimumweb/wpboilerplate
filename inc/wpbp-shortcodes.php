<?php

// [container cols="12"]...[/container]
function container_960gs($atts, $content = null) {
	extract(shortcode_atts(array(
		"cols" => "16"
	), $atts));
	return "<div class=\"container_" . $cols . "\">" . parse_shortcode_content($content) . "</div>";
}

add_shortcode("container", "container_960gs");

// [grid cols="6"]...[/grid]
function grid_960gs($atts, $content = null) {
	extract(shortcode_atts(array(
		"cols" => "1"
	), $atts));
	return "<div class=\"grid_" . $cols . "\">" . parse_shortcode_content($content) . "</div>";
}

add_shortcode("grid", "grid_960gs");

// [clear]
function clear_960gs() {
	return "<div class=\"clear\"></div>";
}

add_shortcode("clear", "clear_960gs");

// [hr]
function wpbp_hr() {
	return "<hr />";
}

add_shortcode("hr", "wpbp_hr");

// [box id="box-id" class="box-class"]...[/box]
function wpbp_box($atts, $content = null) {
	extract(shortcode_atts(array(
		"id" => "",
		"class" => ""
	), $atts));
	return "<div" . ( ( $id != "" ) ? " id=\"" . $id . "\"" : "" ) . " class=\"box" . ( ( $class != "" ) ? " " . $class : "" ) . "\">" . parse_shortcode_content($content) . "</div>";
}

add_shortcode("box", "wpbp_box");

// [article-header]...[/article-header]
function wpbp_article_header($atts = null, $content = null) {
	return "<div class=\"article-header\">" . parse_shortcode_content($content) . "</div>";
}

add_shortcode("article-header", "wpbp_article_header");

// Clean up shortcode $content

function parse_shortcode_content($content) {

	/* Parse nested shortcodes and add formatting. */
	$content = trim( wpautop( do_shortcode( $content ) ) );

	/* Remove '</p>' from the start of the string. */
	if ( substr( $content, 0, 4 ) == '</p>' )
		$content = substr( $content, 4 );

	/* Remove '<p>' from the end of the string. */
	if ( substr( $content, -3, 3 ) == '<p>' )
		$content = substr( $content, 0, -3 );

	/* Remove any instances of '<p></p>'. */
	$content = str_replace( array( '<p></p>' ), '', $content );

	$content = do_shortcode( $content );

	return $content;
}

/*
Plugin Name: Show Menu Shortcode
Plugin URI: http://www.mokamedianyc.com/dev/show-menu-shortcode/
Description: Provides a [show-menu] <a href="http://codex.wordpress.org/Shortcode_API">shortcodes</a> for displaying a menu within a post or page.  The shortcode accepts most parameters that you can pass to the <a href="http://codex.wordpress.org/Template_Tags/wp_nav_menu">wp_nav_menu()</a> function.  To show a menu, add [show-menu menu="Main-menu"] in the page or post body.
Author: Bob Matsuoka
Version: 1.0
Author URI: http://www.mokamedianyc.com
*/

function shortcode_show_menu($atts, $content, $tag)
{	
	global $post;
	
	// Set defaults
	$defaults = array(
		'menu'        	  => '',
		'container'       => 'div', 
		'container_class' => 'menu-container', 
		'container_id'    => '',
		'menu_class'      => 'menu', 
		'menu_id'         => '',
		'fallback_cb'     => 'wp_page_menu',
		'before'          => '',
		'after'           => '',
		'link_before'     => '',
		'link_after'      => '',
		'depth'			  => 0,
		'echo' 			  => false
	);
	
	// Merge user provided atts with defaults
	$atts = shortcode_atts($defaults, $atts);
	
	// Create output
	$out = wp_nav_menu($atts);
	
	return apply_filters('shortcode_show_menu', $out, $atts, $content, $tag);
	
}

add_shortcode('show-menu', 'shortcode_show_menu');