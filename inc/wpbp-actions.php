<?php

add_action('init', 'wpbp_register_lib');

add_action('wpbp_head', 'wpbp_insert_optimizely');
add_action('wpbp_head', 'wpbp_insert_google_analytics');
add_action('wpbp_head', 'wpbp_insert_google_tag_manager');
add_action('wpbp_head', 'wpbp_insert_custom_css');
add_action('wpbp_head', 'wpbp_insert_favicon');

add_action('wpbp_footer', 'wpbp_insert_google_remarketing_tag');
add_action('wpbp_footer', 'wpbp_insert_custom_js');
add_action('wpbp_footer', 'wpbp_insert_post_js');

add_action('wpbp_loop_after', 'wpbp_clear');

function wpbp_insert_optimizely()
{
    $project_id = wpbp_get_option('optimizely_project_id');

    $file = TEMPLATE_DIRECTORY . '/inc/tags/optimizely.php';

    if ( !empty($project_id) && file_exists($file) ) {
        include($file);
    }
}

function wpbp_insert_google_analytics()
{
    $ga_id = wpbp_get_option('google_analytics_id');

    $file = TEMPLATE_DIRECTORY . '/inc/tags/google_analytics.php';

    if ( !empty($ga_id) && file_exists($file) ) {
        include($file);
    }
}

function wpbp_insert_google_remarketing_tag()
{
    $google_remarketing_id = wpbp_get_option('google_remarketing_id');

    $file = TEMPLATE_DIRECTORY . '/inc/tags/google_remarketing.php';

    if ( !empty($google_remarketing_id) && file_exists($file) ) {
        include($file);
    }
}

function wpbp_insert_google_tag_manager()
{
    $gtm_id = esc_attr( wpbp_get_option('google_tag_manager_id') );

    $file = TEMPLATE_DIRECTORY . '/inc/tags/google_tag_manager.php';

    if ( !empty($gtm_id) && file_exists($file) ) {
        include($file);
    }
}

function wpbp_insert_custom_css()
{
    $custom_css = wpbp_get_option('custom_css');

    if ( !empty($custom_css) ) {
        echo '<style type="text/css">'.$custom_css.'</style>';
    }
}

function wpbp_insert_custom_js()
{
    $custom_js = wpbp_get_option('custom_js');

    if ( !empty($custom_js) ) {
        echo '<script type="text/javascript">'.$custom_js.'</script>';
    }
}

function wpbp_insert_post_js()
{
    set_post_ID($post_ID);
    $post_js = get_post_meta($post_ID, 'js', true);

    if ( !empty($post_js) ) {
        echo '<script type="text/javascript">'.$post_js.'</script>';
    }
}

function wpbp_insert_favicon()
{
    $favicon = wpbp_get_option('favicon');

    if ( !empty($favicon) ) {
        echo '<link rel="icon" type="image/png" href="' . $favicon . '">';
    }
}

