<?php

if ( !function_exists('get_post_id') ) {

    function get_post_id()
    {
        return get_queried_object_id();
    }

}

if ( !function_exists('set_post_ID') ) {

    /**
    * set_post_ID
    * Assigns the current post ID to the passed variable.
    */
    function set_post_ID(&$post_ID)
    {
        if ( !isset($post_ID) || !is_numeric($post_ID) ) {
            $post_ID = get_post_id();
        }
    }

}

if ( !function_exists('get_ID_by_slug') ) {

    /**
    * get_ID_by_slug
    * Returns the ID of a given post slug
    */
    function get_ID_by_slug($slug)
    {
        global $wpdb;
        return $wpdb->get_var(sprintf("SELECT ID FROM %s WHERE post_name = '%s' LIMIT 1", $wpdb->posts, $slug));
    }

}

if ( !function_exists('blog_url') ) {

    /**
    * blog_url
    * Returns the url of the posts page.
    */
    function blog_url()
    {
        return get_permalink(get_option('page_for_posts'));
    }

}

if ( !function_exists('get_author') ) {

	/**
	 * get_author
	 * Returns information about a specified author (current by default).
	 */
	function get_author($field = null, $id = null)
	{
		if ( isset($id) && is_int($id) ) {
			$author = get_user_by('id', $id);
		} elseif ( is_author() ) {
			$author = get_user_by('slug', get_query_var('author_name'));
		}
        if ( isset($author) ) {
            if ( isset($field) ) {
                return property_exists($author, $field) ? $author->$field : null;
            } else {
                return $author;
            }
        }
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
		} else {
			if ( $post_thumbnail_id = get_post_thumbnail_id($post_ID) ) {
				$url = wp_get_attachment_url($post_thumbnail_id);
			}
		}
        return !empty($url) ? get_full_url($url) : null;
	}

}

if ( !function_exists('has_featured_image') ) {

	function has_featured_image($post_ID = null)
	{
        set_post_ID($post_ID);
        $url = get_featured_image_url($post_ID);
        return isset($url) && strlen($url) > 0;
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
		$excerpt = ( isset($post->post_excerpt) && strlen($post->post_excerpt) > 0 ) ? $post->post_excerpt : substr( strip_tags( $post->post_content ), 0, 250 ) . '...';
		return $excerpt;
	}

}

if ( !function_exists('wpbp_get_posts_by_tag') ) {

    function wpbp_get_posts_by_tag($tag, $args = [], $field = 'name')
    {
        if ( $term = get_term_by($field, $tag, 'post_tag') ) {
            $args = array_merge((array) $args, [ 'tag__in' => $term->term_id ]);
            return get_posts($args);
        }
    }

}

if ( !function_exists('wpbp_error_log') ) {

    function wpbp_error_log($message, $notify_admin = false, $echo_in_footer = false)
    {
        $wp_debug_file_path = WP_CONTENT_DIR . '/debug.log';

        if ( is_writeable($wp_debug_file_path) ) {
            if ( $handle = @fopen($wp_debug_file_path, 'a') ) {
                $result = @fwrite($handle, $message . PHP_EOL);
                @fclose( $handle );
            }
        }

        if ( $notify_admin ) {

            $admin_email = get_option('admin_email');

            $mail = new Mail();
            $mail->set_option('content_type', "text/plain; charset=utf-8");
            $mail->set_option('from', $admin_email);
            $mail->set_option('to', $admin_email);
            $mail->set_option('subject', "WPBP Error Notification");
            $mail->set_body( $message );
            $mail->send();

            if ( $mail->get_response() != 200 ) {
                wpbp_error_log("Can't send error notification to admin (" . $admin_email . ")");
            }
        }

        if ( $echo_in_footer ) {
            // to do
        }

        return $result;
    }

}

if ( !function_exists('is_login_page') ) {

    function is_login_page()
    {
        return isset($GLOBALS['pagenow']) && in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
    }

}

// the following function requires WPML to be installed and active
if ( !function_exists('wpbp_wpml_lang_sel') && function_exists('icl_get_languages') ) {

    function wpbp_wpml_lang_sel()
    {
        $languages = icl_get_languages('skip_missing=1&orderby=code');
        echo '<ul class="menu lang-sel">';
        if ( is_array($languages) && count($languages) > 1 ) {
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

if ( !function_exists('wpbp_container_class') ) {

    function wpbp_container_class()
    {
        echo "container " . ( wpbp_get_option('fluid') == 'yes' ? "fluid" : "" );
    }

}
