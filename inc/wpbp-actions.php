<?php

add_action('wpbp_head', 'wpbp_og_tags');
add_action('wpbp_head', 'wpbp_google_analytics');
add_action('wpbp_head', 'wpbp_custom_css');
add_action('wpbp_stylesheets', 'wpbp_get_stylesheets');
add_action('wpbp_scripts', 'wpbp_get_scripts');
add_action('wpbp_breadcrumb', 'wpbp_get_breadcrumb');
add_action('wpbp_footer', 'wpbp_count_view');

function wpbp_google_analytics()
{
    global $wpbp_options;
    $wpbp_google_analytics_id = $wpbp_options['google_analytics_id'];
    $wpbp_get_google_analytics_id = esc_attr($wpbp_options['google_analytics_id']);
    if ($wpbp_google_analytics_id !== '') {
?>
<script type="text/javascript">
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', '<?php echo $wpbp_google_analytics_id; ?>']);
    _gaq.push(['_trackPageview']);
    _gaq.push(['_trackPageLoadTime']);
    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();
</script>
<?php
    }
    return;
}

function wpbp_og_tags()
{
    global $wp_query;

    if ( is_single() ) {
        $current_post = get_post( $wp_query->post->ID );
        $current_post_image = get_featured_image( $wp_query->post->ID );
    }

    $og = array(
        'title'       => htmlentities(trim(wp_title('', false))),
        'url'         => wpbp_get_current_url(),
        'image'       => is_single() ? $current_post_image['url'] : null,
        'site_name'   => htmlentities(get_bloginfo('name')),
        'description' => is_single() ? htmlentities(strip_tags(wpbp_get_the_excerpt($wp_query->post->ID))) : null
    );

    foreach ( $og as $key => $val ) {
        if ( isset($val) && strlen($val) > 0 ) {
            echo "<meta property=\"og:" . $key . "\" content=\"" . $val . "\" />\n";
        }
    }

    return;
}

function wpbp_get_scripts()
{

    global $wpbp_options;

    $scripts = "";

    if ( $wpbp_options['js_plugins']['modernizr'] ) {
            $scripts .= script_tag('http://firecdn.net/libs/modernizr/2.0.6/modernizr.min.js');
    }

    if ( $wpbp_options['js_plugins']['lesscss'] ) {
        $scripts .= script_tag('http://firecdn.net/libs/less/1.1.3/less.min.js');
    }

    if ( $wpbp_options['js_plugins']['jquery'] ) {

        $scripts .= script_tag('http://firecdn.net/libs/jquery/1.6.3/jquery.min.js');

        if ( $wpbp_options['js_plugins']['formalize'] ) {
            $scripts .= script_tag('http://firecdn.net/libs/formalize/js/jquery.formalize.js');
        }

        if ( $wpbp_options['js_plugins']['ajax-mail'] ) {
                $scripts .= script_tag( get_template_directory_uri() . '/plugins/ajax-mail/jquery.ajax-mail.js' );
        }
    }
    
    if ( $wpbp_options['js_files'] ) {
        foreach ( ( explode('\n', $wpbp_options['js_files']) ) as $js_file ) {
            $scripts .= script_tag( $js_file );
        }
    }

	$scripts .= script_tag( get_stylesheet_directory_uri() . '/js/scripts.js' );

	echo $scripts;

	return;
}

function script_tag($args)
{
	extract( array_merge( array(
		'src'	=> ( is_string($args) ? $args : '' ),
		'type'	=> 'text/javascript'
	), ( is_array($args) ? $args : array() ) ) );
	return "<script type=\"" . $type . "\" src=\"" . $src . "\"></script>\n";
}

function wpbp_get_stylesheets()
{

	global $wpbp_options;

	$styles = "";

	$styles .= stylesheet_link_tag('http://firecdn.net/libs/960gs/960.min.css');

	if ( $wpbp_options['js_plugins']['formalize'] ) {
		$styles .= stylesheet_link_tag('http://firecdn.net/libs/formalize/css/formalize.css');
	}

	$styles .= stylesheet_link_tag( get_template_directory_uri() . "/css/default.css" );

	if ( $wpbp_options['js_plugins']['lesscss'] ) {
		$styles .= stylesheet_link_tag( array( 'href' => get_stylesheet_directory_uri() . "/css/custom.less", 'rel' => 'stylesheet/less' ) );
	} else {
		$styles .= stylesheet_link_tag( get_stylesheet_directory_uri() . "/css/custom.css" );
	}
    
    if ( $wpbp_options['css_files'] ) {
        foreach ( ( explode('\n', $wpbp_options['css_files']) ) as $css_file ) {
            $styles .= stylesheet_link_tag( $css_file );
        }
    }

	$styles .= stylesheet_link_tag( get_stylesheet_directory_uri() . "/style.css" );

	echo $styles;

	return;
}

function stylesheet_link_tag($args)
{
	extract( array_merge( array(
		'href'	=> ( is_string($args) ? $args : '' ),
		'rel'	=> 'stylesheet',
		'media'	=> 'all',
		'type'	=> 'text/css'
	), ( is_array($args) ? $args : array() ) ) );

	return "<link rel=\"" . $rel . "\" href=\"" . $href . "\" type=\"" . $type . "\" media=\"" . $media . "\" />\n";
}

function wpbp_custom_css()
{
	global $wpbp_options;
    if ( $wpbp_options['custom_css'] ) {
?>
<style type="text/css">
<?php echo $wpbp_options['custom_css'] . "\n"; ?>
</style>
<?php
    }
	return;
}

function wpbp_get_breadcrumb()
{
	wpbp_custom_breadcrumb();
}

function wpbp_count_view()
{
	global $wp_query;
	if ( is_single() && isset( $wp_query->post->ID ) ) {
		$post_ID = $wp_query->post->ID;
		$post_views = get_post_meta($post_ID, 'wpbp_post_views', true);
		$post_views = ( isset($post_views) ) ? $post_views + 1 : 1;
		update_post_meta($post_ID, 'wpbp_post_views', $post_views);
	}
}

add_action('admin_notices', 'delayed_admin_notices');

function delayed_admin_notices()
{
    $delayed_admin_notices = get_delayed_admin_notices();
    foreach ( $delayed_admin_notices as $notice ) {
        echo "<div class=\"" . $notice['type'] . "\"><p>" . $notice['message'] . "</p></div>";
    }
    reset_delayed_admin_notices();
}

function reset_delayed_admin_notices()
{
     return delete_option('delayed_admin_notices');
}

function get_delayed_admin_notices()
{
    return unserialize( get_option('delayed_admin_notices', serialize(array())) );
}

function add_delayed_admin_notice($message, $type = 'updated')
{
    $delayed_admin_notices = get_delayed_admin_notices();
    $delayed_admin_notices = ( isset($delayed_admin_notices) && is_array($delayed_admin_notices) ) ? $delayed_admin_notices : array();
    $delayed_admin_notices[] = array('message' => $message, 'type' => $type);
    $delayed_admin_notices = serialize($delayed_admin_notices);
    update_option('delayed_admin_notices', $delayed_admin_notices);
}

add_action('save_post', 'wpbp_validate_featured_image_url');

function wpbp_validate_featured_image_url($post_ID)
{
    if ( has_featured_image($post_ID) ) {
        $featured_image = get_featured_image($post_ID);
        $featured_image_url = $featured_image['url'];
        if ( wpbp_is_valid_image($featured_image_url) ) {
            $upload_dir = wp_upload_dir();
            if ( strpos($featured_image_url, $upload_dir['baseurl']) === false ) {
                $local_featured_image = download_image_from_url($featured_image_url);
                add_delayed_admin_notice(var_export($local_featured_image, true), 'updated');
                if ( isset($local_featured_image) && is_array($local_featured_image) && isset($local_featured_image['url']) ) {
                    update_post_meta($post_ID, 'featured_image_url', $local_featured_image['url'], $featured_image_url);
                    add_delayed_admin_notice(__('The featured image was downloaded locally!', 'wpbp'), 'updated');
                }
                else {
                    add_delayed_admin_notice(__('An error occured when downloading the featured image locally!', 'wpbp'), 'error');
                }
            }
        }
        else {
            add_delayed_admin_notice(__('Please make sure your featured image url is valid!', 'wpbp'), 'error');
            update_post_meta($post_ID, 'featured_image_url', '', $featured_image_url);
        }
    }
}

?>