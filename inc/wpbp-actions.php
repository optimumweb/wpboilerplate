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

	wpbp_add_script('modernizr', 'http://firecdn.net/libs/modernizr/2.0.6/modernizr.min.js', array(), '2.0.6');
	wpbp_add_script('lesscss', 'http://firecdn.net/libs/less/1.1.3/less.min.js', array(), '1.1.3');
	wpbp_add_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js', array(), '1.7.1');
	wpbp_add_script('wpbp_jquery', get_template_directory_uri() . '/js/wpbp.jquery.js', array('jquery'));
    
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
	if ( @file_get_contents($src, null, null, 0, 1) !== false ) {
		wp_deregister_script($handle);
		wp_register_script($handle, $src, $deps, $ver, $in_footer);
		wp_enqueue_script($handle, $src, $deps, $ver, $in_footer);
	}
}

function wpbp_get_styles()
{

	global $wpbp_options;

	wpbp_add_style('960gs', 'http://firecdn.net/libs/960gs/960.min.css');
	wpbp_add_style('default', get_template_directory_uri() . '/css/default.css');
    
    if ( $wpbp_options['css_files'] ) {
        foreach ( ( preg_split('/\r\n|\r|\n/', $wpbp_options['css_files']) ) as $css_file ) {
            wpbp_add_style( pathinfo($css_file, PATHINFO_FILENAME), $css_file );
        }
    }

	wpbp_add_style('custom-less', get_stylesheet_directory_uri() . '/css/custom.less', array('default'));
	wpbp_add_style('custom', get_stylesheet_directory_uri() . '/css/custom.css', array('default'));

	wpbp_add_style('wp-meta', get_stylesheet_directory_uri() . '/style.css');

	return;
}

function wpbp_add_style($handle, $src = false, $deps = array(), $ver = false, $media = 'all')
{
	if ( true || @file_get_contents($src, null, null, 0, 1) !== false ) {
		wp_deregister_style($handle);
		wp_register_style($handle, $src, $deps, $ver, $media);
		wp_enqueue_style($handle, $src, $deps, $ver, $media);
	}
}

function enqueue_less_styles($tag, $handle) {
    global $wp_styles;
    $match_pattern = '/\.less$/U';
    if ( preg_match( $match_pattern, $wp_styles->registered[$handle]->src ) ) {
        $handle = $wp_styles->registered[$handle]->handle;
        $media = $wp_styles->registered[$handle]->args;
        $href = $wp_styles->registered[$handle]->src . '?ver=' . $wp_styles->registered[$handle]->ver;
        $rel = isset($wp_styles->registered[$handle]->extra['alt']) && $wp_styles->registered[$handle]->extra['alt'] ? 'alternate stylesheet' : 'stylesheet';
        $title = isset($wp_styles->registered[$handle]->extra['title']) ? "title='" . esc_attr( $wp_styles->registered[$handle]->extra['title'] ) . "'" : '';
        $tag = "<link rel='stylesheet' id='$handle-css' $title href='$href' type='text/less' media='$media' />\n";
    }
    return $tag;
}
add_filter('style_loader_tag', 'enqueue_less_styles', 5, 2);

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

function wpbp_clear()
{
	echo '<div class="clear"></div>' . PHP_EOL;
}

?>