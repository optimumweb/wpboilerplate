<?php

if ( !function_exists('wpbp_get_author') ) {

	function wpbp_get_author($field = null, $ID = null)
	{
		if ( isset( $ID ) && is_int($ID) ) {
			$author = get_user_by('id', $ID);
		} elseif ( is_author() ) {
			$author = get_user_by('slug', get_query_var('author_name'));
		}
		return ( isset($field) ) ? ( ( isset( $author->$field ) ) ? $author->$field : false ) : $author ;
	}

}

if ( !function_exists('single_author_title') ) {

	function single_author_title($prefix = '', $display = true)
	{
		$author = wpbp_get_author('display_name');
		if ( !$author ) return false;
		$single_author_title = $prefix . $author;
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
		if ( has_post_thumbnail( $post_ID ) ) {
			return true;
		}
		else {
			$url = get_post_meta( $post_ID, 'featured_image_url', true );
			return ( strlen( $url ) > 0 ) ? true : false;
		}
	}

}

if ( !function_exists('wpbp_get_post_image') ) {

	function wpbp_get_post_image($post_ID, $attr = false)
	{
		$meta_featured_image_url = get_post_meta( $post_ID, 'featured_image_url', true );

		if ( isset( $meta_featured_image_url ) && strlen( $meta_featured_image_url ) > 0 ) {
			$url = $meta_featured_image_url;
		}

		elseif ( has_post_thumbnail( $post_ID ) ) {
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_ID, 'full' ) );
			$url = $image[0];
		}

		else {
			return false;
		}

		if ( isset( $url ) && strlen( $url ) > 0 ) {
			$image_attr = @getimagesize( $url );
			if ( is_array( $image_attr ) ) {
				list($width, $height, $type, $attr) = $image_attr;
				$ratio = round( $width / $height );
				return ( $attr != false && isset( $$attr ) ) ? $$attr : compact( 'url', 'width', 'height', 'ratio', 'type', 'attr' );
			}
		}

		return false;
	}

}

if ( !function_exists('wpbp_post_thumbnail') ) {

	function wpbp_post_thumbnail($post_ID, $width = 150, $height = 'auto', $quality = 90)
	{
		$post_image = wpbp_get_post_image( $post_ID );

		if ( $post_image ) {

			if ( $width == 'auto' && $height == 'auto' ) {
				$width = $post_image['width'];
				$height = $post_image['height'];
			}
			elseif ( $height == 'auto' ) {
				$height = round( $width / $post_image['ratio'] );
			}
			elseif ( $width == 'auto' ) {
				$width = round( $height * $post_image['ratio'] );
			}

			$alt = get_the_title( $post_ID );
			$src = get_bloginfo('template_directory') . '/img/resize.php?w=' . $width . '&h=' . $height . '&q=' . $quality . '&src=' . $post_image['url'];

			echo "<img class=\"post-thumbnail\" src=\"" . $src . "\" width=\"" . $width . "\" height=\"" . $height . "\" alt=\"" . $alt . "\" />\n";

			return;
		}

		return false;
	}

}

if ( !function_exists('wpbp_get_the_excerpt') ) {

	function wpbp_get_the_excerpt($post_ID, $limit = 250)
	{
		$post = get_post( $post_ID );
		$excerpt = ( isset( $post->post_excerpt ) && strlen( $post->post_excerpt ) > 0 ) ? $post->post_excerpt : substr( strip_tags( $post->post_content ), 0, 250 ) . '...';
		return $excerpt;
	}

}

if ( !function_exists('array_plot') ) {

	function array_plot($domain, $function)
	{
		$image = array();
		foreach ( $domain as $x ) {
			if ( is_int($x) || is_string($x) ) {
				$y = $function( $x );
				if ( isset( $y ) ) {
					$image[$x] = $y;
					echo $x . ": " . $y . "<br />\n";
				}
			}
		}
		return $image;
	}

}

?>
