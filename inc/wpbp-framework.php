<?php


if ( !function_exists('image_tag') ) {
    
    function image_tag($args, $options = array())
    {
        if ( is_string($args) ) {
            $src = $args;
            $args = array('src' => $src);
        }
        elseif ( !is_array($args) ) return false;
        
        if ( isset($options['resize']) && $options['resize'] == true ) {
            if ( isset($args['width'], $args['height']) ) {
                if ( !isset($options['quality']) ) $options['quality'] = 90;
                $args['src'] = wpbp_resize_image_url( $args['src'], $args['width'], $args['height'], $args['quality'] );
            }
        }
        
        if ( isset($args['src']) && is_string($args['src']) && strlen($args['src']) > 0 ) {
            echo "<img ";
            foreach ( $args as $key => $value ) {
                if ( isset($key,$value) ) {
                    echo $key . "=\"" . $value . "\" ";
                }
            }
            echo "/>";
        }
        
        return;
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
        return ( !empty($_SERVER['HTTPS']) ) ? "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] : "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    }
    
}

if ( !function_exists('wpbp_is_valid_image') ) {

    function wpbp_is_valid_image($url, $valid_image_types = array( IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG ))
	{
        if ( !is_array($valid_image_types) ) return null;
        
        $url = wpbp_get_full_url($url);
        
        if ( function_exists('exif_imagetype') ) {
            $image_type = exif_imagetype($url);
        }
        else {
            $image_attr = @getimagesize($url);
            if ( isset($image_attr) && is_array($image_attr) ) {
                $image_type = $image_attr[2];
            }
        }
        if ( isset($image_type) && in_array($image_type, $valid_image_types) ) {
            return true;
        }
        return false;
	}
    
}

if ( !function_exists('wpbp_get_image_size') ) {

	function wpbp_get_image_size($url)
	{
		$url = wpbp_get_full_url($url);
        
        if ( wpbp_is_valid_image($url) ) {

    		$image_attr = @getimagesize($url);
    
    		if ( isset($image_attr) && is_array($image_attr) ) {
    			list($width, $height, $type, $attr) = $image_attr;
    			$ratio = round( $width / $height );
    			return compact('url', 'width', 'height', 'ratio', 'type', 'attr');
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

?>