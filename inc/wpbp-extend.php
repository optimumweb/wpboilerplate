<?php

if ( !function_exists('set_post_ID') ) {
    
    /**
     * set_post_ID
     * Assigns the current post ID to the passed variable.
     */
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

if ( !function_exists('blog_url') ) {

	/**
	 * blog_url
	 * Returns the url of the posts page.
	 */
	function blog_url()
	{
		return get_permalink( get_option('page_for_posts' ) );
	}
	
}

if ( !function_exists('get_author') ) {

	/**
	 * get_author
	 * Returns information about a specified author (current by default).
	 */
	function get_author($field = null, $ID = null)
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

	/**
	 * single_author_title
	 * Returns/displays the author display name as a regular wordpress title.
	 */
	function single_author_title($prefix = '', $display = true)
	{
		$author = get_author('display_name');
		if ( !$author ) return false;
		$single_author_title = $prefix . $author;
		if ( $display ) {
			echo $single_author_title;
		} else {
			return $single_author_title;
		}
	}

}

if ( !function_exists('get_featured_image_url') ) {
	
	function get_featured_image_url($post_ID = null)
	{
		set_post_ID($post_ID);
		if ( get_post_meta($post_ID, 'featured_image_url', true) ) {
			$url = get_post_meta($post_ID, 'featured_image_url', true);
		}
		else {
			$image_src = wp_get_attachment_url( get_post_thumbnail_id( $post_ID ) );
			$url = $image_src[0];
		}
		return $url;
	}
	
}

if ( !function_exists('has_featured_image') ) {

	function has_featured_image($post_ID = null)
	{
        set_post_ID($post_ID);
        $url = get_featured_image_url($post_ID);
        if ( isset($url) && strlen($url) > 0 ) return true;
        return false;
	}

}

if ( !function_exists('get_featured_image') ) {

	function get_featured_image($post_ID = null, $attr = false)
	{
        set_post_ID($post_ID);
        
		if ( has_featured_image($post_ID) ) {
            $url = get_featured_image_url( $post_ID );
    		$image_attr = get_image_size($url);
			if ( isset($image_attr) && is_array($image_attr) ) {
				return ( $attr !== false && isset($$attr) ) ? $$attr : compact('url', 'width', 'height', 'ratio', 'type', 'attr');
			}
		}

		return false;
	}

}

if ( !function_exists('featured_image') ) {

	function featured_image($post_ID = null, $width = 150, $height = 'auto', $quality = 90)
	{
        set_post_ID($post_ID);
        
		$post_image_url = get_featured_image($post_ID, 'url');

		if ( isset($post_image_url) && $post_image_url !== false && strlen($post_image_url) > 0 ) {
			$alt = get_the_title($post_ID);
			$src = resize_image_url($post_image_url, $width, $height, $quality);
            if ( isset($src) && $src !== false && strlen($src) > 0 ) {
                echo '<img class="post-thumbnail" src="' . $src . '" alt="' . $alt . '" />\n';
            }
			return;
		}

		return false;
	}

}

if ( !function_exists('wpbp_get_the_excerpt') ) {

	function wpbp_get_the_excerpt($post_ID = null, $limit = 250)
	{
        set_post_ID($post_ID);
		$post = get_post($post_ID);
		$excerpt = ( isset( $post->post_excerpt ) && strlen( $post->post_excerpt ) > 0 ) ? $post->post_excerpt : substr( strip_tags( $post->post_content ), 0, 250 ) . '...';
		return $excerpt;
	}

}

// the following function requires WPML to be installed and active
if ( !function_exists('wpbp_wpml_lang_sel') && function_exists('icl_get_languages') ) {
    
    function wpbp_wpml_lang_sel()
    {
        $languages = icl_get_languages('skip_missing=1&orderby=code');
    	echo '<ul class="menu lang-sel">';
    	if ( count($languages) > 1 ) {
    		foreach( $languages as $lang ) {
    			echo '<li id="lang-' . $lang['language_code'] . '"' . ( $lang['active'] ? ' class="current-menu-item"' : '' ) . '><a' . ( $lang['active'] ? '' : ' rel="alternate"' ) . ' href="' . $lang['url'] . '" hreflang="' . $lang['language_code'] . '">' . $lang['native_name'] . '</a></li>';
    		}
    	}
    	else {
    		echo '<li id="lang-en"' . ( ( ICL_LANGUAGE_CODE == 'en' ) ? ' class="current-menu-item"' : '' ) . '><a' . ( ( ICL_LANGUAGE_CODE == 'en' ) ? '' : ' rel="alternate"' ) . ' href="' . ( ( ICL_LANGUAGE_CODE == 'en' ) ? '#' : '/en/' ) . '" hreflang="en">English</a></li>';
    		echo '<li id="lang-fr"' . ( ( ICL_LANGUAGE_CODE == 'fr' ) ? ' class="current-menu-item"' : '' ) . '><a' . ( ( ICL_LANGUAGE_CODE == 'fr' ) ? '' : ' rel="alternate"' ) . ' href="' . ( ( ICL_LANGUAGE_CODE == 'fr' ) ? '#' : '/' ) . '" hreflang="fr">Fran&ccedil;ais</a></li>';
    	}
    	echo '</ul>';
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
        
        $item_output = $args->before;
        $item_output .= '<a'. $attributes .'>';
        $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID );
        $item_output .= $description.$args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;
        
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}

class Minimal_Walker extends Walker_Nav_Menu
{
    function start_lvl($output, $depth)
    {
        return "";
    }
    
    function end_lvl($output, $depth)
    {
        return "";
    }
    
    function start_el($output, $item, $depth, $args)
    {
        global $wp_query;
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
        
        $class_names = $value = '';
        
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
        $class_names = ' class="' . esc_attr( $class_names ) . '"';
        
        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) . '"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) . '"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) . '"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) . '"' : '';
        
        $item_output .= '<a' . $class_names . ' ' . $attributes . '>';
        $item_output .= $args->link_before;
        $item_output .= apply_filters( 'the_title', $item->title, $item->ID );
        $item_output .= $args->link_after;
        $item_output .= '</a>';
        
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
        
        return $output;
    }
    
    function end_el($output, $element, $depth)
    {
        return "";
    }
}

