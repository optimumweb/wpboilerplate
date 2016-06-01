<?php

add_action('init',      'wpbp_adtrack_init');
add_action('wpbp_head', 'wpbp_adtrack_insert');

$adtrack_params = [ 'lpurl', 'matchtype', 'network', 'creative', 'keyword', 'placement', 'adposition', 'device' ];

function wpbp_adtrack_init()
{
    global $adtrack_params;

    if ( !empty($_GET) ) {
        foreach ( $adtrack_params as $param ) {
            if ( isset($_GET[$param]) ) {
                wpbp_adtrack_set_param($param, $_GET[$param]);
            }
        }
    }
}

function wpbp_adtrack_set_param($param, $value)
{
    return setcookie("adtrack_" . $param, $_GET[$param], time() + 60*24*60*60);
}

function wpbp_adtrack_get_param($param)
{
    if ( isset($_COOKIE["adtrack_" . $param]) ) {
        return $_COOKIE["adtrack_" . $param];
    }
}

function wpbp_adtrack_get_all()
{
    global $adtrack_params;

    $values = [];

    foreach ( $adtrack_params as $param ) {
        if ( $value = wpbp_adtrack_get_param($param) ) {
            $values[$param] = $value;
        }
    }

    return $values;
}

function wpbp_adtrack_insert()
{
    global $adtrack_params;

    $file = TEMPLATE_DIRECTORY . '/inc/tags/adtrack.php';

    if ( file_exists($file) ) {
        $adtrack_values = wpbp_adtrack_get_all();
        include($file);
    }
}
