<?php

function wpbp_get_the_excerpt( $post_ID = null, $limit = 250 ) {
    set_post_ID( $post_ID );

    if ( $post = get_post( $post_ID ) ) {
        if ( isset( $post->post_excerpt ) && strlen( $post->post_excerpt ) > 0 ) {
            return $post->post_excerpt;
        } else {
            return substr( strip_tags( $post->post_content ), 0, 250 ) . '...';
        }
    }
}

function wpbp_get_posts_by_tag( $tag, $args = array(), $field = 'name' ) {
    if ( $term = get_term_by( $field, $tag, 'post_tag' ) ) {
        $args = array_merge( (array) $args, array( 'tag__in' => $term->term_id ) );
        return get_posts( $args );
    }
}

function wpbp_error_log( $message, $notify_admin = false, $echo_in_footer = false ) {
    $wp_debug_file_path = WP_CONTENT_DIR . '/debug.log';

    if ( is_writeable( $wp_debug_file_path ) ) {
        if ( $handle = @fopen( $wp_debug_file_path, 'a' ) ) {
            $result = @fwrite( $handle, $message . PHP_EOL );
            @fclose( $handle );
        }
    }

    if ( $notify_admin ) {

        $admin_email = get_option( 'admin_email' );

        $mail = new Mail;
        $mail->set_option( 'content_type', "text/plain; charset=utf-8" );
        $mail->set_option( 'from', $admin_email );
        $mail->set_option( 'to', $admin_email );
        $mail->set_option( 'subject', "WPBP Error Notification" );
        $mail->set_body( $message );
        $mail->send();

        if ( $mail->get_response() != 200 ) {
            wpbp_error_log( "Can't send error notification to admin (" . $admin_email . ")" );
        }
    }

    if ( $echo_in_footer ) {
        // to do
    }

    return $result;
}

function wpbp_wpml_lang_sel( $display = 'native_name' ) {
    $languages = function_exists( 'icl_get_languages' ) ? icl_get_languages( 'skip_missing=1&orderby=code' ) : array();

    echo '<ul class="menu lang-sel">';

    if ( is_array( $languages ) && count( $languages ) > 1 ) {
        foreach( $languages as $lang ) {
            echo '<li id="lang-' . $lang['language_code'] . '"' . ( $lang['active'] ? ' class="current-menu-item"' : '' ) . '>';
            echo '<a' . ( $lang['active'] ? '' : ' rel="alternate"' ) . ' href="' . $lang['url'] . '" hreflang="' . $lang['language_code'] . '">' . $lang[$display] . '</a>';
            echo '</li>';
        }
    } elseif ( defined('ICL_LANGUAGE_CODE') ) {
        $is_en = ICL_LANGUAGE_CODE === 'en';
        $is_fr = ICL_LANGUAGE_CODE === 'fr';

        echo '<li id="lang-en"' . ( $is_en ? ' class="current-menu-item"' : '' ) . '><a' . ( $is_en ? '' : ' rel="alternate"' ) . ' href="' . ( $is_en ? '#' : '/en/' ) . '" hreflang="en">English</a></li>';
        echo '<li id="lang-fr"' . ( $is_fr ? ' class="current-menu-item"' : '' ) . '><a' . ( $is_fr ? '' : ' rel="alternate"' ) . ' href="' . ( $is_fr ? '#' : '/' ) . '" hreflang="fr">Fran&ccedil;ais</a></li>';
    }

    echo '</ul>';
}

function wpbp_container_class() {
    $class = array( 'container' );
    if ( wpbp_get_option( 'fluid' ) === 'yes' ) {
        $class[] = 'fluid';
    }
    echo implode( '', $class );
}
