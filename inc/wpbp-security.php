<?php

function wpbp_force_https()
{
    if ( wpbp_get_option('force_https') == 'yes' ) {
        if ( !is_ssl() ) {
            wp_redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301);
            exit();
        }
    }
}
add_action('template_redirect', 'wpbp_force_https', 1);


function wpbp_hsts_header()
{
    if ( wpbp_get_option('force_https') == 'yes' ) {
        $hsts_max_age = wpbp_get_option('hsts_max_age');
        if ( $hsts_max_age != '' ) {
            header("Strict-Transport-Security: max-age=" . $hsts_max_age);
        }
    }
}
add_action('send_headers', 'wpbp_hsts_header');
