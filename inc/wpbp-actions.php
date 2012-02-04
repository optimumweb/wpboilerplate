<?php

add_action('init', 'wpbp_get_styles');
add_action('init', 'wpbp_get_scripts');

add_action('wpbp_head', 'wpbp_og_tags');
add_action('wpbp_head', 'wpbp_google_analytics');
add_action('wpbp_head', 'wpbp_custom_css');

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

	// Available Javascript Librairies
	// You will need to enqueue the ones you want in your child theme
	
	wpbp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js', array(), '1.7.1');
	wpbp_register_script('jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js', array(), '1.8.16');
	wpbp_register_script('scrollTo', 'http://firecdn.net/libs/scrollTo/jquery.scrollTo.min.js', array('jquery'), '1.4.2');
	wpbp_register_script('ext-core', 'https://ajax.googleapis.com/ajax/libs/ext-core/3.1.0/ext-core.js', array(), '3.1.0');
	wpbp_register_script('dojo', 'https://ajax.googleapis.com/ajax/libs/dojo/1.6.1/dojo/dojo.xd.js', array(), '1.6.1');
	wpbp_register_script('mootools', 'https://ajax.googleapis.com/ajax/libs/mootools/1.4.1/mootools-yui-compressed.js', array(), '1.4.1');
	wpbp_register_script('modernizr', 'http://firecdn.net/libs/modernizr/2.0.6/modernizr.min.js', array(), '2.0.6');
	wpbp_register_script('lesscss', 'http://firecdn.net/libs/less/less.min.js', array(), '1.2.1');
	wpbp_register_script('sizzle', 'http://firecdn.net/libs/sizzle/sizzle.min.js', array(), '1.5.1');
	wpbp_register_script('highcharts', 'http://firecdn.net/libs/highcharts/highcharts.min.js', array(), '2.1.9');
	wpbp_register_script('cycle', 'http://firecdn.net/libs/cycle/jquery.cycle.min.js', array('jquery'), '2.9998');
	wpbp_register_script('wpbp', get_template_directory_uri() . '/js/wpbp.js', array('jquery'), '2.1.0');
    
    if ( $wpbp_options['js_files'] ) {
        foreach ( ( preg_split('/\r\n|\r|\n/', $wpbp_options['js_files']) ) as $js_file ) {
        	wpbp_add_script( pathinfo($js_file, PATHINFO_FILENAME), $js_file );
        }
    }

	return;
}

function wpbp_register_script($handle, $src = false, $deps = array(), $ver = false, $in_footer = false)
{
	wp_deregister_script($handle);
	wp_register_script($handle, $src, $deps, $ver, $in_footer);
}

function wpbp_add_script($handle, $src = false, $deps = array(), $ver = false, $in_footer = false)
{
	wpbp_register_script($handle, $src, $deps, $ver, $in_footer);
	wp_enqueue_script($handle);
}

function wpbp_enqueue_scripts( $scripts = array() )
{
	if ( is_array( $scripts ) ) {
		foreach ( $scripts as $handle ) {
			wp_enqueue_script($handle);
		}
	}
}

function wpbp_get_styles()
{

	global $wpbp_options;

	wpbp_register_style('960gs', 'http://firecdn.net/libs/960gs/960.min.css');
	wpbp_register_style('default', get_template_directory_uri() . '/css/default.css');
	wpbp_register_style('wp-meta', get_stylesheet_directory_uri() . '/style.css');
	
	if ( $wpbp_options['css_files'] ) {
        foreach ( ( preg_split('/\r\n|\r|\n/', $wpbp_options['css_files']) ) as $css_file ) {
            wpbp_add_style( pathinfo($css_file, PATHINFO_FILENAME), $css_file );
        }
    }

	return;
}

function wpbp_register_style($handle, $src = false, $deps = array(), $ver = false, $media = 'all')
{
	wp_deregister_style($handle);
	wp_register_style($handle, $src, $deps, $ver, $media);
}

function wpbp_add_style($handle, $src = false, $deps = array(), $ver = false, $media = 'all')
{
	wpbp_register_style($handle, $src, $deps, $ver, $media);
	wp_enqueue_style($handle, $src, $deps, $ver, $media);
}

function wpbp_enqueue_styles( $styles = array() )
{
	if ( is_array( $styles ) ) {
		foreach ( $styles as $handle ) {
			wp_enqueue_style($handle);
		}
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
	global $post;
	if ( is_single() && isset( $post->ID ) ) {
		$post_ID = $post->ID;
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