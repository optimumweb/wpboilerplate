<?php

function wpbp_register_lib() {
	if ( ! is_admin() ) {

        $wpbp_libs = wpbp_get_lib();

        if ( is_array( $wpbp_libs ) && count( $wpbp_libs ) > 0 ) {

            foreach ( $wpbp_libs as $handle => $lib ) {

                $lib = array_merge( array(
                    'js'   => null,
                    'css'  => null,
                    'deps' => array(),
                    'ver'  => null,
                    'args' => array(),
                ), $lib );

                if ( isset( $lib['js'] ) ) {

                    $js = $lib['js'];

                    if ( isset( $js['src'] ) ) {

                        foreach ( array( 'ver', 'deps', 'args' ) as $attr ) {
                            if ( ! array_key_exists( $attr, $js ) && array_key_exists( $attr, $lib ) ) {
                                $js[$attr] = $lib[$attr];
                            }
                        }

                        $js['src'] = is_array($js['src']) ? $js['src'] : array( $js['src'] );

                        foreach ( $js['src'] as $key => $src ) {
                            $_handle = count( $js['src'] ) > 1 ? $handle . '_' . md5( $src ) : $handle;
                            wpbp_register_script( $_handle, $src, $js['deps'], $js['ver'], $js['args'] );
                        }

                    }

                }

                if ( isset( $lib['css'] ) ) {

                    $css = $lib['css'];

                    if ( isset( $css['src'] ) ) {

                        foreach ( array( 'ver', 'deps' ) as $attr ) {
                            if ( ! array_key_exists( $attr, $css ) && array_key_exists( $attr, $lib ) ) {
                                $css[$attr] = $lib[$attr];
                            }
                        }

                        $css['src'] = is_array( $css['src'] ) ? $css['src'] : array( $css['src'] );

                        foreach ( $css['src'] as $key => $src ) {
                            $_handle = count( $css['src'] ) > 1 ? $handle . '_' . md5( $src ) : $handle;
                            wpbp_register_style( $_handle, $src, $css['deps'], $css['ver'] );
                        }

                    }

                }

            }

        }

        if ( $js_files = wpbp_get_option( 'js_files' ) ) {
            $js_files = preg_split( '/\r\n|\r|\n/', $js_files );
            if ( is_array( $js_files ) && count( $js_files ) > 0 ) {
                foreach ( $js_files as $js_file ) {
                    wpbp_add_script( pathinfo( $js_file, PATHINFO_FILENAME ), $js_file );
                }
            }
        }

        if ( $css_files = wpbp_get_option( 'css_files' ) ) {
            $css_files = preg_split( '/\r\n|\r|\n/', $css_files );
            if ( is_array( $css_files ) && count( $css_files ) > 0 ) {
                foreach ( $css_files as $css_file ) {
                    wpbp_add_style( pathinfo( $css_file, PATHINFO_FILENAME ), $css_file );
                }
            }
        }

    }
}

function wpbp_register_script( $handle, $src = false, $deps = array(), $ver = false, $args = array() ) {
	wp_deregister_script( $handle );
	wp_register_script( $handle, $src, $deps, $ver, $args );
}

function wpbp_add_script( $handle, $src = false, $deps = array(), $ver = false, $args = array() ) {
	wpbp_register_script( $handle, $src, $deps, $ver, $args );
	wp_enqueue_script( $handle );
}

function wpbp_register_style( $handle, $src = false, $deps = array(), $ver = false, $media = 'all' ) {
	wp_deregister_style( $handle );
	wp_register_style( $handle, $src, $deps, $ver, $media );
}

function wpbp_add_style( $handle, $src = false, $deps = array(), $ver = false, $media = 'all' ) {
	wpbp_register_style( $handle, $src, $deps, $ver, $media );
	wp_enqueue_style( $handle, $src, $deps, $ver, $media );
}

function wpbp_enqueue_lib( $handles = null ) {
    if ( is_string( $handles ) ) $handles = array( $handles );

    $lib = wpbp_get_lib();

    foreach ( $handles as $handle ) {
        if ( isset( $lib[$handle] ) ) {
            if ( isset( $lib[$handle]['js'] ) ) {
                $js = $lib[$handle]['js'];

                if ( is_array( $js['src'] ) ) {
                    foreach ( $js['src'] as $key => $src ) {
                        wp_enqueue_script( $handle . '_' . $key );
                    }
                } else {
                    wp_enqueue_script( $handle );
                }
            }
            if ( isset( $lib[$handle]['css'] ) ) {
                $css = $lib[$handle]['css'];

                if ( is_array( $css['src'] ) ) {
                    foreach ( $css['src'] as $key => $src ) {
                        wp_enqueue_style( $handle . '_' . $key );
                    }
                } else {
                    wp_enqueue_style( $handle );
                }
            }
        }
    }
}

function wpbp_enqueue_scripts( $scripts = array() ) {
	if ( is_array( $scripts ) && count( $scripts ) > 0 ) {
		foreach ( $scripts as $handle ) {
			wp_enqueue_script( $handle );
		}
	}
}

function wpbp_enqueue_styles( $styles = array() ) {
	if ( is_array( $styles ) && count( $styles ) > 0 ) {
		foreach ( $styles as $handle ) {
			wp_enqueue_style( $handle );
		}
	}
}
