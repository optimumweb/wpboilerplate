<?php

add_action( 'init',      'wpbp_adtrack_init' );
add_action( 'wpbp_head', 'wpbp_adtrack_insert' );

$adtrack_params = array( 'lpurl', 'matchtype', 'network', 'creative', 'keyword', 'placement', 'adposition', 'device', 'gclid' );
$adtrack_values = array();

function wpbp_adtrack_init() {
    global $adtrack_params, $adtrack_values;

    foreach ( $adtrack_params as $param ) {
        if ( isset( $_GET[$param] ) ) {
            wpbp_adtrack_set_param( $param, $_GET[$param] );
        }
    }

    $adtrack_values = wpbp_adtrack_get_all();
}

function wpbp_adtrack_set_param( $param, $value, $expire_in_days = 60 ) {
    global $adtrack_values;

    $adtrack_values[$param] = $value;
    $expire = time() + $expire_in_days * 24 * 60 * 60;
    setcookie( 'adtrack_' . $param, $value, $expire );
}

function wpbp_adtrack_get_param( $param ) {
    global $adtrack_values;

    if ( isset( $_COOKIE['adtrack_' . $param] ) ) {
        return $_COOKIE['adtrack_' . $param];
    } elseif ( isset( $adtrack_values[$param] ) ) {
        return $adtrack_values[$param];
    }
}

function wpbp_adtrack_get_all() {
    global $adtrack_params;

    $values = array();

    foreach ( $adtrack_params as $param ) {
        if ( $value = wpbp_adtrack_get_param( $param ) ) {
            $values[$param] = $value;
        }
    }

    return $values;
}

function wpbp_adtrack_insert() {
    global $adtrack_params;

    $file = TEMPLATE_DIRECTORY . '/inc/tags/adtrack.php';

    if ( file_exists( $file ) ) {
        $adtrack_values = wpbp_adtrack_get_all();
        include( $file );
    }
}
