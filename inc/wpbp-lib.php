<?php

function wpbp_get_lib($which = null)
{
    $wpbp_lib = array(
        'jquery' => array(
            'js'   => TEMPLATE_URI . '/lib/jquery/jquery.min.js',
            'ver'  => '3.3.1'
        ),
        'jquery-ui' => array(
            'js'   => TEMPLATE_URI . '/lib/jquery-ui/jquery-ui.min.js',
            'css'  => TEMPLATE_URI . '/lib/jquery-ui/css/base/jquery.ui.all.css',
            'ver'  => '1.12.1'
        ),
        'jquery-ui-smoothness' => array(
            'css'  => TEMPLATE_URI . '/lib/jquery-ui/css/smoothness/jquery-ui.css'
        ),
        'jquery-ui-lightness' => array(
            'css'  => TEMPLATE_URI . '/lib/jquery-ui/css/ui-lightness/jquery-ui.css'
        ),
        'jquery-ui-darkness' => array(
            'css'  => TEMPLATE_URI . '/lib/jquery-ui/css/ui-darkness/jquery-ui.css'
        ),
        'jquery-ui-redmond' => array(
            'css'  => TEMPLATE_URI . '/lib/jquery-ui/css/redmond/jquery-ui.css'
        ),
        'jquery-ui-blitzer' => array(
            'css'  => TEMPLATE_URI . '/lib/jquery-ui/css/blitzer/jquery-ui.css'
        ),
        '960gs' => array(
            'css'  => TEMPLATE_URI . '/lib/960gs/960.min.css'
        ),
        'scrollTo' => array(
            'js'   => TEMPLATE_URI . '/lib/scrollTo/jquery.scrollTo.min.js',
            'deps' => array('jquery'),
            'ver'  => '1.4.2'
        ),
        'cycle' => array(
            'js'   => TEMPLATE_URI . '/lib/cycle/jquery.cycle.min.js',
            'deps' => array('jquery'),
            'ver'  => '2.9998'
        ),
        'powerslide' => array(
            'js'   => TEMPLATE_URI . '/lib/powerslide/js/powerslide.min.js',
            'css'  => TEMPLATE_URI . '/lib/powerslide/css/powerslide.css',
            'deps' => array('jquery'),
            'ver'  => '1.1'
        ),
        'lightbox' => array(
            'js'   => TEMPLATE_URI . '/lib/lightbox/js/lightbox.min.js',
            'css'  => TEMPLATE_URI . '/lib/lightbox/css/lightbox.min.css',
            'deps' => array('jquery'),
            'ver'  => '2.51'
        ),
        'hoverIntent' => array(
            'js'   => TEMPLATE_URI . '/lib/hoverIntent/jquery.hoverIntent.min.js',
            'deps' => array('jquery'),
            'ver'  => '6.0'
        ),
        'flexslider' => array(
            'js'   => TEMPLATE_URI . '/lib/flexslider/jquery.flexslider.min.js',
            'css'  => TEMPLATE_URI . '/lib/flexslider/flexslider.css',
            'deps' => array('jquery'),
            'ver'  => '2.1'
        ),
        'bootstrap' => array(
            'js'   => TEMPLATE_URI . '/lib/bootstrap/js/bootstrap.min.js',
            'css'  => TEMPLATE_URI . '/lib/bootstrap/css/bootstrap.min.css',
            'deps' => array('jquery'),
            'ver'  => '2.0.4'
        ),
        'bootstrap-responsive' => array(
            'css'  => TEMPLATE_URI . '/lib/bootstrap/css/bootstrap-responsive.min.css'
        ),
        'ext-core' => array(
            'js'   => 'https://ajax.googleapis.com/ajax/lib/ext-core/3.1.0/ext-core.js',
            'ver'  => '3.1.0'
        ),
        'dojo' => array(
            'js'   => 'https://ajax.googleapis.com/ajax/lib/dojo/1.6.1/dojo/dojo.xd.js',
            'ver'  => '1.6.1'
        ),
        'mootools' => array(
            'js'   => 'https://ajax.googleapis.com/ajax/lib/mootools/1.4.1/mootools-yui-compressed.js',
            'ver'  => '1.4.1'
        ),
        'modernizr' => array(
            'js'   => TEMPLATE_URI . '/lib/modernizr/2.8.3/modernizr.min.js',
            'ver'  => '2.8.3'
        ),
        'lesscss' => array(
            'js'   => TEMPLATE_URI . '/lib/less/less.min.js',
            'ver'  => '1.2.1'
        ),
        'sizzle' => array(
            'js'   => TEMPLATE_URI . '/lib/sizzle/sizzle.min.js',
            'ver'  => '1.5.1'
        ),
        'highcharts' => array(
            'js'   => TEMPLATE_URI . '/lib/highcharts/highcharts.min.js',
            'ver'  => '2.1.9'
        ),
        'inview' => array(
            'js'   => TEMPLATE_URI . '/lib/inview/jquery.inview.min.js',
            'ver'  => '1.0'
        ),
        'jquery-cookie' => array(
            'js'   => TEMPLATE_URI . '/lib/jquery-cookie/jquery.cookie.js',
            'ver'  => '1.0'
        ),
        'tinyNav' => array(
            'js'   => TEMPLATE_URI . '/lib/tinynav/tinynav.min.js',
            'ver'  => '1.1'
        ),
        'galleria' => array(
            'js'   => TEMPLATE_URI . '/lib/galleria/galleria-1.3.5.min.js',
            'ver'  => '1.3.5'
        ),
        'gmap' => array(
            'js'   => TEMPLATE_URI . '/lib/gmap/gmap.js',
            'ver'  => '1.1'
        ),
        'animatedElement' => array(
            'js'   => TEMPLATE_URI . '/lib/animated-element/jquery.animatedElement.js',
            'css'  => TEMPLATE_URI . '/lib/animated-element/animatedElement.css',
            'deps' => array('jquery'),
            'ver'  => '1.0'
        ),
        'magnific-popup' => array(
            'js'   => TEMPLATE_URI . '/lib/magnific-popup/jquery.magnific-popup.min.js',
            'css'  => TEMPLATE_URI . '/lib/magnific-popup/magnific-popup.css',
            'deps' => array('jquery'),
            'ver'  => '1.0.0'
        ),
        'noUiSlider' => array(
            'js'  => TEMPLATE_URI . '/lib/noUiSlider/nouislider.min.js',
            'css' => array(
                TEMPLATE_URI . '/lib/noUiSlider/nouislider.min.css',
                TEMPLATE_URI . '/lib/noUiSlider/nouislider.tooltips.css'
            ),
            'ver' => '8.2.1'
        ),
        'wNumb' => array(
            'js'  => TEMPLATE_URI . '/lib/wnumb/wNumb.js',
            'ver' => '1.0.2'
        ),
        'owlCarousel' => array(
            'js'   => TEMPLATE_URI . '/lib/owl-carousel/owl.carousel.min.js',
            'css'  => array(
                TEMPLATE_URI . '/lib/owl-carousel/owl.carousel.css',
                TEMPLATE_URI . '/lib/owl-carousel/owl.theme.css'
            ),
            'ver'  => '1.3.3',
            'deps' => array('jquery'),
        ),
        'parallax.js' => array(
            'js'   => TEMPLATE_URI . '/lib/parallax.js/parallax.min.js',
            'ver'  => '1.4.2',
            'deps' => array('jquery'),
        ),
        'fontawesome' => array(
            'css' => TEMPLATE_URI . '/lib/fontawesome/css/font-awesome.min.css',
            'ver' => '4.7.0'
        ),
        'default' => array(
            'css'  => TEMPLATE_URI . '/lib/wpbp/css/default.css'
        ),
        'wpbp' => array(
            'js'   => TEMPLATE_URI . '/js/wpbp.js',
            'css'  => TEMPLATE_URI . '/css/wpbp.css',
            'deps' => array('jquery'),
            'ver'  => '3.1.5'
        ),
        'wp-meta' => array(
            'css'  => THEME_URI . '/style.css'
        ),
        'js-cookie' => array(
            'js' => TEMPLATE_URI . '/lib/js-cookie/js.cookie.min.js',
            'ver' => '2.2.1'
        )
    );

    return isset($which, $wpbp_lib[$which]) ? $wpbp_lib[$which] : $wpbp_lib;
}
