<?php

/**
 * Clean up shortcode $content
 */

function parse_shortcode_content($content, $options = array())
{
	extract( array_merge( array(
		'wpautop'		=> true,
		'do_shortcode'	=> true,
		'trim'			=> true,
	), $options ) );

	if ( $do_shortcode )
		$content = do_shortcode( $content );
		
	if ( $wpautop )
		$content = wpautop( $content );
		
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

	if ( $do_shortcode )
		$content = do_shortcode( $content );

	return $content;
}

// [container cols="12"]...[/container]
function container_960gs($atts, $content = null)
{
	extract(shortcode_atts(array(
		'cols' => '16'
	), $atts));
	return '<div class="container_' . $cols . '">' . parse_shortcode_content($content) . '</div>';
}
add_shortcode('container', 'container_960gs');

// [grid cols="6"]...[/grid]
function grid_960gs($atts, $content = null)
{
	extract(shortcode_atts(array(
		'cols' => '1'
	), $atts));
	return '<div class="grid_' . $cols . '">' . parse_shortcode_content($content) . '</div>';
}
add_shortcode('grid', 'grid_960gs');

// [clear]
function clear_960gs()
{
	return '<div class="clear"></div>';
}
add_shortcode('clear', 'clear_960gs');

// [hr]
function wpbp_hr()
{
	return '<hr />';
}
add_shortcode('hr', 'wpbp_hr');

/**
 * MAKE SMARTBOX
 */

function make_smartbox($atts, $content = null)
{
    extract( shortcode_atts( array(
        'id'			=> '',
        'class'			=> '',
        'title'			=> '',
        'small'			=> false,
        'collapsible'	=> false,
        'closed'		=> false,
        'sliding'		=> false,
        'ajax'			=> false,
        'src'			=> false,
        'lazy'			=> false
    ), $atts ) );
    
    $id_val = $id;
    
    // define box #id
    $id = ( isset($id) && strlen($id) > 0 ) ? ' id="' . $id . '"' : '';
    
    // define box .class
    $class = 'smartbox container ' . $class;
    if ( $sliding )		$class .= ' sliding';
    if ( $collapsible )	$class .= ' collapsible';
    if ( $closed )		$class .= ' closed';
    if ( $ajax )		$class .= ' ajax';
    if ( $lazy )		$class .= ' lazy';
    $class = ' class="' . $class . '"';
    
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
		$box .= '<div class="box-title title"><h3>';
		if ( $src && $ajax ) $box .= '<a class="ajax-trigger" href="' . $src . '">' . $title . '</a>';
		else $box .= $title;
		$box .= '</h3></div>';
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
    	$box .= '<a class="box-arrow collapse-trigger valign' . ( $ajax ? ' ajax-trigger' : '' ) . '" data-ref="#' . $id_val . ' .box-title" href="#"><span></span></a>';
    	$box .= '</div>';
    }
    
    $box .= '<div class="clear"></div>';
    $box .= '</div>';
    
    return $box;
}
add_shortcode('smartbox', 'make_smartbox');

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
function make_paypal($atts, $content = null) {
    
    extract(shortcode_atts(array(
        'target' => '_self',
        'type' => 'buy now',
		'amount' => '0.00',
        'business' => '',
        'currency' => 'USD',
        'item_name' => '',
        'src' => 'http://www.paypal.com/en_US/i/btn/btn_buynow_LG.gif'
	), $atts));
    
    ob_start();
    
    if ( $type == 'buy now' ) :
?><form name="_xclick" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="<?php echo $target; ?>">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="<?php echo $business; ?>">
<input type="hidden" name="currency_code" value="<?php echo $currency; ?>">
<input type="hidden" name="item_name" value="<?php echo $item_name; ?>">
<input type="hidden" name="amount" value="<?php echo $amount; ?>">
<input type="image" src="<?php echo $src; ?>" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
</form><?php
    elseif ( $type == 'add to cart' ) :
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
add_shortcode('paypal', 'make_paypal');