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
        $protocol = ( !empty($_SERVER['HTTPS']) ) ? "https://" : "http://";
        return $protocol . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    }
    
}


if ( !function_exists('wpbp_is_valid_image') ) {

    function wpbp_is_valid_image($url)
	{
        
        $url = wpbp_get_full_url($url);
        
        $image_status = $wpdb->get_var( $wpdb->prepare("SELECT status FROM wpbp_images WHERE url = '%s' LIMIT 1", $url) );
        
        if ( $image_status == 0 ) return false;
        
        elseif ( $image_status == 1 ) return true;
        
        else {
        
            $image_attr = @getimagesize($url);
            
            if ( isset($image_attr) && is_array($image_attr) ) {
                $wpdb->insert('wpbp_images', array('url' => $url, 'status' => 1));
                return true;
            }
            
            $wpdb->insert('wpbp_images', array('url' => $url, 'status' => 0));
            return false;
        
        }
	}
    
}

if ( !function_exists('wpbp_get_image_size') ) {

	function wpbp_get_image_size($url)
	{
		$url = wpbp_get_full_url($url);
        
        if ( wpbp_is_valid_image($url) ) {
            
            $image = $wpdb->get_row( $wpdb->prepare("SELECT * FROM wpbp_images WHERE url = '%s' LIMIT 1", $url), ARRAY_A );

            if ( is_array($image) ) {

                if ( isset($image['width'], $image['height'], $image['ratio'], $image['type'], $image['attr']) ) {
                    return $image;
                }
            
                else {
                    
                    $image_attr = @getimagesize($url);
    
                	if ( isset($image_attr) && is_array($image_attr) ) {
                        
            			list($width, $height, $type, $attr) = $image_attr;
            			
                        $ratio = round( $width / $height );
            			
                        $wpdb->update(
                            'wpbp_images',
                            array(
                                'width' => $width,
                                'height' => $height,
                                'ratio' => $ratio,
                                'type' => $type,
                                'attr' => $attr
                            ),
                            array(
                                'url' => $url
                            )
                        );
                        
                        return compact('url', 'width', 'height', 'ratio', 'type', 'attr');
            		
                    }
                    
                }
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