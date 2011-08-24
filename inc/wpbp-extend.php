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

if ( !function_exists('wpbp_has_post_thumbnail') ) {

	function wpbp_has_post_thumbnail($post_ID)
	{
		$url = get_post_meta( $post_ID, 'featured_image_url', true );
		return ( strlen( $url ) > 0 ) ? true : false;
	}

}

if ( !function_exists('wpbp_post_thumbnail') ) {

	function wpbp_post_thumbnail($post_ID, $width = 150, $height = 'auto', $quality = 90)
	{
		$orig_url = get_post_meta( $post_ID, 'featured_image_url', true );

		list($orig_width, $orig_height, $orig_type, $orig_attr) = getimagesize( $orig_url );

		$orig_ratio = round( $orig_width / $orig_height );

		if ( $width == 'auto' && $height == 'auto' ) {
			$width = $orig_width;
			$height = $orig_height;
		}
		elseif ( $height == 'auto' ) {
			$height = round( $width / $orig_ratio );
		}
		elseif ( $width == 'auto' ) {
			$width = round( $height * $orig_ratio );
		}

		$alt = get_the_title( $post_ID );
		$src = get_bloginfo('template_directory') . '/img/resize.php?w=' . $width . '&h=' . $height . '&q=' . $quality . '&src=' . $orig_url;
		echo "<img class=\"post-thumbnail\" src=\"" . $src . "\" width=\"" . $width . "\" height=\"" . $height . "\" alt=\"" . $alt . "\" />\n";
		return;
	}

}

?>
