<?php

function wpbp_force_https()
{
    if ( !is_ssl() && wpbp_get_option('force_https') == 'yes' ) {
        wp_redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301);
        exit();
    }
}
add_action('template_redirect', 'wpbp_force_https', 1);


function wpbp_hsts_header()
{
    if ( is_ssl() ) {
        $hsts_max_age = wpbp_get_option('hsts_max_age');
        if ( $hsts_max_age != '' ) {
            header(sprintf("Strict-Transport-Security: max-age=%d; includeSubDomains; preload", $hsts_max_age));
        }
    }
}
add_action('send_headers', 'wpbp_hsts_header');

function wpbp_disable_xmlrpc()
{
    if ( wpbp_get_option('disable_xmlrpc') == "yes" ) {
        add_filter('xmlrpc_enabled', '__return_false');
    }
}
add_action('init', 'wpbp_disable_xmlrpc');
