<?php

function wpbp_get_the_views($start = null, $end = null, $post_ID = null)
{
	// if the post ID is not specified, get the current post ID
	if ( !isset($post_ID) ) {
		if ( is_single() ) {
			$post_ID = $wp_query->post->ID;
		} else {
			return false;
		}
	}

	$start = !is_null($start) ? strtotime($start) : strtotime( date('Y-m-d', strtotime('now') ) );
	$end = !is_null($end) ? strtotime($end) : strtotime( date('Y-m-d', strtotime('now') ) );

	if ( $start > $end ) return false;

	$post_views = get_post_meta($post_ID, 'wpbp_post_views', true);

	$total_views = 0;

	if ( strlen($post_views) > 0 ) {
		$post_views = json_decode($post_views, true);
		for ( $date = $start; $date <= $end; $date = strtotime("+1 day", $date) ) {
			$total_views += $post_views[date('Y-m-d', $date)];
		}
	}

	var_dump($start, $end, $post_ID);

	return $total_views;
}

function wpbp_set_the_views($post_ID, $date = null)
{	
	$date = ( !is_null($date) ) ? $date : date('Y-m-d');
	list($year, $month, $day) = array_map( 'intval', explode('-', $date) );

	if ( !checkdate( $month, $day, $year ) ) return false;

	$post_views = get_post_meta($post_ID, 'wpbp_post_views', true);

	if ( strlen($post_views) > 0 ) {
		$post_views = json_decode($post_views, true);
		$post_views[$date] = isset($post_views[$date]) ? ( $post_views[$date] + 1 ) : 0;
		$post_views = json_encode($post_views);
		update_post_meta($post_ID, 'wpbp_post_views', $post_views);
	}
	else {
		$post_views = array();
		$post_views[$date] = 1;
		$post_views = json_encode($post_views);
		add_post_meta($post_ID, 'wpbp_post_views', $post_views, true);
	}

	return true;
}

?>
