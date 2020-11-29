<?php

if ( ! function_exists( 'wpbp_is_valid_var' ) ) {

	function wpbp_is_valid_var( $var ) {
		return ! empty( $var );
	}

}

if ( ! function_exists( 'wpbp_are_valid_vars' ) ) {

	function wpbp_are_valid_vars() {
		$vars = func_get_args();

		foreach ( $vars as $var ) {
		    if ( ! wpbp_is_valid_var( $var ) ) return false;
		}

		return true;
	}
}

if ( ! function_exists( 'wpbp_first_valid' ) ) {

	/**
	 * wpbp_first_valid
	 * Returns the first valid variable in a list
	 */
	function wpbp_first_valid()
	{
		$vars = func_get_args();

		foreach ( $vars as $var ) {
		    if ( wpbp_is_valid_var( $var ) ) return $var;
		}
	}

}

if ( ! function_exists( 'wpbp_get_image_tag' ) ) {

	function wpbp_get_image_tag( $args, $options = array() ) {
        if ( is_string( $args ) ) {
            $args = array( 'src' => $args );
        } elseif ( ! is_array( $args ) ) {
            return false;
        }

        if ( isset( $options['resize'] ) && $options['resize'] === true ) {
            if ( isset( $args['width'], $args['height'] ) ) {
                if ( ! isset( $options['quality'] ) ) $options['quality'] = 90;
                $args['src'] = resize_image_url( $args['src'], $args['width'], $args['height'], $options['quality'] );
            }
        }

        if ( ! isset( $args['src'] ) || ! is_string( $args['src'] ) || strlen( $args['src'] ) == 0 ) {
            return false;
        }

		$tag = '<img ';
		foreach ( $args as $key => $value ) {
			$tag .= isset( $key, $value ) ? $key . '="' . $value . '" ' : '';
		}
		$tag .= '/>';

        return $tag;
    }

}

if ( ! function_exists( 'wpbp_image_tag' ) && function_exists( 'wpbp_get_image_tag' ) ) {

	function wpbp_image_tag( $args, $options = array() ) {
		echo wpbp_get_image_tag( $args, $options );
	}

}

if ( ! function_exists( 'script_tag' ) ) {

	function script_tag( $args ) {
		extract( array_merge( array(
			'src'	=> is_string($args) ? $args : '',
			'type'	=> 'text/javascript'
		), is_array($args) ? $args : array() ) );

		return '<script type="' . $type . '" src="' . $src . '"></script>' . PHP_EOL;
	}

}

if ( ! function_exists( 'stylesheet_link_tag' ) ) {

	function stylesheet_link_tag( $args ) {
		extract( array_merge( array(
			'href'	=> is_string($args) ? $args : '',
			'rel'	=> 'stylesheet',
			'media'	=> 'all',
			'type'	=> 'text/css'
		), is_array($args) ? $args : array() ) );

		return '<link rel="' . $rel . '" href="' . $href . '" type="' . $type . '" media="' . $media . '" />' . PHP_EOL;
	}

}

if ( ! function_exists( 'get_full_url' ) ) {

    function get_full_url( $url ) {
        if ( strpos( $url, 'http' ) === false ) {
        	$protocol = isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
			$url = $protocol . '://' . $_SERVER['SERVER_NAME'] . $url;
		}

        return $url;
    }

}

if ( ! function_exists( 'get_current_url' ) ) {

    function get_current_url() {
        $protocol = isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://';
        return $protocol . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    }

}


if ( ! function_exists( 'is_valid_image' ) ) {

    function is_valid_image( $url ) {
        $url = get_full_url( $url );
		$image_attr = @getimagesize( $url );
		return isset( $image_attr ) && is_array( $image_attr ) && count( $image_attr ) > 0;
	}

}

if ( ! function_exists( 'get_image_size' ) ) {

	function get_image_size( $url, $raw = false ) {
		$url = get_full_url( $url );

        if ( $raw ) {
            $image_attr = @getimagesize( $url );

            if ( isset( $image_attr ) && is_array( $image_attr ) && count( $image_attr ) > 0 ) {
            	list( $width, $height, $type, $attr ) = $image_attr;
                $ratio = $height != 0 ? round( $width / $height, 2 ) : null;
                return compact( 'url', 'width', 'height', 'ratio', 'type' );
            }
        }

		return false;
	}

}

if ( ! function_exists( 'resize_image_url' ) ) {

	function resize_image_url( $url, $width = 'auto', $height = 'auto', $q = '90' ) {
		if ( $width === 'auto' && $height === 'auto' ) {
			$width = $image_attr['width'];
			$height = $image_attr['height'];
		} elseif ( $height === 'auto' ) {
			$height = round( $width / $image_attr['ratio'] );
		} elseif ( $width === 'auto' ) {
			$width = round( $height * $image_attr['ratio'] );
		}

		return TEMPLATE_URI . '/img/resize.php?w=' . $width . '&h=' . $height . '&q=' . $q . '&src=' . $url;
	}

}

if ( ! function_exists( 'array_plot' ) ) {

    function array_plot( $domain, $function, $echo = true ) {
        if ( ! function_exists( $function ) ) return false;

		$image = array();

		foreach ( $domain as $x ) {
			if ( is_int( $x ) || is_string( $x ) ) {
				$y = $function( $x );
				if ( isset( $y ) ) {
					$image[$x] = $y;

					if ( $echo ) {
                        echo $x . ": " . $y . "<br />" . PHP_EOL;
                    }
				}
			}
		}

		return $image;
	}

}

if ( ! function_exists( 'array_get' ) ) {

    function array_get( $array, $key, $default = null, $strcmp = false ) {
        if ( is_array( $array ) ) {
            if ( $strcmp ) {
                foreach ( $array as $k => $v ) {
                    if ( strcmp( $k, $key ) === 0 ) {
                        return $v;
                    }
                }
            } else {
                if ( array_key_exists( $key, $array ) ) {
                    return $array[$key];
                }
            }
        }

        return $default;
    }

}

if ( ! function_exists( 'sanitize' ) ) {

	function sanitize( $value ) {
		switch ( gettype($value) ) {
			case 'array':
				return array_map( 'sanitize', $value );
			case 'string':
				return filter_var( $value, FILTER_SANITIZE_STRING );
			default:
				return $value;
		}
	}

}

if ( ! function_exists( 'encrypt' ) && ! function_exists( 'decrypt' ) ) {

	function encrypt( $text, $key = null ) {
		if ( !$key && defined( 'AUTH_SALT' ) ) {
            $key = AUTH_SALT;
        }

		if ( function_exists( 'mcrypt_encrypt' ) && function_exists( 'mcrypt_decrypt' ) ) {
		    $iv_size = mcrypt_get_iv_size( MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB );
		    $iv = mcrypt_create_iv( $iv_size, MCRYPT_RAND );
		    $encrypted = mcrypt_encrypt( MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_ECB, $iv );
		    $encoded = base64_encode( $encrypted );
			return trim( $encoded );
		} else {
			$result = '';
			for ( $i = 0; $i < strlen( $text ); $i++ ) {
				$char = substr( $text, $i, 1 );
				$keychar = substr($key, ( $i % strlen( $key ) ) - 1, 1);
				$char = chr( ord( $char ) + ord( $keychar ) );
				$result .= $char;
			}
			return base64_encode( $result );
		}
	}

	function decrypt( $text, $key = null ) {
		if ( !$key && defined( 'AUTH_SALT' ) ) {
            $key = AUTH_SALT;
        }

		if ( function_exists( 'mcrypt_encrypt' ) && function_exists( 'mcrypt_decrypt' ) ) {
		    $iv_size = mcrypt_get_iv_size( MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB );
		    $iv = mcrypt_create_iv( $iv_size, MCRYPT_RAND );
		    $decoded = base64_decode( $text );
		    $decrypted = mcrypt_decrypt( MCRYPT_RIJNDAEL_256, $key, $decoded, MCRYPT_MODE_ECB, $iv );
			return trim( $decrypted );
		} else {
			$result = '';
			$text = base64_decode( $text );
			for( $i = 0; $i < strlen( $text ); $i++ ) {
				$char = substr( $text, $i, 1 );
				$keychar = substr( $key, ( $i % strlen( $key ) ) - 1, 1 );
				$char = chr( ord( $char ) - ord( $keychar ) );
				$result .= $char;
			}
			return $result;
		}
	}

}

if ( ! function_exists( 'array_to_xml' ) ) {

	function array_to_xml( array $arr, SimpleXMLElement $xml ) {
		foreach ( $arr as $k => $v ) {
			is_array( $v ) ? array_to_xml( $v, $xml->addChild($k) ) : $xml->addChild( $k, $v );
		}
		return $xml;
	}

}
