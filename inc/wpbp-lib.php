<?php

function wpbp_get_lib( $which = null ) {
    $wpbp_lib = array(
        'default' => array(
            'css' => array(
                'src' => TEMPLATE_URI . '/lib/wpbp/css/default.css'
            )
        ),
        'wpbp' => array(
            'js' => array(
                'src' => TEMPLATE_URI . '/js/wpbp.js',
                'deps' => array( 'jquery' ),
                'args' => array(
                    'strategy' => 'defer',
                ),
            ),
            'css' => array(
                'src' => TEMPLATE_URI . '/css/wpbp.css'
            ),
            'ver' => '3.1.7'
        ),
        'jquery' => array(
            'js' => array(
                'src' => TEMPLATE_URI . '/lib/jquery/jquery.min.js',
                'args' => array( 'strategy' => 'defer' ),
            ),
            'ver' => '3.3.1'
        ),
        'jquery-ui' => array(
            'js' => array(
                'src' => TEMPLATE_URI . '/lib/jquery-ui/jquery-ui.min.js',
                'args' => array( 'strategy' => 'defer' ),
            ),
            'css' => array(
                'src' => TEMPLATE_URI . '/lib/jquery-ui/css/base/jquery.ui.all.css'
            ),
            'ver' => '1.12.1'
        ),
        'jquery-ui-smoothness' => array(
            'css' => array(
                'src' => TEMPLATE_URI . '/lib/jquery-ui/css/smoothness/jquery-ui.css'
            )
        ),
        'jquery-ui-lightness' => array(
            'css' => array(
                'src' => TEMPLATE_URI . '/lib/jquery-ui/css/ui-lightness/jquery-ui.css'
            )
        ),
        'jquery-ui-darkness' => array(
            'css' => array(
                'src' => TEMPLATE_URI . '/lib/jquery-ui/css/ui-darkness/jquery-ui.css'
            )
        ),
        'jquery-ui-redmond' => array(
            'css' => array(
                'src' => TEMPLATE_URI . '/lib/jquery-ui/css/redmond/jquery-ui.css'
            )
        ),
        'jquery-ui-blitzer' => array(
            'css' => array(
                'src' => TEMPLATE_URI . '/lib/jquery-ui/css/blitzer/jquery-ui.css'
            )
        ),
        '960gs' => array(
            'css' => array(
                'src' => TEMPLATE_URI . '/lib/960gs/960.min.css'
            )
        ),
        'scrollTo' => array(
            'js' => array(
                'src'  => TEMPLATE_URI . '/lib/scrollTo/jquery.scrollTo.min.js',
                'deps' => array( 'jquery' ),
                'args' => array( 'strategy' => 'defer' ),
            ),
            'ver' => '1.4.2'
        ),
        'cycle' => array(
            'js' => array(
                'src'  => TEMPLATE_URI . '/lib/cycle/jquery.cycle.min.js',
                'deps' => array( 'jquery' ),
                'args' => array( 'strategy' => 'defer' ),
            ),
            'ver' => '2.9998'
        ),
        'powerslide' => array(
            'js' => array(
                'src'  => TEMPLATE_URI . '/lib/powerslide/js/powerslide.min.js',
                'deps' => array( 'jquery' ),
                'args' => array( 'strategy' => 'defer' ),
            ),
            'css' => array(
                'src' => TEMPLATE_URI . '/lib/powerslide/css/powerslide.css'
            ),
            'ver' => '1.1'
        ),
        'lightbox' => array(
            'js' => array(
                'src'  => TEMPLATE_URI . '/lib/lightbox/js/lightbox.min.js',
                'deps' => array( 'jquery' ),
                'args' => array( 'strategy' => 'defer' ),
            ),
            'css' => TEMPLATE_URI . '/lib/lightbox/css/lightbox.min.css',
            'ver' => '2.51'
        ),
        'hoverIntent' => array(
            'js' => array(
                'src' => TEMPLATE_URI . '/lib/hoverIntent/jquery.hoverIntent.min.js',
                'deps' => array( 'jquery' ),
                'args' => array( 'strategy' => 'defer' ),
            ),
            'ver' => '6.0'
        ),
        'flexslider' => array(
            'js' => array(
                'src' => TEMPLATE_URI . '/lib/flexslider/jquery.flexslider.min.js',
                'deps' => array( 'jquery' ),
                'args' => array( 'strategy' => 'defer' ),
            ),
            'css' => array(
                'src' => TEMPLATE_URI . '/lib/flexslider/flexslider.css'
            ),
            'ver' => '2.1'
        ),
        'bootstrap' => array(
            'js' => array(
                'src'  => TEMPLATE_URI . '/lib/bootstrap/js/bootstrap.min.js',
                'deps' => array( 'jquery' ),
                'args' => array( 'strategy' => 'defer' ),
            ),
            'css' => array(
                'src' => TEMPLATE_URI . '/lib/bootstrap/css/bootstrap.min.css'
            ),
            'ver' => '2.0.4'
        ),
        'bootstrap-responsive' => array(
            'css' => array(
                'src' => TEMPLATE_URI . '/lib/bootstrap/css/bootstrap-responsive.min.css'
            )
        ),
        'ext-core' => array(
            'js' => array(
                'src' => 'https://ajax.googleapis.com/ajax/lib/ext-core/3.1.0/ext-core.js'
            ),
            'ver' => '3.1.0'
        ),
        'dojo' => array(
            'js' => array(
                'src' => 'https://ajax.googleapis.com/ajax/lib/dojo/1.6.1/dojo/dojo.xd.js',
                'args' => array( 'strategy' => 'defer' ),
            ),
            'ver' => '1.6.1'
        ),
        'mootools' => array(
            'js' => array(
                'src' => 'https://ajax.googleapis.com/ajax/lib/mootools/1.4.1/mootools-yui-compressed.js',
                'args' => array( 'strategy' => 'defer' ),
            ),
            'ver' => '1.4.1'
        ),
        'modernizr' => array(
            'js' => array(
                'src' => TEMPLATE_URI . '/lib/modernizr/2.8.3/modernizr.min.js',
                'args' => array( 'strategy' => 'defer' ),
            ),
            'ver' => '2.8.3'
        ),
        'lesscss' => array(
            'js' => array(
                'src' => TEMPLATE_URI . '/lib/less/less.min.js',
            ),
            'ver' => '1.2.1',
        ),
        'sizzle' => array(
            'js' => array(
                'src' => TEMPLATE_URI . '/lib/sizzle/sizzle.min.js',
                'args' => array( 'strategy' => 'defer' ),
            ),
            'ver' => '1.5.1'
        ),
        'highcharts' => array(
            'js' => array(
                'src' => TEMPLATE_URI . '/lib/highcharts/highcharts.min.js',
                'args' => array( 'strategy' => 'defer' ),
            ),
            'ver' => '2.1.9'
        ),
        'inview' => array(
            'js' => array(
                'src' => TEMPLATE_URI . '/lib/inview/jquery.inview.min.js',
                'args' => array( 'strategy' => 'defer' ),
            ),
            'ver' => '1.0'
        ),
        'jquery-cookie' => array(
            'js' => array(
                'src' => TEMPLATE_URI . '/lib/jquery-cookie/jquery.cookie.js',
            ),
            'ver' => '1.0'
        ),
        'tinyNav' => array(
            'js' => array(
                'src' => TEMPLATE_URI . '/lib/tinynav/tinynav.min.js',
                'args' => array( 'strategy' => 'defer' ),
            ),
            'ver' => '1.1'
        ),
        'galleria' => array(
            'js' => array(
                'src' => TEMPLATE_URI . '/lib/galleria/galleria-1.3.5.min.js',
                'args' => array( 'strategy' => 'defer' ),
            ),
            'ver' => '1.3.5'
        ),
        'gmap' => array(
            'js' => array(
                'src' => TEMPLATE_URI . '/lib/gmap/gmap.js',
                'args' => array( 'strategy' => 'defer' ),
            ),
            'ver' => '1.1'
        ),
        'animatedElement' => array(
            'js' => array(
                'src'  => TEMPLATE_URI . '/lib/animated-element/jquery.animatedElement.js',
                'deps' => array( 'jquery' ),
                'args' => array( 'strategy' => 'defer' ),
            ),
            'css' => array(
                'src' => TEMPLATE_URI . '/lib/animated-element/animatedElement.css'
            ),
            'ver' => '1.0'
        ),
        'magnific-popup' => array(
            'js' => array(
                'src'  => TEMPLATE_URI . '/lib/magnific-popup/jquery.magnific-popup.min.js',
                'deps' => array( 'jquery' ),
                'args' => array( 'strategy' => 'defer' ),
            ),
            'css' => array(
                'src' => TEMPLATE_URI . '/lib/magnific-popup/magnific-popup.css'
            ),
            'ver' => '1.0.0'
        ),
        'noUiSlider' => array(
            'js' => array(
                'src' => TEMPLATE_URI . '/lib/noUiSlider/nouislider.min.js',
                'args' => array( 'strategy' => 'defer' ),
            ),
            'css' => array(
                'src' => array(
                    TEMPLATE_URI . '/lib/noUiSlider/nouislider.min.css',
                    TEMPLATE_URI . '/lib/noUiSlider/nouislider.tooltips.css'
                )
            ),
            'ver' => '8.2.1'
        ),
        'wNumb' => array(
            'js' => array(
                'src' => TEMPLATE_URI . '/lib/wnumb/wNumb.js',
                'args' => array( 'strategy' => 'defer' ),
            ),
            'ver' => '1.0.2'
        ),
        'owlCarousel' => array(
            'js' => array(
                'src'  => TEMPLATE_URI . '/lib/owl-carousel/owl.carousel.min.js',
                'deps' => array( 'jquery' ),
                'args' => array( 'strategy' => 'defer' ),
            ),
            'css' => array(
                'src' => array(
                    TEMPLATE_URI . '/lib/owl-carousel/owl.carousel.css',
                    TEMPLATE_URI . '/lib/owl-carousel/owl.theme.css'
                )
            ),
            'ver' => '1.3.3'
        ),
        'parallax.js' => array(
            'js' => array(
                'src'  => TEMPLATE_URI . '/lib/parallax.js/parallax.min.js',
                'deps' => array( 'jquery' ),
                'args' => array( 'strategy' => 'defer' ),
            ),
            'ver' => '1.4.2'
        ),
        'fontawesome' => array(
            'css' => array(
                'src' => TEMPLATE_URI . '/lib/fontawesome/css/font-awesome.min.css'
            ),
            'ver' => '4.7.0'
        ),
        'wp-meta' => array(
            'css' => array(
                'src' => THEME_URI . '/style.css'
            )
        ),
        'js-cookie' => array(
            'js' => array(
                'src' => TEMPLATE_URI . '/lib/js-cookie/js.cookie.min.js',
            ),
            'ver' => '2.2.1'
        ),
        'aos' => array(
            'js' => array(
                'src' => TEMPLATE_URI . '/lib/aos/2.3.4/aos.js'
            ),
            'css' => array(
                'src' => TEMPLATE_URI . '/lib/aos/2.3.4/aos.css'
            ),
            'ver' => '2.3.4'
        )
    );

    return isset( $which, $wpbp_lib[$which] ) ? $wpbp_lib[$which] : $wpbp_lib;
}
