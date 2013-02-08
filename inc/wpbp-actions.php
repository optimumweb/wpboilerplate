<?php

add_action('init', 'wpbp_register_lib');
add_action('wpbp_head', 'wpbp_google_analytics');
add_action('wpbp_head', 'wpbp_custom_css');
add_action('wpbp_head', 'wpbp_favicon');
add_action('wpbp_footer', 'wpbp_add_post_js');
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
}

function wpbp_add_post_js()
{
    set_post_ID($post_ID);
    if ( get_post_meta($post_ID, 'js', true) ) :
?>
    <script type="text/javascript">
        <?php echo get_post_meta($post_ID, 'js', true); ?>
    </script>
<?php
    endif;
}

function wpbp_favicon()
{
    if ( wpbp_get_option('favicon') ) {
        echo '<link rel="icon" type="image/png" href="' . wpbp_get_option('favicon') . '">';
    }
}

function wpbp_clear()
{
	echo '<div class="clear"></div>';
}
