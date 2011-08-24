<?php

if ( !function_exists('single_author_title') ) {

	function single_author_title($prefix = '', $display = true)
	{
		global $wp_query;
		$single_author_title = $prefix . $wp_query->queried_object->display_name;
		if ( $display ) {
			echo $single_author_title;
		} else {
			return $single_author_title;
		}
	}

}

if ( !function_exists('get_theme_directory') ) {

	function get_theme_directory()
	{
		return get_theme_root() . '/' . get_stylesheet();
	}

}

?>
