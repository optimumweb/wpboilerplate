<?php

function wpbp_register_lib()
{
	if ( !is_admin() ) {

        $wpbp_libs = wpbp_get_lib();

        if ( is_array($wpbp_libs) && count($wpbp_libs) > 0 ) {

            foreach ( $wpbp_libs as $handle => $lib ) {

                $lib = array_merge(array(
                    'js'        => null,
                    'css'       => null,
                    'deps'      => array(),
                    'ver'       => null,
                    'in_footer' => false
                ), $lib);

                if ( !empty($lib['js']) ) {
                    if ( is_array($lib['js']) ) {
                        foreach ( $lib['js'] as $key => $js ) {
                            wpbp_register_script($handle . "_" . $key, $js, $lib['deps'], $lib['ver'], $lib['in_footer']);
                        }
                    } else {
                        wpbp_register_script($handle, $lib['js'], $lib['deps'], $lib['ver'], $lib['in_footer']);
                    }
                }

                if ( !empty($lib['css']) ) {
                    if ( is_array($lib['css']) ) {
                        foreach ( $lib['css'] as $key => $css ) {
                            wpbp_register_style($handle . "_" . $key, $css);
                        }
                    } else {
                        wpbp_register_style($handle, $lib['css'], $lib['deps'], $lib['ver']);
                    }
                }
            }

        }

        if ( $js_files = wpbp_get_option('js_files') ) {
            $js_files = preg_split('/\r\n|\r|\n/', $js_files);
            if ( is_array($js_files) && count($js_files) > 0 ) {
                foreach ( $js_files as $js_file ) {
                    wpbp_add_script( pathinfo($js_file, PATHINFO_FILENAME), $js_file );
                }
            }
        }

        if ( $css_files = wpbp_get_option('css_files') ) {
            $css_files = preg_split('/\r\n|\r|\n/', $css_files);
            if ( is_array($css_files) && count($css_files) ) {
                foreach ( $css_files as $css_file ) {
                    wpbp_add_style( pathinfo($css_file, PATHINFO_FILENAME), $css_file );
                }
            }
        }

    }
}

function wpbp_register_script($handle, $src = false, $deps = array(), $ver = false, $in_footer = false)
{
    if ( is_array($deps) && count($deps) > 0 ) {
        foreach ( $deps as &$dep ) {
            //if ( !wp_script_is($handle, 'registered') ) unset($dep);
        }
    }
	wp_deregister_script($handle);
	wp_register_script($handle, $src, $deps, $ver, $in_footer);
}

function wpbp_add_script($handle, $src = false, $deps = array(), $ver = false, $in_footer = true)
{
	wpbp_register_script($handle, $src, $deps, $ver, $in_footer);
	wp_enqueue_script($handle);
}

function wpbp_register_style($handle, $src = false, $deps = array(), $ver = false, $media = 'all')
{
    if ( is_array($deps) && count($deps) > 0 ) {
        foreach ( $deps as &$dep ) {
            //if ( !wp_style_is($handle, 'registered') ) unset($dep);
        }
    }
	wp_deregister_style($handle);
    wp_register_style($handle, $src);
	//wp_register_style($handle, $src, $deps, $ver, $media);
}

function wpbp_add_style($handle, $src = false, $deps = array(), $ver = false, $media = 'all')
{
	wpbp_register_style($handle, $src, $deps, $ver, $media);
	wp_enqueue_style($handle, $src, $deps, $ver, $media);
}

function wpbp_enqueue_lib($handles = null)
{
    if ( is_string($handles) ) $handles = array( $handles );

    $lib = wpbp_get_lib();

    foreach ( $handles as $handle ) {
        if ( isset($lib[$handle]) ) {
            if ( isset($lib[$handle]['js']) ) {
                if ( is_array($lib[$handle]['js']) ) {
                    foreach ( $lib[$handle]['js'] as $key => $js ) {
                        wp_enqueue_script($handle . "_" . $key);
                    }
                } else {
                    wp_enqueue_script($handle);
                }
            }
            if ( isset($lib[$handle]['css']) ) {
                if ( is_array($lib[$handle]['css']) ) {
                    foreach ( $lib[$handle]['css'] as $key => $css ) {
                        wp_enqueue_style($handle . "_" . $key);
                    }
                } else {
                    wp_enqueue_style($handle);
                }
            }
        }
    }
}

function wpbp_enqueue_scripts($scripts = array())
{
	if ( is_array($scripts) ) {
		foreach ( $scripts as $handle ) {
			wp_enqueue_script($handle);
		}
	}
}

function wpbp_enqueue_styles($styles = array())
{
	if ( is_array($styles) ) {
		foreach ( $styles as $handle ) {
			wp_enqueue_style($handle);
		}
	}
}
