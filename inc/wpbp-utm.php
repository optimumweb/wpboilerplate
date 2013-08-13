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
{
?>
	<input id="utmccn" name="field[utmccn][value]" value="<?php echo $utm['utmccn']; ?>" type="hidden" />
	<input id="utmcmd" name="field[utmcmd][value]" value="<?php echo $utm['utmcmd']; ?>" type="hidden" />
	<input id="utmcsr" name="field[utmcsr][value]" value="<?php echo $utm['utmcsr']; ?>" type="hidden" />
	<input id="utmctr" name="field[utmctr][value]" value="<?php echo $utm['utmctr']; ?>" type="hidden" />
	<input id="utmcct" name="field[utmcct][value]" value="<?php echo $utm['utmcct']; ?>" type="hidden" />
<?php
}

/**
 * UTMZ Cookie Parser parses values from Google Analytics cookies into variables
 * for population into hidden fields, databases or elsewhere
 */

class utmz_cookie_parser {

    public $utmz_source;
    public $utmz_medium;
    public $utmz_term;
    public $utmz_content;
    public $utmz_campaign;
    public $utmz_gclid;
    public $utmz;
    public $utmz_domainHash;
    public $utmz_timestamp;
    public $utmz_sessionNumber;
    public $utmz_campaignNumber;

    //Contstructor fires method that parses and assigns property values
    function __construct() {
        $this->_set_utmz();
    }

    //Grab utmz cookie if it exists
    private function _set_utmz() {
        if (isset($_COOKIE['__utmz'])) {
            $this->utmz = $_COOKIE['__utmz'];
            $this->_parse_utmz();
        } else return false;
    }

    //parse utmz cookie into variables
    private function _parse_utmz() {

        //Break cookie in half
        $utmz_b = strstr($this->utmz, 'u');
        $utmz_a = substr($this->utmz, 0, strpos($this->utmz, $utmz_b) - 1);

        //assign variables to first half of cookie
        list($this->utmz_domainHash, $this->utmz_timestamp, $this->utmz_sessionNumber, $this->utmz_campaignNumber) = explode('.', $utmz_a);

        //break apart second half of cookie
        $utmzPairs = array();
        $z = explode('|', $utmz_b);
        foreach ($z as $value) {
            $v = explode('=', $value);
            $utmzPairs[$v[0]] = $v[1];
        }

        //Variable assignment for second half of cookie
        foreach ($utmzPairs as $key => $value) {
            switch ($key) {
                case 'utmcsr':
                    $this->utmz_source = $value;
                    break;
                case 'utmcmd':
                    $this->utmz_medium = $value;
                    break;
                case 'utmctr':
                    $this->utmz_term = $value;
                    break;
                case 'utmcct':
                    $this->utmz_content = $value;
                    break;
                case 'utmccn':
                    $this->utmz_campaign = $value;
                    break;
                case 'utmgclid':
                    $this->utmz_gclid = $value;
                    break;
                default:
                    //do nothing
            }
        }

    }

}

////////////////////////////////////////////////////
// GA_Parse - PHP Google Analytics Parser Class
//
// Version 1.0 - Date: 17 September 2009
// Version 1.1 - Date: 25 January 2012
// Version 1.2 - Date: 21 April 2012
//
// Define a PHP class that can be used to parse
// Google Analytics cookies currently with support
// for __utmz (campaign data) and __utma (visitor data)
//
// Author: Joao Correia - http://joaocorreia.pt
//
// License: LGPL
//
////////////////////////////////////////////////////

class GA_Parse
{

    var $campaign_source;    		// Campaign Source
    var $campaign_name;  			// Campaign Name
    var $campaign_medium;    		// Campaign Medium
    var $campaign_content;   		// Campaign Content
    var $campaign_term;      		// Campaign Term

    var $first_visit;      		// Date of first visit
    var $previous_visit;			// Date of previous visit
    var $current_visit_started;	// Current visit started at
    var $times_visited;			// Times visited
    var $pages_viewed;			// Pages viewed in current session


    function __construct($_COOKIE) {
        // If we have the cookies we can go ahead and parse them.
        if (isset($_COOKIE["__utma"]) and isset($_COOKIE["__utmz"])) {
            $this->ParseCookies();
        }

    }

    function ParseCookies(){

        // Parse __utmz cookie
        list($domain_hash,$timestamp, $session_number, $campaign_numer, $campaign_data) = preg_split('[\.]', $_COOKIE["__utmz"],5);

        // Parse the campaign data
        $campaign_data = parse_str(strtr($campaign_data, "|", "&"));

        $this->campaign_source = $utmcsr;
        $this->campaign_name = $utmccn;
        $this->campaign_medium = $utmcmd;
        if (isset($utmctr)) $this->campaign_term = $utmctr;
        if (isset($utmcct)) $this->campaign_content = $utmcct;

        // You should tag you campaigns manually to have a full view
        // of your adwords campaigns data.
        // The same happens with Urchin, tag manually to have your campaign data parsed properly.

        if (isset($utmgclid)) {
            $this->campaign_source = "google";
            $this->campaign_name = "";
            $this->campaign_medium = "cpc";
            $this->campaign_content = "";
            $this->campaign_term = $utmctr;
        }

        // Parse the __utma Cookie
        list($domain_hash,$random_id,$time_initial_visit,$time_beginning_previous_visit,$time_beginning_current_visit,$session_counter) = preg_split('[\.]', $_COOKIE["__utma"]);

        $this->first_visit = new \DateTime();
        $this->first_visit->setTimestamp($time_initial_visit);

        $this->previous_visit = new \DateTime();
        $this->previous_visit->setTimestamp($time_beginning_previous_visit);

        $this->current_visit_started = new \DateTime();
        $this->current_visit_started->setTimestamp($time_beginning_current_visit);

        $this->times_visited = $session_counter;

        // Parse the __utmb Cookie

        list($domain_hash,$pages_viewed,$garbage,$time_beginning_current_session) = preg_split('[\.]', $_COOKIE["__utmb"]);
        $this->pages_viewed = $pages_viewed;


        // End ParseCookies
    }

// End GA_Parse
}