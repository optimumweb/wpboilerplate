<?php

if ( ! function_exists( 'get_post_id' ) ) {

    function get_post_id() {
        return get_queried_object_id();
    }

}

if ( ! function_exists( 'set_post_ID' ) ) {

    /**
    * set_post_ID
    * Assigns the current post ID to the passed variable.
    */
    function set_post_ID( &$post_ID ) {
        if ( ! isset( $post_ID) || ! is_numeric( $post_ID ) ) {
            $post_ID = get_post_id();
        }
    }

}

if ( ! function_exists( 'get_ID_by_slug' ) ) {

    /**
    * get_ID_by_slug
    * Returns the ID of a given post slug
    */
    function get_ID_by_slug( $slug ) {
        global $wpdb;
        return $wpdb->get_var( sprintf( "SELECT ID FROM %s WHERE post_name = '%s' LIMIT 1", $wpdb->posts, $slug ) );
    }

}

if ( ! function_exists( 'blog_url' ) ) {

    /**
    * blog_url
    * Returns the url of the posts page.
    */
    function blog_url() {
        return get_permalink( get_option( 'page_for_posts' ) );
    }

}

if ( ! function_exists( 'get_author' ) ) {

	/**
	 * get_author
	 * Returns information about a specified author (current by default).
	 */
	function get_author( $field = null, $id = null ) {
		if ( isset( $id ) && is_int( $id ) ) {
			$author = get_user_by( 'id', $id );
		} elseif ( is_author() ) {
			$author = get_user_by( 'slug', get_query_var( 'author_name' ) );
		}

        if ( isset( $author ) ) {
            if ( isset( $field ) ) {
                return property_exists( $author, $field ) ? $author->$field : null;
            } else {
                return $author;
            }
        }
	}

}

if ( ! function_exists( 'single_author_title' ) ) {

	/**
	 * single_author_title
	 * Returns/displays the author display name as a regular wordpress title.
	 */
	function single_author_title( $prefix = '', $display = true ) {
		if ( ! $author = get_author( 'display_name' ) ) {
            return false;
        }

		$single_author_title = $prefix . $author;

		if ( $display ) {
			echo $single_author_title;
		} else {
			return $single_author_title;
		}
	}

}

if ( ! function_exists( 'get_featured_image_url' ) ) {

	function get_featured_image_url( $post_ID = null ) {
		set_post_ID( $post_ID );

		if ( $url = get_post_meta( $post_ID, 'featured_image_url', true ) ) {
			return get_full_url( $url );
		} elseif ( $post_thumbnail_id = get_post_thumbnail_id( $post_ID ) ) {
            if ( $url = wp_get_attachment_url( $post_thumbnail_id ) ) {
                return get_full_url( $url );
            }
        }
	}

}

if ( ! function_exists( 'has_featured_image' ) ) {

	function has_featured_image( $post_ID = null ) {
        set_post_ID( $post_ID );
        $url = get_featured_image_url( $post_ID );
        return isset( $url ) && strlen( $url ) > 0;
	}

}

if ( ! function_exists( 'get_featured_image' ) ) {

	function get_featured_image( $post_ID = null, $attr = false ) {
        set_post_ID( $post_ID );

		if ( $url = get_featured_image_url( $post_ID ) ) {
    		$image_attr = get_image_size( $url );
			if ( isset( $image_attr ) && is_array( $image_attr ) ) {
				return ( $attr !== false && isset($$attr) ) ? $$attr : compact( 'url', 'width', 'height', 'ratio', 'type', 'attr' );
			}
		}

		return false;
	}

}

if ( ! function_exists( 'featured_image' ) ) {

	function featured_image( $post_ID = null, $width = 150, $height = 'auto', $quality = 90 ) {
        set_post_ID( $post_ID );

		if ( $post_image_url = get_featured_image( $post_ID, 'url' ) ) {
			$alt = get_the_title( $post_ID );
			$src = resize_image_url( $post_image_url, $width, $height, $quality );
            if ( isset( $src ) && $src !== false && strlen($src) > 0 ) {
                echo '<img class="post-thumbnail" src="' . $src . '" alt="' . $alt . '" />\n';
            }
			return;
		}

		return false;
	}

}

if ( ! function_exists( 'is_login_page' ) ) {

    function is_login_page() {
        return isset( $GLOBALS['pagenow'] ) && in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) );
    }

}
