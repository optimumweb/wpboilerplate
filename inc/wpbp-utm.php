<?php

/**
 * wpbp_get_utm
 * Extracts campaign data from Google Analytics cookies and parses it in an array
 * utmccn => campaign name
 * utmcsr => campaign source
 * utmcmd => campaign medium
 * utmctr => campaign term or keyword
 * utmcct => campaign content
 */

function wpbp_get_utm($key = null)
{
	$utm = array();
	
	if ( !empty( $_COOKIE['__utmz'] ) ) { 
	
		$pattern = "/(utmcsr=([^\|]*)[\|]?)|(utmccn=([^\|]*)[\|]?)|(utmcmd=([^\|]*)[\|]?)|(utmctr=([^\|]*)[\|]?)|(utmcct=([^\|]*)[\|]?)/i";
		
		preg_match_all( $pattern, $_COOKIE['__utmz'], $matches );
		
		if ( !empty( $matches[0] ) ) {
		
			foreach ( $matches[0] as $match ) {
			
				$pair = null;
				
				$match = trim($match, "|");
				
				list($k, $v) = explode("=", $match);
				
				$utm[$k] = $v;
		
			}
	
		}
		
		$utm['name']	= $utm['utmccn'];
		$utm['source']	= $utm['utmcsr'];
		$utm['medium']	= $utm['utmcmd'];
		$utm['term']	= $utm['utmctr'];
		$utm['content']	= $utm['utmcct'];
		
	}
	
	return $key ? $utm[$key] : $utm;
}