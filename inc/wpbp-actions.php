<?php

add_action('init', 'wpbp_register_libs');
add_action('wpbp_head', 'wpbp_og_tags');
add_action('wpbp_head', 'wpbp_google_analytics');
add_action('wpbp_head', 'wpbp_custom_css');
add_action('wpbp_head', 'wpbp_favicon');
add_action('wpbp_footer', 'wpbp_add_post_js');
add_action('wpbp_footer', 'wpbp_count_view');
add_action('wpbp_loop_after', 'wpbp_clear');

function wpbp_google_analytics()
{
    $wpbp_google_analytics_id = wpbp_get_option('google_analytics_id');
    $wpbp_get_google_analytics_id = esc_attr( wpbp_get_option('google_analytics_id') );
    if ( $wpbp_google_analytics_id !== '' ) :
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
    endif;
    return;
}

function wpbp_og_tags()
{

    if ( is_single() ) {
    	set_post_ID( $post_ID );
    }

    $og = array(
        'title'       => trim( wp_title('', false) ),
        'url'         => get_current_url(),
        'image'       => is_single() ? get_featured_image_url( $post_ID ) : null,
        'site_name'   => get_bloginfo('name'),
        'description' => is_single() ? strip_tags( wpbp_get_the_excerpt( $post_ID ) ) : null
    );

    foreach ( $og as $key => $val ) {
        if ( isset($val) && strlen($val) > 0 ) {
            echo '<meta property="og:' . $key . '" content="' . $val . '" />' . PHP_EOL;
        }
    }

    return;
}

function wpbp_custom_css()
{
    if ( wpbp_get_option('custom_css') ) :
?>
<style type="text/css">
<?php wpbp_option('custom_css') . "\n"; ?>
</style>
<?php
    endif;
	return;
}

function wpbp_favicon()
{
	if ( wpbp_get_option('favicon') ) {
		echo '<link rel="icon" type="image/png" href="' . wpbp_get_option('favicon') . '">';
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
