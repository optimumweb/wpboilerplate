<?php

function wpbp_get_lib($which = null)
{
    $wpbp_lib = [
        'default' => [
            'css' => [
                'src' => TEMPLATE_URI . '/lib/wpbp/css/default.css'
            ]
        ],
        'wpbp' => [
            'js' => [
                'src' => TEMPLATE_URI . '/js/wpbp.js',
                'deps' => [ 'jquery' ],
            ],
            'css' => [
                'src' => TEMPLATE_URI . '/css/wpbp.css'
            ],
            'ver' => '3.1.7'
        ],
        'jquery' => [
            'js' => [
                'src' => TEMPLATE_URI . '/lib/jquery/jquery.min.js'
            ],
            'ver' => '3.3.1'
        ],
        'jquery-ui' => [
            'js' => [
                'src' => TEMPLATE_URI . '/lib/jquery-ui/jquery-ui.min.js'
            ],
            'css' => [
                'src' => TEMPLATE_URI . '/lib/jquery-ui/css/base/jquery.ui.all.css'
            ],
            'ver' => '1.12.1'
        ],
        'jquery-ui-smoothness' => [
            'css' => [
                'src' => TEMPLATE_URI . '/lib/jquery-ui/css/smoothness/jquery-ui.css'
            ]
        ],
        'jquery-ui-lightness' => [
            'css' => [
                'src' => TEMPLATE_URI . '/lib/jquery-ui/css/ui-lightness/jquery-ui.css'
            ]
        ],
        'jquery-ui-darkness' => [
            'css' => [
                'src' => TEMPLATE_URI . '/lib/jquery-ui/css/ui-darkness/jquery-ui.css'
            ]
        ],
        'jquery-ui-redmond' => [
            'css' => [
                'src' => TEMPLATE_URI . '/lib/jquery-ui/css/redmond/jquery-ui.css'
            ]
        ],
        'jquery-ui-blitzer' => [
            'css' => [
                'src' => TEMPLATE_URI . '/lib/jquery-ui/css/blitzer/jquery-ui.css'
            ]
        ],
        '960gs' => [
            'css' => [
                'src' => TEMPLATE_URI . '/lib/960gs/960.min.css'
            ]
        ],
        'scrollTo' => [
            'js' => [
                'src'  => TEMPLATE_URI . '/lib/scrollTo/jquery.scrollTo.min.js',
                'deps' => [ 'jquery' ]
            ],
            'ver' => '1.4.2'
        ],
        'cycle' => [
            'js' => [
                'src'  => TEMPLATE_URI . '/lib/cycle/jquery.cycle.min.js',
                'deps' => [ 'jquery' ]
            ],
            'ver' => '2.9998'
        ],
        'powerslide' => [
            'js' => [
                'src'  => TEMPLATE_URI . '/lib/powerslide/js/powerslide.min.js',
                'deps' => [ 'jquery' ],
            ],
            'css' => [
                'src' => TEMPLATE_URI . '/lib/powerslide/css/powerslide.css'
            ],
            'ver' => '1.1'
        ],
        'lightbox' => [
            'js' => [
                'src'  => TEMPLATE_URI . '/lib/lightbox/js/lightbox.min.js',
                'deps' => [ 'jquery' ],
            ],
            'css' => TEMPLATE_URI . '/lib/lightbox/css/lightbox.min.css',
            'ver' => '2.51'
        ],
        'hoverIntent' => [
            'js' => [
                'src' => TEMPLATE_URI . '/lib/hoverIntent/jquery.hoverIntent.min.js',
                'deps' => [ 'jquery' ]
            ],
            'ver' => '6.0'
        ],
        'flexslider' => [
            'js' => [
                'src' => TEMPLATE_URI . '/lib/flexslider/jquery.flexslider.min.js',
                'deps' => [ 'jquery' ]
            ],
            'css' => [
                'src' => TEMPLATE_URI . '/lib/flexslider/flexslider.css'
            ],
            'ver' => '2.1'
        ],
        'bootstrap' => [
            'js' => [
                'src'  => TEMPLATE_URI . '/lib/bootstrap/js/bootstrap.min.js',
                'deps' => [ 'jquery' ],
            ],
            'css' => [
                'src' => TEMPLATE_URI . '/lib/bootstrap/css/bootstrap.min.css'
            ],
            'ver' => '2.0.4'
        ],
        'bootstrap-responsive' => [
            'css' => [
                'src' => TEMPLATE_URI . '/lib/bootstrap/css/bootstrap-responsive.min.css'
            ]
        ],
        'ext-core' => [
            'js' => [
                'src' => 'https://ajax.googleapis.com/ajax/lib/ext-core/3.1.0/ext-core.js'
            ],
            'ver' => '3.1.0'
        ],
        'dojo' => [
            'js' => [
                'src' => 'https://ajax.googleapis.com/ajax/lib/dojo/1.6.1/dojo/dojo.xd.js'
            ],
            'ver' => '1.6.1'
        ],
        'mootools' => [
            'js' => [
                'src' => 'https://ajax.googleapis.com/ajax/lib/mootools/1.4.1/mootools-yui-compressed.js'
            ],
            'ver' => '1.4.1'
        ],
        'modernizr' => [
            'js' => [
                'src' => TEMPLATE_URI . '/lib/modernizr/2.8.3/modernizr.min.js',
            ],
            'ver' => '2.8.3'
        ],
        'lesscss' => [
            'js' => [
                'src' => TEMPLATE_URI . '/lib/less/less.min.js'
            ],
            'ver' => '1.2.1'
        ],
        'sizzle' => [
            'js' => [
                'src' => TEMPLATE_URI . '/lib/sizzle/sizzle.min.js'
            ],
            'ver' => '1.5.1'
        ],
        'highcharts' => [
            'js' => [
                'src' => TEMPLATE_URI . '/lib/highcharts/highcharts.min.js'
            ],
            'ver' => '2.1.9'
        ],
        'inview' => [
            'js' => [
                'src' => TEMPLATE_URI . '/lib/inview/jquery.inview.min.js'
            ],
            'ver' => '1.0'
        ],
        'jquery-cookie' => [
            'js' => [
                'src' => TEMPLATE_URI . '/lib/jquery-cookie/jquery.cookie.js'
            ],
            'ver' => '1.0'
        ],
        'tinyNav' => [
            'js' => [
                'src' => TEMPLATE_URI . '/lib/tinynav/tinynav.min.js'
            ],
            'ver' => '1.1'
        ],
        'galleria' => [
            'js' => [
                'src' => TEMPLATE_URI . '/lib/galleria/galleria-1.3.5.min.js'
            ],
            'ver' => '1.3.5'
        ],
        'gmap' => [
            'js' => [
                'src' => TEMPLATE_URI . '/lib/gmap/gmap.js'
            ],
            'ver' => '1.1'
        ],
        'animatedElement' => [
            'js' => [
                'src'  => TEMPLATE_URI . '/lib/animated-element/jquery.animatedElement.js',
                'deps' => [ 'jquery' ],
            ],
            'css' => [
                'src' => TEMPLATE_URI . '/lib/animated-element/animatedElement.css'
            ],
            'ver' => '1.0'
        ],
        'magnific-popup' => [
            'js' => [
                'src'  => TEMPLATE_URI . '/lib/magnific-popup/jquery.magnific-popup.min.js',
                'deps' => [ 'jquery' ]
            ],
            'css' => [
                'src' => TEMPLATE_URI . '/lib/magnific-popup/magnific-popup.css'
            ],
            'ver' => '1.0.0'
        ],
        'noUiSlider' => [
            'js' => [
                'src' => TEMPLATE_URI . '/lib/noUiSlider/nouislider.min.js'
            ],
            'css' => [
                'src' => [
                    TEMPLATE_URI . '/lib/noUiSlider/nouislider.min.css',
                    TEMPLATE_URI . '/lib/noUiSlider/nouislider.tooltips.css'
                ]
            ],
            'ver' => '8.2.1'
        ],
        'wNumb' => [
            'js' => [
                'src' => TEMPLATE_URI . '/lib/wnumb/wNumb.js'
            ],
            'ver' => '1.0.2'
        ],
        'owlCarousel' => [
            'js' => [
                'src'  => TEMPLATE_URI . '/lib/owl-carousel/owl.carousel.min.js',
                'deps' => [ 'jquery' ]
            ],
            'css' => [
                'src' => [
                    TEMPLATE_URI . '/lib/owl-carousel/owl.carousel.css',
                    TEMPLATE_URI . '/lib/owl-carousel/owl.theme.css'
                ]
            ],
            'ver' => '1.3.3'
        ],
        'parallax.js' => [
            'js' => [
                'src'  => TEMPLATE_URI . '/lib/parallax.js/parallax.min.js',
                'deps' => [ 'jquery' ]
            ],
            'ver' => '1.4.2'
        ],
        'fontawesome' => [
            'css' => [
                'src' => TEMPLATE_URI . '/lib/fontawesome/css/font-awesome.min.css'
            ],
            'ver' => '4.7.0'
        ],
        'wp-meta' => [
            'css' => [
                'src' => THEME_URI . '/style.css'
            ]
        ],
        'js-cookie' => [
            'js' => [
                'src' => TEMPLATE_URI . '/lib/js-cookie/js.cookie.min.js'
            ],
            'ver' => '2.2.1'
        ],
        'aos' => [
            'js' => [
                'src' => TEMPLATE_URI . '/lib/aos/2.3.4/dist/aos.js'
            ],
            'css' => [
                'src' => TEMPLATE_URI . '/lib/aos/2.3.4/dist/aos.css'
            ],
            'ver' => '2.3.4'
        ]
    ];

    return isset($which, $wpbp_lib[$which]) ? $wpbp_lib[$which] : $wpbp_lib;
}
