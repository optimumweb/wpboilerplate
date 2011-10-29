<?php

if ( !function_exists('set_post_ID') ) {
    
    function set_post_ID(&$post_ID)
	{
		if ( !isset($post_ID) || !is_int($post_ID) ) {
            global $wp_query;
            if ( isset($wp_query->post->ID) ) {
                $post_ID = $wp_query->post->ID;
            } else {
                throw new Exception('No post ID.');
            }
        }
	}
    
}

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
            if ( strlen( $url ) > 0 ) return true;
		}
        return false;
	}

}

if ( !function_exists('wpbp_is_valid_image') ) {

    function wpbp_is_valid_image($url, $valid_image_types = array( IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG ))
	{
        if ( !is_array($valid_image_types) ) return null;
        
        $url = wpbp_get_full_url($url);
        
        if ( function_exists('exif_imagetype') ) {
            $image_type = exif_imagetype($url);
        }
        else {
            $image_attr = @getimagesize($url);
            if ( isset($image_attr) && is_array($image_attr) ) {
                $image_type = $image_attr[2];
            }
        }
        if ( isset($image_type) && in_array($image_type, $valid_image_types) ) {
            return true;
        }
        return false;
	}
    
}

if ( !function_exists('wpbp_get_image_size') ) {

	function wpbp_get_image_size($url)
	{
		$url = wpbp_get_full_url($url);
        
        if ( wpbp_is_valid_image($url) ) {

    		$image_attr = @getimagesize($url);
    
    		if ( isset($image_attr) && is_array($image_attr) ) {
    			list($width, $height, $type, $attr) = $image_attr;
    			$ratio = round( $width / $height );
    			return compact('url', 'width', 'height', 'ratio', 'type', 'attr');
    		}
        
        }
        
		return false;
	}

}

if ( !function_exists('wpbp_resize_image_url') ) {

	function wpbp_resize_image_url($url, $width = 'auto', $height = 'auto', $q = '90')
	{
		$image_attr = wpbp_get_image_size($url);

		if ( isset($image_attr) && is_array($image_attr) ) {

			if ( $width == 'auto' && $height == 'auto' ) {
				$width = $image_attr['width'];
				$height = $image_attr['height'];
			}
			elseif ( $height == 'auto' ) {
				$height = round( $width / $image_attr['ratio'] );
			}
			elseif ( $width == 'auto' ) {
				$width = round( $height * $image_attr['ratio'] );
			}

			return get_bloginfo('template_directory') . '/img/resize.php?w=' . $width . '&h=' . $height . '&q=' . $q . '&src=' . $url;
		}

		return false;
	}

}

if ( !function_exists('wpbp_get_post_image') ) {

	function wpbp_get_post_image($post_ID, $attr = false)
	{
		$meta_featured_image_url = get_post_meta( $post_ID, 'featured_image_url', true );

		if ( isset($meta_featured_image_url) && strlen($meta_featured_image_url) > 0 ) {
			$url = $meta_featured_image_url;
		}
		elseif ( has_post_thumbnail($post_ID) ) {
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_ID, 'full' ) );
			$url = $image[0];
		}
		else {
			return false;
		}

		if ( isset($url) && strlen($url) > 0 ) {
			$image_attr = wpbp_get_image_size($url);
			if ( isset($image_attr) && is_array($image_attr) ) {
				return ( $attr !== false && isset($$attr) ) ? $$attr : compact('url', 'width', 'height', 'ratio', 'type', 'attr');
			}
		}

		return false;
	}

}

if ( !function_exists('wpbp_post_thumbnail') ) {

	function wpbp_post_thumbnail($post_ID, $width = 150, $height = 'auto', $quality = 90)
	{
		$post_image_url = wpbp_get_post_image($post_ID, 'url');

		if ( isset($post_image_url) && $post_image_url !== false && strlen($post_image_url) > 0 ) {
			$alt = get_the_title($post_ID);
			$src = wpbp_resize_image_url($post_image_url, $width, $height, $quality);
            if ( isset($src) && $src !== false && strlen($src) > 0 ) {
                echo "<img class=\"post-thumbnail\" src=\"" . $src . "\" alt=\"" . $alt . "\" />\n";
            }
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

class Description_Walker extends Walker_Nav_Menu
{
    function start_el(&$output, $item, $depth, $args)
    {
        global $wp_query;
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
        
        $class_names = $value = '';
        
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
        $class_names = ' class="'. esc_attr( $class_names ) . '"';
        
        $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';
        
        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
        
        $description  = ! empty( $item->description ) ? '<span>'.esc_attr( $item->description ).'</span>' : '';
        
        if($depth != 0) $description = "";
        
        $item_output = $args->before;
        $item_output .= '<a'. $attributes .'>';
        $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID );
        $item_output .= $description.$args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;
        
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}

?>
