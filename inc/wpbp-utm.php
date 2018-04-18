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

function wpbp_get_utm($key = null, $override = array())
{
	$utm = array();

	if ( !empty($_COOKIE['__utmz']) ) {

		$pattern = "/(utmcsr=([^\|]*)[\|]?)|(utmccn=([^\|]*)[\|]?)|(utmcmd=([^\|]*)[\|]?)|(utmctr=([^\|]*)[\|]?)|(utmcct=([^\|]*)[\|]?)/i";

		preg_match_all($pattern, $_COOKIE['__utmz'], $matches);

		if ( isset($matches[0]) && is_array($matches[0]) && count($matches[0]) > 0 ) {

			foreach ( $matches[0] as $match ) {

				$pair = null;

				$match = trim($match, "|");

				list($k, $v) = explode("=", $match);

				$utm[$k] = $v;

			}
		}

		$utm = array_merge($utm, $override);

	}

	return $key ? $utm[$key] : $utm;
}

/**
 * wpbp_parse_utm_fields
 */

function wpbp_parse_utm_fields($utm = array())
{
	$utm_fields = array( 'utmccn', 'utmcmd', 'utmcsr', 'utmctr', 'utmcct' );
	foreach ( $utm_fields as $utm_field ) {
		if ( array_key_exists($utm_field, $utm) ) {
			echo sprintf('<input id="%s" name="field[%s][value]" value="%s" type="hidden" />', $utm_field, $utm_field, $utm[$utm_field]) . PHP_EOL;
		}
	}
}
