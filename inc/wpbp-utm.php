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

		$utm = array_merge( $utm, $override );
	}

	return $key ? $utm[$key] : $utm;
}

/**
 * wpbp_parse_utm_fields
 */

function wpbp_parse_utm_fields($utm = array())
{ ?>
	<input id="utmccn" name="field[utmccn][value]" value="<?php echo $utm['utmccn']; ?>" type="hidden" />
	<input id="utmcmd" name="field[utmcmd][value]" value="<?php echo $utm['utmcmd']; ?>" type="hidden" />
	<input id="utmcsr" name="field[utmcsr][value]" value="<?php echo $utm['utmcsr']; ?>" type="hidden" />
	<input id="utmctr" name="field[utmctr][value]" value="<?php echo $utm['utmctr']; ?>" type="hidden" />
	<input id="utmcct" name="field[utmcct][value]" value="<?php echo $utm['utmcct']; ?>" type="hidden" />
<?php }
