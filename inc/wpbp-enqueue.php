<?php

function wpbp_register_lib()
{
    global $wpbp_debug;

	if ( !is_admin() ) {

        $wpbp_libs = wpbp_get_lib();

        if ( is_array($wpbp_libs) && count($wpbp_libs) > 0 ) {

            foreach ( $wpbp_libs as $handle => $lib ) {

                $lib = array_merge([
                    'js'        => null,
                    'css'       => null,
                    'deps'      => [],
                    'ver'       => null,
                    'in_footer' => false
                ], $lib);

                if ( isset($lib['js']) ) {

                    $js = $lib['js'];

                    if ( isset($js['src']) ) {

                        foreach ( ['ver', 'deps', 'in_footer'] as $attr ) {
                            if ( !array_key_exists($attr, $js) && array_key_exists($attr, $js) ) {
                                $js[$attr] = $lib[$attr];
                            }
                        }

                        $js['src'] = is_array($js['src']) ? $js['src'] : [ $js['src'] ];

                        foreach ( $js['src'] as $key => $src ) {
                            $_handle = count($js['src']) > 1 ? $handle . '_' . md5($src) : $handle;
                            $wpbp_debug[] = compact('_handle', 'src');
                            wpbp_register_script($_handle, $src, $js['deps'], $js['ver'], $js['in_footer']);
                        }

                    }

                }

                if ( isset($lib['css']) ) {

                    $css = $lib['css'];

                    if ( isset($css['src']) ) {

                        foreach ( ['ver', 'deps'] as $attr ) {
                            if ( !array_key_exists($attr, $css) && array_key_exists($attr, $lib) ) {
                                $css[$attr] = $lib[$attr];
                            }
                        }

                        $css['src'] = is_array($css['src']) ? $css['src'] : [ $css['src'] ];

                        foreach ( $css['src'] as $key => $src ) {
                            $_handle = count($css['src']) > 1 ? $handle . '_' . md5($src) : $handle;
                            wpbp_register_style($_handle, $src, $css['deps'], $css['ver']);
                        }

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
	wp_deregister_style($handle);
	wp_register_style($handle, $src, $deps, $ver, $media);
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
                $js = $lib[$handle]['js'];
                if ( is_array($js['src']) ) {
                    foreach ( $js['src'] as $key => $src ) {
                        wp_enqueue_script($handle . "_" . $key);
                    }
                } else {
                    wp_enqueue_script($handle);
                }
            }
            if ( isset($lib[$handle]['css']) ) {
                $css = $lib[$handle]['css'];
                if ( is_array($css['src']) ) {
                    foreach ( $css['src'] as $key => $src ) {
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
