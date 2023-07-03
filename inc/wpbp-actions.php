<?php

add_action( 'init', 'wpbp_register_lib' );

add_action( 'wpbp_head', 'wpbp_insert_utm_values' );
add_action( 'wpbp_head', 'wpbp_insert_optimizely' );
add_action( 'wpbp_head', 'wpbp_insert_google_analytics' );
add_action( 'wpbp_head', 'wpbp_insert_google_tag_manager' );
add_action( 'wpbp_head', 'wpbp_insert_matomo_tag' );
add_action( 'wpbp_head', 'wpbp_insert_custom_css' );
add_action( 'wpbp_head', 'wpbp_insert_favicon' );
add_action( 'wpbp_head', 'wpbp_insert_custom_header_code' );
add_action( 'wpbp_head', 'wpbp_insert_post_js' );

add_action( 'wpbp_footer', 'wpbp_insert_gauges_tag' );
add_action( 'wpbp_footer', 'wpbp_insert_google_remarketing_tag' );
add_action( 'wpbp_footer', 'wpbp_insert_custom_js' );
add_action( 'wpbp_footer', 'wpbp_insert_custom_footer_code' );
add_action( 'wpbp_footer', 'wpbp_debug_dump' );

add_action( 'wpbp_loop_after', 'wpbp_clear' );

add_action( 'wp_ajax_wpbp_alert_admin', 'wpbp_alert_admin' );
add_action( 'wp_ajax_nopriv_wpbp_alert_admin', 'wpbp_alert_admin' );

function wpbp_insert_utm_values() {
    $utms = array(
        'utm_source'   => '',
        'utm_medium'   => '',
        'utm_term'     => '',
        'utm_content'  => '',
        'utm_campaign' => ''
    );

    foreach ( $utms as $utm => $value ) {
        if ( isset( $_GET[$utm] ) ) {
            $utms[$utm] = $_GET[$utm];
            setcookie( $utm, $_GET[$utm], time()+60*60*24*30, '/' ); // now + 30 days
        } elseif ( isset( $_COOKIE[$utm] ) ) {
            $utms[$utm] = $_COOKIE[$utm];
        }
    }

    $file = TEMPLATE_DIRECTORY . '/inc/tags/utm.php';

    if ( file_exists( $file ) ) {
        include( $file );
    }
}

function wpbp_insert_optimizely() {
    $project_id = wpbp_get_option( 'optimizely_project_id' );

    $file = TEMPLATE_DIRECTORY . '/inc/tags/optimizely.php';

    if ( ! empty( $project_id ) && file_exists( $file ) ) {
        include( $file );
    }
}

function wpbp_insert_google_analytics() {
    $google_analytics_id = wpbp_get_option( 'google_analytics_id' );
    $google_ads_id = wpbp_get_option( 'google_ads_id' );

    if ( ! empty( $google_analytics_id ) ) {
        $file = TEMPLATE_DIRECTORY . '/inc/tags/google_analytics.php';

        if ( file_exists( $file ) ) {
            include( $file );
        }
    }
}

function wpbp_insert_google_remarketing_tag() {
    $google_remarketing_id = wpbp_get_option( 'google_remarketing_id' );

    $file = TEMPLATE_DIRECTORY . '/inc/tags/google_remarketing.php';

    if ( ! empty( $google_remarketing_id ) && file_exists( $file ) ) {
        include( $file );
    }
}

function wpbp_insert_google_tag_manager() {
    $gtm_id = esc_attr( wpbp_get_option( 'google_tag_manager_id' ) );

    $file = TEMPLATE_DIRECTORY . '/inc/tags/google_tag_manager.php';

    if ( ! empty( $gtm_id ) && file_exists( $file ) ) {
        include( $file );
    }
}

function wpbp_insert_gauges_tag() {
    $site_id = wpbp_get_option( 'gauges_site_id' );

    $file = TEMPLATE_DIRECTORY . '/inc/tags/gauges.php';

    if ( ! empty( $site_id ) && file_exists( $file ) ) {
        include( $file );
    }
}

function wpbp_insert_matomo_tag() {
    $matomo_endpoint = wpbp_get_option( 'matomo_endpoint' );
    $matomo_site_id = wpbp_get_option( 'matomo_endpoint' );

    if ( ! empty( $matomo_endpoint ) && ! empty( $matomo_site_id ) ) {
        if ( file_exists( $file = TEMPLATE_DIRECTORY . '/inc/tags/matomo.php' ) ) {
            include( $file );
        }
    }
}

function wpbp_insert_custom_css() {
    $custom_css = wpbp_get_option( 'custom_css' );

    if ( ! empty( $custom_css ) ) {
        echo '<style>' . $custom_css . '</style>' . PHP_EOL;
    }
}

function wpbp_insert_custom_js() {
    $custom_js = wpbp_get_option( 'custom_js' );

    if ( ! empty( $custom_js ) ) {
        echo '<script>' . $custom_js . '</script>' . PHP_EOL;
    }
}

function wpbp_insert_post_js() {
    set_post_ID( $post_ID );
    $post_js = get_post_meta( $post_ID, 'js', true );

    if ( ! empty( $post_js ) ) {
        echo '<script>' . $post_js . '</script>' . PHP_EOL;
    }
}

function wpbp_insert_custom_header_code() {
    $code = wpbp_get_option( 'custom_header_code' );

    if ( ! empty( $code ) ) {
        echo $code . PHP_EOL;
    }
}

function wpbp_insert_custom_footer_code() {
    $code = wpbp_get_option( 'custom_footer_code' );

    if ( ! empty( $code ) ) {
        echo $code . PHP_EOL;
    }
}

function wpbp_insert_favicon() {
    $favicon = wpbp_get_option( 'favicon' );

    if ( ! empty( $favicon ) ) {
        echo '<link rel="icon" type="image/png" href="' . $favicon . '">' . PHP_EOL;
    }
}

function wpbp_alert_admin() {
    $subject = $_REQUEST['subject'];
    $body = $_REQUEST['body'];
    $admin_email = get_option( 'admin_email' );
    $headers  = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type: text/plain; charset=UTF-8" . "\r\n";
    return mail( $admin_email, $subject, $body, $headers );
}

function wpbp_debug_dump() {
    global $wpbp_debug;

    if ( ! empty( $wpbp_debug ) ) {
        echo '<!-- wpbp_debug: ' . PHP_EOL;
        var_dump( $wpbp_debug );
        echo '-->' . PHP_EOL;
    }
}
