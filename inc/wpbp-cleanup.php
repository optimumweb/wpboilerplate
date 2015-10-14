<?php

// redirect /?s to /search/
// http://txfx.net/wordpress-plugins/nice-search/
function wpbp_nice_search_redirect()
{
	if ( is_search() && strpos($_SERVER['REQUEST_URI'], '/wp-admin/') === false && strpos($_SERVER['REQUEST_URI'], '/search/') === false ) {
		wp_redirect(home_url('/search/' . str_replace(array(' ', '%20'), array('+', '+'), urlencode(get_query_var( 's' )))), 301);
	    exit();
	}
}
add_action('template_redirect', 'wpbp_nice_search_redirect');

function wpbp_search_query($escaped = true)
{
	$query = apply_filters('wpbp_search_query', get_query_var('s'));

	if ( $escaped ) {
    	$query = esc_attr($query);
	}

 	return urldecode($query);
}
add_filter('get_search_query', 'wpbp_search_query');

// root relative URLs for everything
// inspired by http://www.456bereastreet.com/archive/201010/how_to_make_wordpress_urls_root_relative/
// thanks to Scott Walkinshaw (scottwalkinshaw.com)
function wpbp_root_relative_url($input)
{
	$output = preg_replace_callback(
		'/(https?:\/\/[^\/|"]+)([^"]+)?/',
		create_function(
			'$matches',
			// if full URL is site_url, return a slash for relative root
			'if (isset($matches[0]) && $matches[0] === site_url()) { return "/";' .
			// if domain is equal to site_url, then make URL relative 
			'} elseif (isset($matches[0]) && strpos($matches[0], site_url()) !== false) { return $matches[2];' .
			// if domain is not equal to site_url, do not make external link relative
			'} else { return $matches[0]; };'
		),
		$input
	);

	return $output;
}

if ( !is_admin() ) {
	add_filter('bloginfo_url', 'wpbp_root_relative_url');
	add_filter('theme_root_uri', 'wpbp_root_relative_url');
	add_filter('stylesheet_directory_uri', 'wpbp_root_relative_url');
	add_filter('template_directory_uri', 'wpbp_root_relative_url');
	add_filter('plugins_url', 'wpbp_root_relative_url');
	add_filter('the_permalink', 'wpbp_root_relative_url');
	add_filter('wp_list_pages', 'wpbp_root_relative_url');
	add_filter('wp_list_categories', 'wpbp_root_relative_url');
	add_filter('wp_nav_menu', 'wpbp_root_relative_url');
	add_filter('the_content_more_link', 'wpbp_root_relative_url');
	add_filter('the_tags', 'wpbp_root_relative_url');
	add_filter('get_pagenum_link', 'wpbp_root_relative_url');
	add_filter('get_comment_link', 'wpbp_root_relative_url');
	add_filter('month_link', 'wpbp_root_relative_url');
	add_filter('day_link', 'wpbp_root_relative_url');
	add_filter('year_link', 'wpbp_root_relative_url');
	add_filter('tag_link', 'wpbp_root_relative_url');
	add_filter('the_author_posts_link', 'wpbp_root_relative_url');
    add_filter('widget_text', 'do_shortcode');
}

// remove root relative URLs on any attachments in the feed
function wpbp_root_relative_attachment_urls()
{
	if ( !is_feed() ) {
		add_filter('wp_get_attachment_url', 'wpbp_root_relative_url');
		add_filter('wp_get_attachment_link', 'wpbp_root_relative_url');
	}
}

add_action('pre_get_posts', 'wpbp_root_relative_attachment_urls');

// remove dir and set lang="en" as default (rather than en-US)
function wpbp_language_attributes()
{
	$attributes = array();
	$output = '';
	$lang = get_bloginfo('language');

	if ( $lang && $lang !== 'en-US' ) {
		$attributes[] = "lang=\"$lang\"";
	} else {
		$attributes[] = 'lang="en"';
	}

	$output = implode(' ', $attributes);
	$output = apply_filters('wpbp_language_attributes', $output);

	return $output;
}
add_filter('language_attributes', 'wpbp_language_attributes');

// remove WordPress version from RSS feed
function wpbp_no_generator() { return ''; }
add_filter('the_generator', 'wpbp_no_generator');

// cleanup wp_head
function wpbp_noindex()
{
	if ( get_option('blog_public') === '0' ) {
		echo '<meta name="robots" content="noindex,nofollow">', "\n";
	}
}	

function wpbp_rel_canonical()
{
	if ( !is_singular() ) {
		return;
	}

	global $wp_the_query;

	if ( !$id = $wp_the_query->get_queried_object_id() ) {
		return;
	}

	$link = get_permalink($id);

	echo "<link rel=\"canonical\" href=\"$link\">\n";
}

// remove CSS from recent comments widget
function wpbp_remove_recent_comments_style()
{
	global $wp_widget_factory;

	if ( isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments']) ) {
		remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
	}
}

// remove CSS from gallery
function wpbp_gallery_style($css)
{
	return preg_replace("/<style type='text\/css'>(.*?)<\/style>/s", '', $css);
}

function wpbp_head_cleanup()
{
	// http://wpengineer.com/1438/wordpress-header/
	remove_action('wp_head', 'feed_links', 2);
	remove_action('wp_head', 'feed_links_extra', 3);
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'index_rel_link');
	remove_action('wp_head', 'parent_post_rel_link', 10, 0);
	remove_action('wp_head', 'start_post_rel_link', 10, 0);
	remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
	remove_action('wp_head', 'wp_generator');
	remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
	remove_action('wp_head', 'noindex', 1);	
	add_action('wp_head', 'wpbp_noindex');
	remove_action('wp_head', 'rel_canonical');	
	//add_action('wp_head', 'wpbp_rel_canonical');
	add_action('wp_head', 'wpbp_remove_recent_comments_style', 1);
	add_filter('gallery_style', 'wpbp_gallery_style');
	
	// stop Gravity Forms from outputting CSS since it's linked in header.php
	if ( class_exists('RGForms') ) {
		update_option('rg_gforms_disable_css', 1);
	}

	// deregister l10n.js (new since WordPress 3.1)
	// why you might want to keep it: http://wordpress.stackexchange.com/questions/5451/what-does-l10n-js-do-in-wordpress-3-1-and-how-do-i-remove-it/5484#5484
	// don't load jQuery through WordPress since it's linked in header.php
	if ( !is_admin() ) {
		wp_deregister_script('l10n');
		wp_deregister_script('jquery');
		wp_register_script('jquery', '', '', '', true);
	}	
}
add_action('init', 'wpbp_head_cleanup');

// cleanup gallery_shortcode()
function wpbp_gallery_shortcode($attr)
{
	global $post, $wp_locale;

	static $instance = 0;
	$instance++;

	// Allow plugins/themes to override the default gallery template.
	$output = apply_filters('post_gallery', '', $attr);
	if ( $output != '' )
		return $output;

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset($attr['orderby']) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}

	extract(shortcode_atts(array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post->ID,
		'icontag'    => 'figure',
		'captiontag' => 'figcaption',
		'columns'    => 3,
		'size'       => 'thumbnail',
		'include'    => '',
		'exclude'    => ''
	), $attr));

	$id = intval($id);
	if ( 'RAND' == $order )
		$orderby = 'none';

	if ( !empty($include) ) {
		$include = preg_replace( '/[^0-9,]+/', '', $include );
		$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( !empty($exclude) ) {
		$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
		$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	} else {
		$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	}

	if ( empty($attachments) )
		return '';

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment )
			$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
		return $output;
	}

	$captiontag = tag_escape($captiontag);
	$columns = intval($columns);
	$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
	$float = is_rtl() ? 'right' : 'left';

	$selector = "gallery-{$instance}";

	$gallery_style = $gallery_div = '';
	if ( apply_filters( 'use_default_gallery_style', true ) ) {
		$gallery_style = "";
	}
	$size_class = sanitize_html_class( $size );
	$gallery_div = "<section id='$selector' class='clearfix gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";
	$output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );

	$i = 0;
	foreach ( $attachments as $id => $attachment ) {
		// make the gallery link to the file by default instead of the attachment
		// thanks to Matt Price (countingrows.com)

        $link = isset($attr['link']) && $attr['link'] === 'attachment' ? wp_get_attachment_link($id, $size, true, false) :  wp_get_attachment_link($id, $size, false, false);
        $output .= "<{$icontag} class=\"gallery-item\">$link";

		if ( $captiontag && trim($attachment->post_excerpt) ) {
			$output .= "<{$captiontag} class=\"gallery-caption\">" . wptexturize($attachment->post_excerpt) . "</{$captiontag}>";
		}

		$output .= "</{$icontag}>";

		if ( $columns > 0 && ++$i % $columns == 0 ) {
            $output .= '';
        }
	}

	$output .= "</section>\n";

	return $output;
}

remove_shortcode('gallery');
add_shortcode('gallery', 'wpbp_gallery_shortcode');

// http://www.deluxeblogtips.com/2011/01/remove-dashboard-widgets-in-wordpress.html
function wpbp_remove_dashboard_widgets()
{
	remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
	remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
	remove_meta_box('dashboard_primary', 'dashboard', 'normal');
	remove_meta_box('dashboard_secondary', 'dashboard', 'normal');
}
add_action('admin_init', 'wpbp_remove_dashboard_widgets');

// excerpt cleanup
function wpbp_excerpt_length($length) {
	return 40;
}

function wpbp_excerpt_more($more)
{
	return ' &hellip; <a href="' . get_permalink() . '">' . __( 'Continued', 'wpbp' ) . '</a>';
}
add_filter('excerpt_length', 'wpbp_excerpt_length');
add_filter('excerpt_more', 'wpbp_excerpt_more');

// remove container from menus
function wpbp_nav_menu_args($args = '')
{
	$args['container'] = false;
	return $args;
}
add_filter('wp_nav_menu_args', 'wpbp_nav_menu_args');

class wpbp_nav_walker extends Walker_Nav_Menu
{
	function start_el(&$output, $item, $depth, $args)
    {
		global $wp_query;
	    $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

	    $slug = sanitize_title($item->title);

	    $class_names = $value = '';
	    $classes = empty( $item->classes ) ? array() : (array) $item->classes;

	    $classes = array_filter($classes, 'wpbp_check_current');

	    $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
	    $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

	    $id = apply_filters( 'nav_menu_item_id', 'menu-' . $slug, $item, $args );
	    $id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';

	    $output .= $indent . '<li' . $id . $class_names . '>';

	    $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
	    $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
	    $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
	    $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

	    $item_output = $args->before;
	    $item_output .= '<a'. $attributes .'>';
	    $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
	    $item_output .= '</a>';
	    $item_output .= $args->after;

	    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}

function wpbp_check_current($val)
{
	return preg_match('/current-menu/', $val);
}

// add to robots.txt
// http://codex.wordpress.org/Search_Engine_Optimization_for_WordPress#Robots.txt_Optimization
function wpbp_robots()
{
	echo "Disallow: /cgi-bin\n";
	echo "Disallow: /wp-admin\n";
	echo "Disallow: /wp-includes\n";
	echo "Disallow: /wp-content/plugins\n";
	echo "Disallow: /plugins\n";
	echo "Disallow: /wp-content/cache\n";
	echo "Disallow: /wp-content/themes\n";
	echo "Disallow: /trackback\n";
	echo "Disallow: /feed\n";
	echo "Disallow: /comments\n";
	echo "Disallow: /category/*/*\n";
	echo "Disallow: */trackback\n";
	echo "Disallow: */feed\n";
	echo "Disallow: */comments\n";
	echo "Disallow: /*?*\n";
	echo "Disallow: /*?\n";
	echo "Allow: /wp-content/uploads\n";
	echo "Allow: /assets";
}
add_action('do_robots', 'wpbp_robots');

// http://www.google.com/support/webmasters/bin/answer.py?answer=1229920
function wpbp_author_link($link)
{
	return str_replace('<a ', '<a class="fn" rel="author"', $link);
}
add_filter('the_author_posts_link', 'wpbp_author_link');

// we don't need to self-close these tags in html5:
// <img>, <input>
function wpbp_remove_self_closing_tags($input)
{
	return str_replace(' />', '>', $input);
}
add_filter('get_avatar', 'wpbp_remove_self_closing_tags');
add_filter('comment_id_fields', 'wpbp_remove_self_closing_tags');

// check to see if the tagline is set to default
// show an admin notice to update if it hasn't been changed
// you want to change this or remove it because it's used as the description in the RSS feed
function wpbp_notice_tagline()
{
	echo '<div class="error">';
	echo '<p>' . sprintf(__('Please update your <a href="%s">site tagline</a>', 'wpbp'), admin_url('options-general.php')) . '</p>';
	echo '</div>';
}

if ( get_option('blogdescription') === 'Just another WordPress site' ) {
	add_action('admin_notices', 'wpbp_notice_tagline');
}

// WPML fixes
// prevent loading WPML css & js
if ( !defined('ICL_DONT_LOAD_NAVIGATION_CSS') ) define('ICL_DONT_LOAD_NAVIGATION_CSS', true);
if ( !defined('ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS') ) define('ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS', true);
if ( !defined('ICL_DONT_LOAD_LANGUAGES_JS') ) define('ICL_DONT_LOAD_LANGUAGES_JS', true);

// set the post revisions to 5 unless the constant
// was set in wp-config.php to avoid DB bloat
if ( !defined('WP_POST_REVISIONS') ) define('WP_POST_REVISIONS', 5);

// allow more tags in TinyMCE including iframes
function wpbp_change_mce_options($options)
{
	$ext = 'pre[id|name|class|style],iframe[align|longdesc|name|width|height|frameborder|scrolling|marginheight|marginwidth|src]';

	if ( isset($initArray['extended_valid_elements']) ) {
		$options['extended_valid_elements'] .= ',' . $ext;
	} else {
		$options['extended_valid_elements'] = $ext;
	}

	return $options;
}
add_filter('tiny_mce_before_init', 'wpbp_change_mce_options');

function remove_stupid_br($content)
{
    global $shortcode_tags;
    $shortcode_handles = array_keys($shortcode_tags);
    $block = join("|", $shortcode_handles);
    $rep = preg_replace("/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>)?/","[$2$3]", $content);
    $rep = preg_replace("/(<p>)?\[\/($block)](<\/p>|<br \/>)?/","[/$2]", $rep);
    return $rep;
}
add_filter("the_content", "remove_stupid_br");