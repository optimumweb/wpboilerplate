<?php

add_action('wpbp_head', 'wpbp_og_tags');
add_action('wpbp_head', 'wpbp_google_analytics');
add_action('wpbp_head', 'wpbp_custom_css');
add_action('wp_enqueue_scripts', 'wpbp_get_styles');
add_action('wp_enqueue_scripts', 'wpbp_get_scripts');
add_action('wpbp_footer', 'wpbp_count_view');
add_action('wpbp_loop_after', 'wpbp_clear');

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
        'title'       => trim(wp_title('', false)),
        'url'         => wpbp_get_current_url(),
        'image'       => is_single() ? $current_post_image['url'] : null,
        'site_name'   => get_bloginfo('name'),
        'description' => is_single() ? strip_tags(wpbp_get_the_excerpt($wp_query->post->ID)) : null
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

    if ( $wpbp_options['js_plugins']['modernizr'] ) {
		wpbp_add_script('modernizr', 'http://firecdn.net/libs/modernizr/2.0.6/modernizr.min.js', array(), '2.0.6');
    }

    if ( $wpbp_options['js_plugins']['lesscss'] ) {
    	wpbp_add_script('lesscss', 'http://firecdn.net/libs/less/1.1.3/less.min.js', array(), '1.1.3');
    }

    if ( $wpbp_options['js_plugins']['jquery'] ) {

		wpbp_add_script('jquery', 'http://firecdn.net/libs/jquery/1.6.3/jquery.min.js', array(), '1.6.3');

        if ( $wpbp_options['js_plugins']['formalize'] ) {
        	wpbp_add_script('formalize', 'http://firecdn.net/libs/formalize/js/jquery.formalize.js', array('jquery'));
        }
        
        wpbp_add_script('wpbp_jquery', get_template_directory_uri() . '/js/wpbp.jquery.js', array('jquery'));
    }
    
    wpbp_add_script('wpbp', get_template_directory_uri() . '/js/wpbp.js');
    
    if ( $wpbp_options['js_files'] ) {
        foreach ( ( preg_split('/\r\n|\r|\n/', $wpbp_options['js_files']) ) as $js_file ) {
        	wpbp_add_script( pathinfo($js_file, PATHINFO_FILENAME), $js_file );
        }
    }

	wpbp_add_script('theme_js', get_stylesheet_directory_uri() . '/js/scripts.js');

	return;
}

function wpbp_add_script($handle, $src = false, $deps = array(), $ver = false, $in_footer = false)
{
	wp_deregister_script($handle);
	wp_register_script($handle, $src, $deps, $ver, $in_footer);
	wp_enqueue_script($handle, $src, $deps, $ver, $in_footer);
}

function script_tag($args)
{
	extract( array_merge( array(
		'src'	=> ( is_string($args) ? $args : '' ),
		'type'	=> 'text/javascript'
	), ( is_array($args) ? $args : array() ) ) );
	return "<script type=\"" . $type . "\" src=\"" . $src . "\"></script>\n";
}

function wpbp_get_styles()
{

	global $wpbp_options;

	wpbp_add_style('960.gs', 'http://firecdn.net/libs/960gs/960.min.css');

	if ( $wpbp_options['js_plugins']['formalize'] ) {
		wpbp_add_style('formalize', 'http://firecdn.net/libs/formalize/css/formalize.css');
	}

	wpbp_add_style('default', get_template_directory_uri() . '/css/default.css');

	if ( $wpbp_options['js_plugins']['lesscss'] ) {
		wpbp_add_style('custom', get_stylesheet_directory_uri() . '/css/custom.less', array('default'));
	} else {
		wpbp_add_style('custom', get_stylesheet_directory_uri() . '/css/custom.css', array('default'));
	}
    
    if ( $wpbp_options['css_files'] ) {
        foreach ( ( preg_split('/\r\n|\r|\n/', $wpbp_options['css_files']) ) as $css_file ) {
            wpbp_add_style( pathinfo($css_file, PATHINFO_FILENAME), $css_file );
        }
    }

	wpbp_add_style( get_stylesheet_directory_uri() . '/style.css' );

	return;
}

function wpbp_add_style($handle, $src = false, $deps = array(), $ver = false, $media = 'all')
{
	wp_deregister_style($handle);
	wp_register_style($handle, $src, $deps, $ver, $media);
	wp_enqueue_style($handle, $src, $deps, $ver, $media);
}

apply_filters( 'style_loader_tag', "<link rel='stylesheet' href='$href' type='text/css' media='$media' data-test='1' />\n", $handle );

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

function wpbp_clear()
{
	echo '<div class="clear"></div>' . PHP_EOL;
}

?>