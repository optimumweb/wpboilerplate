.min.js');

		if ( $wpbp_options['js_plugins']['formalize'] ) {
			$scripts .= script_tag('http://firecdn.net/libs/formalize/js/jquery.formalize.js');
		}

		if ( $wpbp_options['js_plugins']['ajax-mail'] ) {
			$scripts .= script_tag( get_template_directory_uri() . '/plugins/ajax-mail/jquery.ajax-mail.js' );
		}

		if ( $wpbp_options['js_plugins']['jSlider'] ) {
			$scripts .= script_tag('http://firecdn.net/libs/jSlider/jquery.jSlider.js');
		}
	}

	$scripts .= script_tag( get_stylesheet_directory_uri() . '/js/scripts.js' );

	echo $scripts;

	return;
}

function script_tag($args)
{
	extract( array_merge( array(
		'src'	=> ( is_string($args) ? $args : '' ),
		'type'	=> 'text/javascript'
	), ( is_array($args) ? $args : array() ) ) );
	return "<script type=\"" . $type . "\" src=\"" . $src . "\"></script>\n";
}

function wpbp_get_stylesheets()
{

	global $wpbp_options;

	$styles = "";

	$styles .= stylesheet_link_tag('http://firecdn.net/libs/960gs/960.min.css');

	if ( $wpbp_options['js_plugins']['formalize'] ) {
		$styles .= stylesheet_link_tag('http://firecdn.net/libs/formalize/css/formalize.css');
	}

	if ( $wpbp_options['js_plugins']['jSlider'] ) {
		$styles .= stylesheet_link_tag('http://firecdn.net/libs/jSlider/jSlider.css');
	}

	$styles .= stylesheet_link_tag( get_template_directory_uri() . "/css/default.css" );

	if ( $wpbp_options['js_plugins']['lesscss'] ) {
		$styles .= stylesheet_link_tag( array( 'href' => get_stylesheet_directory_uri() . "/css/custom.less", 'rel' => 'stylesheet/less' ) );
	} else {
		$styles .= stylesheet_link_tag( get_stylesheet_directory_uri() . "/css/custom.css" );
	}

	$styles .= stylesheet_link_tag( get_stylesheet_directory_uri() . "/style.css" );

	echo $styles;

	return;
}

function stylesheet_link_tag($args)
{
	extract( array_merge( array(
		'href'	=> ( is_string($args) ? $args : '' ),
		'rel'	=> 'stylesheet',
		'media'	=> 'all',
		'type'	=> 'text/css'
	), ( is_array($args) ? $args : array() ) ) );

	return "<link rel=\"" . $rel . "\" href=\"" . $href . "\" type=\"" . $type . "\" media=\"" . $media . "\" />\n";
}

function wpbp_custom_css()
{
	global $wpbp_options;
?>
<style type="text/css">
<?php echo $wpbp_options['custom_css'] . "\n"; ?>
</style>
<?php
	return;
}

function wpbp_get_breadcrumb()
{
	wpbp_custom_breadcrumb();
}

function wpbp_count_view()
{
	global $wp_query;
	if ( is_single() && isset( $wp_query->post->ID ) ) {
		$post_ID = $wp_query->post->ID;
		$post_views = get_post_meta($post_ID, 'wpbp_post_views', true);
		$post_views = ( isset($post_views) ) ? $post_views + 1 : 1;
		update_post_meta($post_ID, 'wpbp_post_views', $post_views);
	}
}

?>