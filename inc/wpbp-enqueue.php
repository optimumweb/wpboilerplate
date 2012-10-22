<?php

$wpbp_libs = array(
  'jquery' => array(
    'js'   => TEMPLATE_URI . '/libs/jquery/jquery.min.js',
    'ver'  => '1.7.2'
  ),
  'jquery-ui' => array(
    'js'   => TEMPLATE_URI . '/libs/jquery-ui/jquery-ui.min.js',
    'css'  => TEMPLATE_URI . '/libs/jquery-ui/css/base/jquery.ui.all.css',
    'ver'  => '1.8.22'
  ),
  'jquery-ui-smoothness' => array(
    'css'  => TEMPLATE_URI . '/libs/jquery-ui/css/smoothness/jquery-ui.css'
  ),
  'jquery-ui-lightness' => array(
    'css'  => TEMPLATE_URI . '/libs/jquery-ui/css/ui-lightness/jquery-ui.css'
  ),
  'jquery-ui-darkness' => array(
    'css'  => TEMPLATE_URI . '/libs/jquery-ui/css/ui-darkness/jquery-ui.css'
  ),
  'jquery-ui-redmond' => array(
    'css'  => TEMPLATE_URI . '/libs/jquery-ui/css/redmond/jquery-ui.css'
  ),
  'jquery-ui-blitzer' => array(
    'css'  => TEMPLATE_URI . '/libs/jquery-ui/css/blitzer/jquery-ui.css'
  ),
  '960gs' => array(
    'css'  => TEMPLATE_URI . '/libs/960gs/960.min.css'
  ),
  'scrollTo' => array(
    'js'   => TEMPLATE_URI . '/libs/scrollTo/jquery.scrollTo.min.js',
    'deps' => array('jquery'),
    'ver'  => '1.4.2'
  ),
  'cycle' => array(
    'js'   => TEMPLATE_URI . '/libs/cycle/jquery.cycle.min.js',
    'deps' => array('jquery'),
    'ver'  => '2.9998'
  ),
  'powerslide' => array(
    'js'   => TEMPLATE_URI . '/libs/powerslide/js/powerslide.min.js',
    'css'  => TEMPLATE_URI . '/libs/powerslide/css/powerslide.css',
    'deps' => array('jquery'),
    'ver'  => '1.1'
  ),
  'lightbox' => array(
    'js'   => TEMPLATE_URI . '/libs/lightbox/js/lightbox.min.js',
    'css'  => TEMPLATE_URI . '/libs/lightbox/css/lightbox.min.css',
    'deps' => array('jquery'),
    'ver'  => '2.51'
  ),
  'hoverIntent' => array(
    'js'   => TEMPLATE_URI . '/libs/hoverIntent/jquery.hoverIntent.min.js',
    'deps' => array('jquery'),
    'ver'  => '6.0'
  ),
  'flexslider' => array(
    'js'   => TEMPLATE_URI . '/libs/flexslider/jquery.flexslider.min.js',
    'css'  => TEMPLATE_URI . '/libs/flexslider/flexslider.css',
    'deps' => array('jquery'),
    'ver'  => '2.1'
  ),
  'bootstrap' => array(
    'js'   => TEMPLATE_URI . '/libs/bootstrap/js/bootstrap.min.js',
    'css'  => TEMPLATE_URI . '/libs/bootstrap/css/bootstrap.min.css',
    'deps' => array('jquery'),
    'ver'  => '2.0.4'
  ),
  'bootstrap-responsive' => array(
    'css'  => TEMPLATE_URI . '/libs/bootstrap/css/bootstrap-responsive.min.css'
  ),
  'ext-core' => array(
    'js'   => 'https://ajax.googleapis.com/ajax/libs/ext-core/3.1.0/ext-core.js',
    'ver'  => '3.1.0'
  ),
  'dojo' => array(
    'js'   => 'https://ajax.googleapis.com/ajax/libs/dojo/1.6.1/dojo/dojo.xd.js',
    'ver'  => '1.6.1'
  ),
  'mootools' => array(
    'js'   => 'https://ajax.googleapis.com/ajax/libs/mootools/1.4.1/mootools-yui-compressed.js',
    'ver'  => '1.4.1'
  ),
  'modernizr' => array(
    'js'   => TEMPLATE_URI . '/libs/modernizr/modernizr.min.js',
    'ver'  => '2.6.2'
  ),
  'lesscss' => array(
    'js'   => TEMPLATE_URI . '/libs/less/less.min.js',
    'ver'  => '1.2.1'
  ),
  'sizzle' => array(
    'js'   => TEMPLATE_URI . '/libs/sizzle/sizzle.min.js',
    'ver'  => '1.5.1'
  ),
  'highcharts' => array(
    'js'   => TEMPLATE_URI . '/libs/highcharts/highcharts.min.js',
    'ver'  => '2.1.9'
  ),
  'default' => array(
    'css'  => TEMPLATE_URI . '/libs/wpbp/css/default.css'
  ),
  'wpbp' => array(
    'js'   => TEMPLATE_URI . '/js/wpbp.min.js',
    'css'  => TEMPLATE_URI . '/css/wpbp.css',
    'deps' => array('jquery'),
    'ver'  => '3.1.0'
  ),
  'wp-meta' => array(
    'css'  => THEME_URI . '/style.css'
  )
);

function wpbp_register_libs()
{
	if ( is_admin() ) return;

	global $wpbp_libs;

  foreach ( $wpbp_libs as $handle => $lib ) {
    if ( wpbp_is_valid_var( $lib['js'] ) ) {
      wpbp_register_script($handle, $lib['js'], $lib['deps'], $lib['ver'], $lib['in_footer']);
    }
    if ( wpbp_is_valid_var( $lib['css'] ) ) {
      wpbp_register_style($handle, $lib['css']);
    }
  }

  if ( wpbp_get_option('js_files') ) {
    foreach ( ( preg_split('/\r\n|\r|\n/', wpbp_get_option('js_files')) ) as $js_file ) {
      wpbp_add_script( pathinfo($js_file, PATHINFO_FILENAME), $js_file );
    }
  }

  if ( wpbp_get_option('css_files') ) {
    foreach ( ( preg_split('/\r\n|\r|\n/', wpbp_get_option('css_files')) ) as $css_file ) {
      wpbp_add_style( pathinfo($css_file, PATHINFO_FILENAME), $css_file );
    }
  }

	return;
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

function wpbp_enqueue_scripts( $scripts = array() )
{
	if ( is_array( $scripts ) ) {
		foreach ( $scripts as $handle ) {
			wp_enqueue_script($handle);
		}
	}
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

function wpbp_enqueue_styles( $styles = array() )
{
	if ( is_array( $styles ) ) {
		foreach ( $styles as $handle ) {
			wp_enqueue_style($handle);
		}
	}
}
