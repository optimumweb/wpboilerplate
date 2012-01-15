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

if ( !function_exists('image_tag') && function_exists('get_image_tag') ) {
    	
	function image_tag($args, $options = array())
	{
		echo get_image_tag($args, $options);
	}
	
}

if ( !function_exists('wpbp_get_full_url') ) {
    
    function wpbp_get_full_url($url)
    {
        if ( strpos($url, 'http') === false ) {
        	$protocol = ( isset($_SERVER['HTTPS']) ) ? 'https' : 'http';
			$url = $protocol . '://' . $_SERVER['SERVER_NAME'] . $url;
		}
        return $url;
    }
    
}

if ( !function_exists('wpbp_get_current_url') ) {
    
    function wpbp_get_current_url()
    {
        $protocol = ( !empty($_SERVER['HTTPS']) ) ? "https://" : "http://";
        return $protocol . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    }
    
}


if ( !function_exists('wpbp_is_valid_image') ) {

    function wpbp_is_valid_image($url)
	{
        global $wpdb;
        
        $url = wpbp_get_full_url($url);
        
        if ( wpbp_image_table_exists() ) {
            $image_status = $wpdb->get_var( $wpdb->prepare("SELECT status FROM " . WPBP_IMAGE_TABLE . " WHERE url = '%s' LIMIT 1 ", $url) );
        }
        
        if ( !isset($image_status) || $image_status === null ) {
        
            $image_attr = @getimagesize($url);
            
            if ( isset($image_attr) && is_array($image_attr) ) {
                $wpdb->insert(WPBP_IMAGE_TABLE, array('ID' => null, 'url' => $url, 'status' => 1));
                return true;
            }
            
            else {
                $wpdb->insert(WPBP_IMAGE_TABLE, array('ID' => null, 'url' => $url, 'status' => 0));
                return false;
            }
        }
        
        elseif ( $image_status == 1 ) {
            return true;
        }
        
        return false;
	}
    
}

if ( !function_exists('wpbp_get_image_size') ) {

	function wpbp_get_image_size($url, $raw = false)
	{
        global $wpdb;
        
		$url = wpbp_get_full_url($url);
        
        if ( $raw ) {
            $image_attr = @getimagesize($url);
            if ( isset($image_attr) && is_array($image_attr) ) {
            	list($width, $height, $type, $attr) = $image_attr;
                $ratio = ( $height != 0 ) ? round($width / $height, 2) : null;
                return compact('url', 'width', 'height', 'ratio', 'type');
            }
            return false;
        }
        
        if ( wpbp_is_valid_image($url) ) {
            
            if ( wpbp_image_table_exists() ) {
                
                $image = $wpdb->get_row( $wpdb->prepare("SELECT * FROM " . WPBP_IMAGE_TABLE . " WHERE url = '%s' LIMIT 1 ", $url), ARRAY_A );
                
                if ( isset($image) && is_array($image) && isset($image['width'], $image['height']) ) {
                    return $image;
                }
                
                else {
                    $image = wpbp_get_image_size($url, true);
                    if ( isset($image) && is_array($image) ) {
                        $wpdb->update(WPBP_IMAGE_TABLE, $image, array('url' => $url));
                        echo "<!-- " . var_export($image, true) . " //-->\n";
                        return $image;
                    }
                }
            }
            
            else {
                wpbp_create_image_table();
                return wpbp_get_image_size($url, true);
            }
        
        }
        
		return false;
	}

}

if ( !function_exists('wpbp_resize_image_url') ) {

	function wpbp_resize_image_url($url, $width = 'auto', $height = 'auto', $q = '90')
	{
		$image_attr = wpbp_get_image_size($url);

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

			return get_bloginfo('template_directory') . '/img/resize.php?w=' . $width . '&h=' . $height . '&q=' . $q . '&src=' . $url;
		}

		return false;
	}

}

if ( !function_exists('wpbp_table_exists') ) {
    
    function wpbp_table_exists($table)
    {
        global $wpdb;
        $sql = @$wpdb->query("SELECT * FROM " . $table . " LIMIT 1");
        return ( !$sql ) ? false : true;
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

?>