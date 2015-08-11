<?php

/**
 * Clean up shortcode $content
 */

function parse_shortcode_content($content, $options = array())
{
	extract( array_merge( array(
        'unautop'      => true,
		'wpautop'      => false,
		'do_shortcode' => true,
		'trim'         => true,
	), $options ) );

    if ( $unautop )
        $content = shortcode_unautop( $content );

    if ( $wpautop )
        $content = wpautop( $content );

	if ( $do_shortcode )
		$content = do_shortcode( $content );
		
	if ( $trim )
		$content = trim( $content );

	// Remove '</p>' from the start of the string.
	if ( substr( $content, 0, 4 ) == '</p>' )
		$content = substr( $content, 4 );

	// Remove '<p>' from the end of the string.
	if ( substr( $content, -3, 3 ) == '<p>' )
		$content = substr( $content, 0, -3 );

	// Remove any instances of '<p></p>'.
	$content = str_replace( array( '<p></p>' ), '', $content );
    $content = str_replace( array( '<p> </p>' ), '', $content );

	if ( $do_shortcode )
		$content = do_shortcode( $content );

	return $content;
}

// [the_title post_id="123"]
function wpbp_the_title($atts)
{
    extract(shortcode_atts(array( 'post_id' => null ), $atts));
    if ( !isset($post_id) ) set_post_ID($post_id);
    return get_the_title($post_id);
}
add_shortcode('the_title', 'wpbp_the_title');

// [section id="section-id" class="section-class"]...[/section]
function wpbp_section($atts, $content = null)
{
    extract(shortcode_atts(array(
        'id'    => '',
        'class' => ''
    ), $atts));
    return '<section id="' . $id . '" class="' . $class . '">' . parse_shortcode_content($content) . '</section>';
}
add_shortcode('section', 'wpbp_section');

// [grid cols="6"]...[/grid]
function wpbp_grid($atts, $content = null)
{
	extract(shortcode_atts(array(
		'cols' => '1'
	), $atts));
	return '<div class="grid_' . $cols . '">' . parse_shortcode_content($content) . '</div>';
}
add_shortcode('grid', 'wpbp');

// [clear]
function wpbp_clear()
{
	return '<div class="clear"></div>';
}
add_shortcode('clear', 'wpbp_clear');

// [hr]
function wpbp_hr()
{
	return '<hr />';
}
add_shortcode('hr', 'wpbp_hr');


function wpbp_recent_posts($atts = array())
{
    $params = shortcode_atts(array(
        'posts_per_page' => 6,
        'offset'         => null,
        'category'       => null,
        'category_name'  => null,
        'orderby'        => null,
        'order'          => null,
        'include'        => null,
        'exclude'        => array( get_post_id() ),
        'meta_key'       => null,
        'meta_value'     => null,
        'post_type'      => null,
        'tax_query'      => null,
        'post_template'  => null,
        'no_results'     => __("Aucuns résultats", 'wpbp')
    ), $atts);

    if ( isset($params['tax_query']) ) {
        $tq = explode("=", $params['tax_query']);
        $params['tax_query'] = array( array(
            'taxonomy' => $tq[0],
            'field'    => 'slug',
            'terms'    => $tq[1]
        ) );
    }

    $post_template_path = THEME_DIRECTORY . '/' . $params['post_template'] . '.php';

    ob_start();

    $query = new WP_Query($params);

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            include($post_template_path);
        }
    } else {
        echo '<div class="no-results">' . $params['no_results'] . '</div>';
    }

    return ob_get_clean();
}
add_shortcode('wpbp_recent_posts', 'wpbp_recent_posts');

/**
 * SMARTBOX
 */

function wpbp_smartbox($atts, $content = null)
{
    extract( shortcode_atts( array(
        'id'          => '',
        'class'       => '',
        'title'       => '',
        'title_tag'   => 'h3',
        'href'        => false,
        'target'      => '_self',
        'small'       => false,
        'collapsible' => false,
        'closed'      => false,
        'sliding'     => false,
        'ajax'        => false,
        'src'         => false,
        'lazy'        => false
    ), $atts ) );

    if ( !$id ) $id = sanitize_title($title);

    // define box #id
    $id = ' id="' . $id . '"';
    
    // define box .class
    $class = 'smartbox ' . $class;
    if ( $sliding )		$class .= ' sliding';
    if ( $collapsible )	$class .= ' collapsible';
    if ( $closed )		$class .= ' closed';
    if ( $ajax )		$class .= ' ajax';
    if ( $lazy )		$class .= ' lazy';
    $class = ' class="' . preg_replace('/\s+/', ' ', $class) . '"';
    
    // define box data
    $data = '';
    if ( $src ) $data .= ' data-src="' . $src . '"';
    
    if ( $src && !$ajax ) {
    	$src_ID		= get_ID_by_slug( $src );
    	if ( $src_ID && get_post( $src_ID ) ) {
			$title		= wpbp_first_valid( $title, get_the_title( $src_ID ) );
			$content	= wpbp_first_valid( $content, get_post_field( 'post_content', $src_ID ) );
    	}
    	else {
    		$title = 'Invalid Post';
    		$content = '<p>Cannot find post with slug "' . $src . '".</p>';
    	}
    }
    
    $box = '<div' . $id . $class . $data . '>';
    
	if ( isset($title) && strlen($title) > 0 ) {
		$box .= '<div class="box-title title">';
        if ( $title_tag ) $box .= '<' . $title_tag . '>';
        if ( $href || ( $src && $ajax ) ) {
            if ( $href ) $box .= '<a href="' . $href . '" target="' . $target . '">' . $title . '</a>';
            elseif ( $src && $ajax ) $box .= '<a class="ajax-trigger" href="' . $src . '">' . $title . '</a>';
        }
		else $box .= $title;
        if ( $title_tag ) $box .= '</' . $title_tag . '>';
		$box .= '</div>';
	}
		
	$box .= '<div class="box-content content">';
	$box .= parse_shortcode_content($content);
	$box .= '<div class="clear"></div></div>';
    
    if ( $sliding ) {
    	$box .= '<div class="box-controls">';
    	$box .= '<a class="box-arrow box-prev" href="#prev"><span></span></a>';
    	$box .= '<a class="box-arrow box-next" href="#next"><span></span></a>';
    	$box .= '</div>';
    }
    
    if ( $collapsible ) {
    	$box .= '<div class="box-controls">';
    	$box .= '<a class="box-arrow collapse-trigger' . ( $ajax ? ' ajax-trigger' : '' ) . '" href="#"><span></span></a>';
    	$box .= '</div>';
    }
    
    $box .= '<div class="clear"></div>';
    $box .= '</div>';
    
    return $box;
}
add_shortcode('smartbox', 'wpbp_smartbox');

// [article-header]...[/article-header]
function wpbp_article_header($atts = null, $content = null)
{
	return '<div class="article-header">' . parse_shortcode_content($content) . '</div>';
}
add_shortcode("article-header", "wpbp_article_header");

/**
 * Plugin Name: Show Menu Shortcode
 * Plugin URI: http://www.mokamedianyc.com/dev/show-menu-shortcode/
 * Description: Provides a [show-menu] <a href="http://codex.wordpress.org/Shortcode_API">shortcodes</a> for displaying a menu within a post or page.  The shortcode accepts most parameters that you can pass to the <a href="http://codex.wordpress.org/Template_Tags/wp_nav_menu">wp_nav_menu()</a> function.  To show a menu, add [show-menu menu="Main-menu"] in the page or post body.
 * Author: Bob Matsuoka
 * Version: 1.0
 * Author URI: http://www.mokamedianyc.com
*/

function shortcode_show_menu($atts, $content, $tag)
{	
	global $post;
	
	// Set defaults
	$defaults = array(
		'menu'        	  => '',
		'container'       => 'div', 
		'container_class' => 'menu-container', 
		'container_id'    => '',
		'menu_class'      => 'menu', 
		'menu_id'         => '',
		'fallback_cb'     => 'wp_page_menu',
		'before'          => '',
		'after'           => '',
		'link_before'     => '',
		'link_after'      => '',
		'depth'			  => 0,
		'echo' 			  => false
	);
	
	// Merge user provided atts with defaults
	$atts = shortcode_atts($defaults, $atts);
	
	// Create output
	$out = wp_nav_menu($atts);
	
	return apply_filters('shortcode_show_menu', $out, $atts, $content, $tag);
	
}

add_shortcode('show-menu', 'shortcode_show_menu');

// [paypal type="buy now" amount="12.99" business="me@mybusiness.com" currency="USD" item_name="Teddy Bear" src="http://www.paypal.com/en_US/i/btn/btn_buynow_LG.gif" target="_blank"]
function wpbp_paypal($atts, $content = null)
{
    extract(shortcode_atts(array(
        'target'    => '_self',
        'type'      => 'buy_now',
		'amount'    => '0.00',
        'business'  => '',
        'currency'  => 'USD',
        'item_name' => '',
        'src'       => 'http://www.paypal.com/en_US/i/btn/btn_buynow_LG.gif'
	), $atts));
    
    ob_start();
    
    if ( $type == 'buy_now' ) :
?><form name="_xclick" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="<?php echo $target; ?>">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="<?php echo $business; ?>">
<input type="hidden" name="currency_code" value="<?php echo $currency; ?>">
<input type="hidden" name="item_name" value="<?php echo $item_name; ?>">
<input type="hidden" name="amount" value="<?php echo $amount; ?>">
<input type="image" src="<?php echo $src; ?>" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
</form><?php
    elseif ( $type == 'add_to_cart' ) :
?><form name="_cart" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="paypal">
<input type="hidden" name="cmd" value="_cart">
<input type="hidden" name="add" value="1">
<input type="hidden" name="business" value="<?php echo $business; ?>">
<input type="hidden" name="currency_code" value="<?php echo $currency; ?>">
<input type="hidden" name="item_name" value="<?php echo $item_name; ?>">
<input type="hidden" name="amount" value="<?php echo $amount; ?>">
<input type="image" src="<?php echo $src; ?>" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
</form><?php   
    endif;

    $paypal = ob_get_clean();
    return parse_shortcode_content($paypal);
}
add_shortcode('paypal', 'wpbp_paypal');

function wpbp_responsive_embed($atts)
{
    extract(shortcode_atts(array(
        'ratio'       => '16x9',
        'type'        => 'iframe',
        'src'         => '',
        'frameborder' => 0
    ), $atts));

    if ( isset($src) && strlen($src) > 0 ) {
        $ratio = explode('x', $ratio);
        $ratio = $ratio[1] / $ratio[0];
        $padding_bottom = ( $ratio * 100 ) . '%';

        return '<div class="embed-container" style="padding-bottom:'. $padding_bottom .';"><'. $type .' src="'. $src .'" frameborder="'. $frameborder .'" allowfullscreen></'. $type .'></div>';
    }
}
add_shortcode('responsive_embed', 'wpbp_responsive_embed');

function list_term_children($term_id, $taxonomies, $args) {
    $children = get_terms( $taxonomies, array_merge( $args, array( 'parent' => $term_id ) ) );
    if ( count($children) > 0 ) {
        echo '<ul>';
        foreach ( $children as $child ) {
            echo '<li>';
            echo '<a href="' . get_term_link($child) . '">' . $child->name . '</a>';
            list_term_children($child->term_id, $taxonomies, $args);
            echo '</li>';
        }
        echo '</ul>';
    }
}

function wpbp_taxonomy_list($atts)
{
    extract(shortcode_atts(array(
        'tax'        => 'post_tag',
        'orderby'    => 'name',
        'hide_empty' => false
    ), $atts));

    $taxonomies = array_map("trim", explode(",", $tax));
    $args = array( 'orderby' => $orderby, 'hide_empty' => $hide_empty );

    ob_start();
    list_term_children(0, $taxonomies, $args);
    return ob_get_clean();
}
add_shortcode('taxonomy_list', 'wpbp_taxonomy_list');

function wpbp_spacer($atts)
{
    extract(shortcode_atts(array(
        'size' => 'medium',
    ), $atts));

    switch ($size) {
        case 'small':
            $height = "0.5em";
            break;
        case 'medium':
            $height = "1em";
            break;
        case 'large':
            $height = "2em";
            break;
        default:
            $height = $size;
            break;
    }

    return '<div style="height: ' . $height . '"></div>';
}
add_shortcode('spacer', 'wpbp_spacer');