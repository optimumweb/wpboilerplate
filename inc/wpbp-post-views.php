<?php

function wpbp_get_the_views($post_ID, $start = null, $end = null)
{
	$start = !is_null($start) ? strtotime($start) : strtotime( date('Y-m-d', strtotime('-1 day') ) );
	$end = !is_null($end) ? strtotime($end) : strtotime( date('Y-m-d', strtotime('-1 day') ) );

	if ( $start > $end ) return false;

	$post_views = get_post_meta($post_ID, 'wpbp_post_views', true);

	$total_views = 0;

	if ( strlen($post_views) > 0 ) {
		$post_views = json_decode($post_views);
		for ( $date = $start; $date <= $end; $date = strtotime("+1 day", $date) ) {
			$total_views += $post_views[date('Y-m-d', $date)];
		}
	}

	return $total_views;
}

function wpbp_set_the_views($post_ID, $date = null)
{
	$date = ( !is_null($date) ) ? $date : date('Y-m-d');
	list($m, $d, $Y) = explode('-', $date);

	if ( !checkdate($m, $d, $Y) ) return false;

	$post_views = get_post_meta($post_ID, 'wpbp_post_views', true);

	if ( strlen($post_views) > 0 ) {
		$post_views = json_decode($post_views);
		$post_views[$date] = isset($post_views[$date]) ? ( $post_views[$date] + 1 ) : 0;
		$post_views = json_encode($post_views);
		update_post_meta($post_ID, 'wpbp_posts_views', $post_views);
	}
	else {
		$post_views = array();
		$post_views[$date] = 1;
		$post_views = json_encode($post_views);
		add_post_meta($post_ID, 'wpbp_post_views', $post_views, true);
	}

	return true;
}

function wpbp_count_view()
{
	global $wp_query;
	if ( is_single() && isset($wp_query->post->ID) ) {
		$wpbp_set_the_views( $wp_query->post->ID, date('Y-m-d') );
	}
}
add_action('wpbp_footer', 'wpbp_count_view');

?>
