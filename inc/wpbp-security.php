<?php

function wpbp_force_https()
{
    if ( !is_ssl() ) {
        wp_redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301 );
        exit();
    }
}

if ( wpbp_get_option('force_https') == 'yes' ) {
    add_action('template_redirect', 'wpbp_force_https', 1);
}
