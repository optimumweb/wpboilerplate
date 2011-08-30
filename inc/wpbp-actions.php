<?php

add_action('wpbp_head', 'wpbp_og_tags');
add_action('wpbp_head', 'wpbp_google_analytics');
add_action('wpbp_head', 'wpbp_custom_css');
add_action('wpbp_stylesheets', 'wpbp_get_stylesheets');
add_action('wpbp_scripts', 'wpbp_get_scripts');
add_action('wpbp_breadcrumb', 'wpbp_get_breadcrumb');
add_action('wpbp_footer', 'wpbp_count_view');

function wpbp_google_analytics()
{
	global $wpbp_options;
	$wpbp_google_analytics_id = $wpbp_options['google_analytics_id'];
	$wpbp_get_google_analytics_id = esc_attr($wpbp_options['google_analytics_id']);
	if ($wpbp_google_analytics_id !== '') {
?>
<script type="text/javascript">
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', '<?php echo $wpbp_google_analytics_id; ?>']);
	_gaq.push(['_trackPageview']);
	_gaq.push(['_trackPageLoadTime']);
	(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
</script>
<?php
	}
	return;
}

function wpbp_og_tags()
{
	global $wp_query;

	if ( is_single() ) {
		$current_post = get_post( $wp_query->post->ID );
		$current_post_image = wpbp_get_post_image( $wp_query->post->ID );
	}

	$og = array(
		'title'			=> is_single() ? $current_post->post_title : wp_title('', false),
		'url'			=> 'http://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"],
		'image'			=> is_single() ? $current_post_image['url'] : false,
		'site_name'		=> get_bloginfo('name'),
		'description'	=> is_single() ? $current_post->post_excerpt : false,
	);

	foreach ( $og as $key => $val ) {
		echo "<meta property=\"og:" . $key . "\" content=\"" . $val . "\" />\n";
	}
	
	return;
}

function wpbp_get_scripts()
{

	global $wpbp_options;

	$scripts = "";

	if ( $wpbp_options['js_plugins']['modernizr'] ) {
		$scripts .= script_tag( get_template_directory_uri() . "/js/libs/modernizr-2.0.6.min.js" );
	}

	if ( $wpbp_options['js_plugins']['lesscss'] ) {
		$scripts .= script_tag( get_template_directory_uri() . "/js/libs/less-1.1.3.min.js" );
	}

	if ( $wpbp_options['js_plugins']['jquery'] ) {

		$scripts .= script_tag( get_template_directory_uri() . "/js/libs/jquery-1.6.2.min.js" );

		if ( $wpbp_options['js_plugins']['formalize'] ) {
			$scripts .= script_tag( get_template_directory_uri() . "/js/plugins/jquery.formalize.min.js" );
		}

		if ( $wpbp_options['js_plugins']['php-jquery-ajax-mail'] ) {
			$scripts .= script_tag( get_template_directory_uri() . "/js/plugins/jquery.mail.min.js" );
		}
	}

	$scripts .= script_tag( get_stylesheet_directory_uri() . "/js/scripts.js" );

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

	$styles .= stylesheet_link_tag( get_template_directory_uri() . "/css/960/960.min.css" );

	if ( $wpbp_options['js_plugins']['formalize'] ) {
		$styles .= stylesheet_link_tag( get_template_directory_uri() . "/css/formalize/formalize.min.css" );
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