<?php

add_action('init', 'wpbp_register_lib');

add_action('wpbp_head', 'wpbp_insert_optimizely');
add_action('wpbp_head', 'wpbp_insert_google_analytics');
add_action('wpbp_head', 'wpbp_insert_google_tag_manager');
add_action('wpbp_head', 'wpbp_insert_custom_css');
add_action('wpbp_head', 'wpbp_insert_favicon');

add_action('wpbp_footer', 'wpbp_insert_custom_js');
add_action('wpbp_footer', 'wpbp_insert_post_js');

add_action('wpbp_loop_after', 'wpbp_clear');

function wpbp_insert_optimizely()
{
    $project_id = wpbp_get_option('optimizely_project_id');
    if ( $project_id ) {
        echo '<script src="//cdn.optimizely.com/js/' . $project_id . '.js"></script>' . PHP_EOL;
    }
}

function wpbp_insert_google_analytics()
{
    $ga_id = wpbp_get_option('google_analytics_id');
    if ( $ga_id ) : ?>
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', '<?php echo $ga_id; ?>', 'auto');
    ga('send', 'pageview');

</script>
<?php endif;
}

function wpbp_insert_google_tag_manager()
{
    $gtm_id = esc_attr( wpbp_get_option('google_tag_manager_id') );
    if ( $gtm_id !== '' ) : ?>
<!-- Google Tag Manager -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=<?php echo $gtm_id; ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','<?php echo $gtm_id; ?>');</script>
<!-- End Google Tag Manager -->
    <?php endif;
}

function wpbp_insert_custom_css()
{
    $custom_css = wpbp_get_option('custom_css');

    if ( $custom_css ) : ?>
<style type="text/css">
<?php echo $custom_css; ?>
</style>
<?php endif;
}

function wpbp_insert_custom_js()
{
    $custom_js = wpbp_get_option('custom_js');

    if ( $custom_js ) : ?>
<script type="text/javascript">
<?php echo $custom_js; ?>
</script>
<?php endif;
}

function wpbp_insert_post_js()
{
    set_post_ID($post_ID);
    $post_js = get_post_meta($post_ID, 'js', true);

    if ( $post_js ) : ?>
<script type="text/javascript">
<?php echo $post_js; ?>
</script>
<?php endif;
}

function wpbp_insert_favicon()
{
    $favicon = wpbp_get_option('favicon');

    if ( $favicon) {
        echo '<link rel="icon" type="image/png" href="' . $favicon . '">';
    }
}

