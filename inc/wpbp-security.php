<?php

function wpbp_force_https()
{
    if ( wpbp_get_option('force_https') == 'yes' && !is_ssl() ) {
        wp_redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301);
        exit();
    }
}
add_action('template_redirect', 'wpbp_force_https', 1);
