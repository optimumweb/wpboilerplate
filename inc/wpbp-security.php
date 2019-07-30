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

function wpbp_deny_xmlrpc($htaccess_rules)
{
    if ( wpbp_get_option('disable_xmlrpc') == "yes" ) {
        $filename = TEMPLATE_DIRECTORY . '/inc/htaccess/deny_xmlrpc';
        if ( file_exists($filename) ) {
            $content = file_get_contents($filename);
            return $content . $htaccess_rules;
        }
    }
    return $htaccess_rules;
}
add_filter('mod_rewrite_rules', 'wpbp_deny_xmlrpc');


function wpbp_login_notification()
{
    $current_user = wp_get_current_user();
    $admin_email = get_option('admin_email');
    $blogname = get_option('blogname');
    $subject = sprintf("%s - Login Notification", $blogname);
    $message = implode(PHP_EOL, [
        sprintf("Datetime: %s", date('c')),
        sprintf("Username: %s", $current_user->user_login),
        sprintf("IP Address: %s", $_SERVER['REMOTE_ADDR'])
    ]);
    wp_mail($admin_email, $subject, $message);
}
add_action('wp_login', 'wpbp_login_notification');
