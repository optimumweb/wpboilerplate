<?php

add_action('init', 'wpbp_get_styles');
add_action('init', 'wpbp_get_scripts');
add_action('wpbp_head', 'wpbp_og_tags');
add_action('wpbp_head', 'wpbp_google_analytics');
add_action('wpbp_head', 'wpbp_custom_css');
add_action('wpbp_head', 'wpbp_favicon');
add_action('wpbp_footer', 'wpbp_add_post_js');
add_action('wpbp_footer', 'wpbp_count_view');
add_action('wpbp_loop_after', 'wpbp_clear');

function wpbp_google_analytics()
{
    global $wpbp_options;
    $wpbp_google_analytics_id = $wpbp_options['google_analytics_id'];
    $wpbp_get_google_analytics_id = esc_attr($wpbp_options['google_analytics_id']);
    if ( $wpbp_google_analytics_id !== '' ) {
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
        'description' => is_single() ? strip_tags( wpbp_get_the_excerpt( $wp_query->post->ID ) ) : null
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
	if ( is_admin() ) return;

    global $wpbp_options;

	// Available Javascript Librairies
	// You will need to enqueue the ones you want in your child theme
	
	// jQuery
	wpbp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js', array(), '1.7.1');
	
	// jQuery UI
	wpbp_register_script('jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js', array(), '1.8.16');
	
	// jQuery Plugins
	wpbp_register_script('scrollTo', 'http://firecdn.net/libs/scrollTo/jquery.scrollTo.min.js', array('jquery'), '1.4.2');
	wpbp_register_script('cycle', 'http://firecdn.net/libs/cycle/jquery.cycle.min.js', array('jquery'), '2.9998');
	wpbp_register_script('powerslide', 'http://firecdn.net/libs/powerslide/js/powerslide.min.js', array('jquery'), '1.1');
	wpbp_register_script('lightbox', 'http://firecdn.net/libs/lightbox/js/lightbox.min.js', array('jquery'), '2.51');
	
	// Twitter Bootstrap
	wpbp_register_script('bootstrap', 'http://firecdn.net/libs/bootstrap/js/bootstrap.min.js', array('jquery'), '2.0.2');
	
	// Ext JS
	wpbp_register_script('ext-core', 'https://ajax.googleapis.com/ajax/libs/ext-core/3.1.0/ext-core.js', array(), '3.1.0');
	
	// Dojo
	wpbp_register_script('dojo', 'https://ajax.googleapis.com/ajax/libs/dojo/1.6.1/dojo/dojo.xd.js', array(), '1.6.1');
	
	// MooTools
	wpbp_register_script('mootools', 'https://ajax.googleapis.com/ajax/libs/mootools/1.4.1/mootools-yui-compressed.js', array(), '1.4.1');
	
	// Modernizr
	wpbp_register_script('modernizr', 'http://firecdn.net/libs/modernizr/modernizr.js', array(), '2.0.6');
	
	// LessCSS
	wpbp_register_script('lesscss', 'http://firecdn.net/libs/less/less.min.js', array(), '1.2.1');
	
	// Sizzle
	wpbp_register_script('sizzle', 'http://firecdn.net/libs/sizzle/sizzle.min.js', array(), '1.5.1');
	
	// Highcharts
	wpbp_register_script('highcharts', 'http://firecdn.net/libs/highcharts/highcharts.min.js', array(), '2.1.9');
	
	// WPBP
	wpbp_register_script('wpbp', 'http://firecdn.net/libs/wpbp/js/wpbp.min.js', array('jquery'), '2.1.0');
    
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

function wpbp_add_script($handle, $src = false, $deps = array(), $ver = false, $in_footer = true)
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
	if ( is_admin() ) return;

	global $wpbp_options;

	// 960gs
	wpbp_register_style('960gs', 'http://firecdn.net/libs/960gs/960.min.css');
	
	// jQuery UI
	wpbp_register_style('jquery-ui', 'http://firecdn.net/libs/jquery-ui/css/base/jquery.ui.all.css');
	wpbp_register_style('jquery-ui-smoothness', 'http://firecdn.net/libs/jquery-ui/css/smoothness/jquery-ui.css');
	wpbp_register_style('jquery-ui-lightness', 'http://firecdn.net/libs/jquery-ui/css/ui-lightness/jquery-ui.css');
	wpbp_register_style('jquery-ui-darkness', 'http://firecdn.net/libs/jquery-ui/css/ui-darkness/jquery-ui.css');
	wpbp_register_style('jquery-ui-redmond', 'http://firecdn.net/libs/jquery-ui/css/redmond/jquery-ui.css');
	wpbp_register_style('jquery-ui-blitzer', 'http://firecdn.net/libs/jquery-ui/css/blitzer/jquery-ui.css');
	
	// Twitter Bootstrap
	wpbp_register_style('bootstrap', 'http://firecdn.net/libs/bootstrap/css/bootstrap.min.css');
	wpbp_register_style('bootstrap-responsive', 'http://firecdn.net/libs/bootstrap/css/bootstrap-responsive.min.css');
	
	// Lightbox
	wpbp_register_style('lightbox', 'http://firecdn.net/libs/lightbox/css/lightbox.min.css');
	
	// PowerSlide
	wpbp_register_style('powerslide', 'http://firecdn.net/libs/powerslide/css/powerslide.css');
	
	// WPBP Default
	wpbp_register_style('default', 'http://firecdn.net/libs/wpbp/css/default.css');
	
	// WP Meta
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

function wpbp_custom_css()
{
	global $wpbp_options;
    if ( $wpbp_options['custom_css'] ) :
?>
<style type="text/css">
<?php echo $wpbp_options['custom_css'] . "\n"; ?>
</style>
<?php
    endif;
	return;
}

function wpbp_favicon()
{
	global $wpbp_options;
	if ( $wpbp_options['favicon'] ) {
		echo '<link rel="icon" type="image/png" href="' . $wpbp_options['favicon'] . '">';
	}
}

function wpbp_add_post_js()
{
	set_post_ID($post_ID);
	if ( get_post_meta($post_ID, 'js', true) ) : ?>
		<script type="text/javascript">
			<?php echo get_post_meta($post_ID, 'js', true); ?>
		</script>
	<?php endif;
}

function wpbp_count_view()
{
	global $post;
	if ( is_single() && isset( $post->ID ) ) {
		$post_ID = $post->ID;
		$post_views = get_post_meta($post_ID, 'wpbp_post_views', true);
		$post_views = isset($post_views) ? $post_views + 1 : 1;
		update_post_meta($post_ID, 'wpbp_post_views', $post_views);
	}
}

function wpbp_clear()
{
	echo '<div class="clear"></div>' . PHP_EOL;
}
