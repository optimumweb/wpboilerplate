<?php

add_action('init', 'wpbp_register_lib');
add_action('wpbp_head', 'wpbp_google_analytics');
add_action('wpbp_head', 'wpbp_google_tag_manager');
add_action('wpbp_head', 'wpbp_custom_css');
add_action('wpbp_head', 'wpbp_favicon');
add_action('wpbp_footer', 'wpbp_add_post_js');
add_action('wpbp_loop_after', 'wpbp_clear');

function wpbp_google_analytics()
{
    $id = wpbp_get_option('google_analytics_id');
    if ( $id !== '' ) : ?>
<!-- Google Analytics -->
<script type="text/javascript">
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', '<?php echo $id; ?>']);
    _gaq.push(['_trackPageview']);
    _gaq.push(['_trackPageLoadTime']);
    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();
</script>
<!-- End Google Analytics -->
<?php endif;
}

function wpbp_google_tag_manager()
{
    $id = esc_attr( wpbp_get_option('google_tag_manager_id') );
    if ( $id !== '' ) : ?>
<!-- Google Tag Manager -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=<?php echo $id; ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','<?php echo $id; ?>');</script>
<!-- End Google Tag Manager -->
    <?php endif;
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
