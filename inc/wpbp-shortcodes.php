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
