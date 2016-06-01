<?php

add_action('init', 'wpbp_adtrack_init');

function wpbp_adtrack_init()
{
    $params = [ 'lpurl', 'matchtype', 'network', 'creative', 'keyword', 'placement', 'adposition', 'device' ];

    if ( !empty($_GET) ) {
        foreach ( $params as $param ) {
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
