<?php


if ( !function_exists('get_image_tag') ) {

	function get_image_tag($args, $options = array())
    {
        if ( is_string($args) ) {
            $args = array('src' => $args);
        }
        elseif ( !is_array($args) ) return false;
        
        if ( isset($options['resize']) && $options['resize'] == true ) {
            if ( isset($args['width'], $args['height']) ) {
                if ( !isset($options['quality']) ) $options['quality'] = 90;
                $args['src'] = wpbp_resize_image_url( $args['src'], $args['width'], $args['height'], $options['quality'] );
            }
        }
        
        if ( !isset($args['src']) || !is_string($args['src']) || strlen($args['src']) == 0 ) return false;
        
		$tag = '<img ';
		foreach ( $args as $key => $value ) {
			$tag .= isset($key,$value) ? $key . '="' . $value . '" ' : '';
		}
		$tag .= '/>';
        
        return $tag;
    }

}

if ( !function_exists('image_tag') && function_exists('wpbp_get_image_tag') ) {
    	
	function image_tag($args, $options = array())
	{
		echo get_image_tag($args, $options);
	}
	
}

if ( !function_exists('script_tag') ) {

	function script_tag($args)
	{
		extract( array_merge( array(
			'src'	=> ( is_string($args) ? $args : '' ),
			'type'	=> 'text/javascript'
		), ( is_array($args) ? $args : array() ) ) );
		return "<script type=\"" . $type . "\" src=\"" . $src . "\"></script>\n";
	}

}

if ( !function_exists('stylesheet_link_tag') ) {

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

}

if ( !function_exists('get_full_url') ) {
    
    function get_full_url($url)
    {
        if ( strpos($url, 'http') === false ) {
        	$protocol = ( isset($_SERVER['HTTPS']) ) ? 'https' : 'http';
			$url = $protocol . '://' . $_SERVER['SERVER_NAME'] . $url;
		}
        return $url;
    }
    
}

if ( !function_exists('get_current_url') ) {
    
    function get_current_url()
    {
        $protocol = ( !empty($_SERVER['HTTPS']) ) ? "https://" : "http://";
        return $protocol . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    }
    
}


if ( !function_exists('is_valid_image') ) {

    function is_valid_image($url)
	{
        
        $url = wpbp_get_full_url($url);
        
		$image_attr = @getimagesize($url);
		
		if ( isset($image_attr) && is_array($image_attr) ) {
			return true;
		}
		
		return false;
	}
    
}

if ( !function_exists('get_image_size') ) {

	function get_image_size($url, $raw = false)
	{
        global $wpdb;
        
		$url = get_full_url($url);
        
        if ( $raw ) {
            $image_attr = @getimagesize($url);
            if ( isset($image_attr) && is_array($image_attr) ) {
            	list($width, $height, $type, $attr) = $image_attr;
                $ratio = ( $height != 0 ) ? round($width / $height, 2) : null;
                return compact('url', 'width', 'height', 'ratio', 'type');
            }
            return false;
        }
        
		return false;
	}

}

if ( !function_exists('resize_image_url') ) {

	function resize_image_url($url, $width = 'auto', $height = 'auto', $q = '90')
	{
		$image_attr = get_image_size($url);

		if ( isset($image_attr) && is_array($image_attr) ) {

			if ( $width == 'auto' && $height == 'auto' ) {
				$width = $image_attr['width'];
				$height = $image_attr['height'];
			}
			elseif ( $height == 'auto' ) {
				$height = round( $width / $image_attr['ratio'] );
			}
			elseif ( $width == 'auto' ) {
				$width = round( $height * $image_attr['ratio'] );
			}

			return TEMPLATE_DIRECTORY . '/img/resize.php?w=' . $width . '&h=' . $height . '&q=' . $q . '&src=' . $url;
		}

		return false;
	}

}

if ( !function_exists('array_plot') ) {

    function array_plot($domain, $function)
	{
		$image = array();
		foreach ( $domain as $x ) {
			if ( is_int($x) || is_string($x) ) {
				$y = $function( $x );
				if ( isset( $y ) ) {
					$image[$x] = $y;
					echo $x . ": " . $y . "<br />\n";
				}
			}
		}
		return $image;
	}

}

if ( !function_exists('sanitize') ) {

	function sanitize($value)
	{
		$type = gettype($value);
		
		switch ( $type ) {
			case 'array' :
				return array_map('sanitize', $value);
			case 'string' :
				return filter_var($value, FILTER_SANITIZE_STRING);
			default :
				return $value;
		}
	}
	
}

if ( !function_exists('encrypt') && !function_exists('decrypt') ) {

	/*!
	 * MAKE SURE TO DEFINE A SALT IN YOUR CHILD THEME
	 * THE SALT DEFINED HERE IS A LAST RESORT ONLY
	 */
	
	if ( !defined('SALT') ) define('SALT', 'wPHVcl6U8t~X-R`R#+ ,7`(NMd,ue,8aC Le%-rVinK-_*3;/BVP$W#c=~L v+[?');

	function encrypt($text)
	{
		if ( function_exists('mcrypt_encrypt') && function_exists('mcrypt_decrypt') ) {
			return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, SALT, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
		}
		else {
			$result = '';
			for ( $i=0; $i < strlen($text); $i++ ) {
				$char = substr($text, $i, 1);
				$keychar = substr(SALT, ($i % strlen(SALT))-1, 1);
				$char = chr( ord($char) + ord($keychar) );
				$result .= $char;
			}
			return base64_encode($result);
		}
	}
	
	function decrypt($text)
	{
		if ( function_exists('mcrypt_encrypt') && function_exists('mcrypt_decrypt') ) {
			return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, SALT, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
		}
		else {
			$result = '';
			$text = base64_decode($text);
			for( $i=0; $i < strlen($text); $i++ ) {
				$char = substr($text, $i, 1);
				$keychar = substr(SALT, ($i % strlen(SALT))-1, 1);
				$char = chr( ord($char) - ord($keychar) );
				$result .= $char;
			}
			return $result;
		}
	}
	
}

if ( !function_exists('array_to_xml') ) {

	function array_to_xml(array $arr, SimpleXMLElement $xml)
	{
		foreach ($arr as $k => $v) {
			is_array($v)
				? array_to_xml($v, $xml->addChild($k))
				: $xml->addChild($k, $v);
		}
		return $xml;
	}

}
