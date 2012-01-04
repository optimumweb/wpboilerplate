<?php

	/**
	 * Validation
	 *
	 * @author Jonathan Roy <jroy@optimumweb.ca>
	 * @version 1.0
	 * @package wpboilerplate
	 */

	Class Validation {
		
		public $is_valid;
		public $invalid_fields;
		
		public function __construct()
		{
			$this->set_validity(true);
			$this->set_invalid_fields(array());
		}
		
		public function is_valid()
		{
			return $this->is_valid;
		}
		
		private function set_validity($is_valid)
		{
			if ( is_bool($is_valid) )
				$this->is_valid = $is_valid;
			else
				$this->is_valid = false;
		}
		
		public function get_invalid_fields()
		{
			return $this->invalid_fields;
		}
		
		public function list_invalid_fields()
		{
			$invalid_fields = $this->get_invalid_fields();
			if ( isset($invalid_fields) && is_array($invalid_fields) && count($invalid_fields) != 0 ) {
				echo '<ul>';
				foreach ( $invalid_fields as $invalid_field )
					echo '<li>' . $invalid_field['label'] . '</li>';
				echo '</ul>';
			}
		}
		
		private function set_invalid_fields($invalid_fields)
		{
			if ( is_array($invalid_fields) )
				$this->invalid_fields = $invalid_fields;
		}
		
		private function add_invalid_field($invalid_field)
		{
			$invalid_fields = $this->get_invalid_fields();
			$invalid_fields[] = $invalid_field;
			$this->set_invalid_fields($invalid_fields);
		}
		
		public function validate_fields($fields)
		{
			if ( isset($fields) && is_array($fields) ) {
				foreach ( $fields as $field ) {
					if ( isset($field['required']) ) {
						switch ( $field['required'] ) {
							case 'alphanum' :
								$is_valid = $this->is_alphanum($field['value']);
								break;
							case 'email' :
								$is_valid = $this->is_valid_email($field['value']);
								break;
							case 'tel' :
								$is_valid = $this->is_valid_tel($field['value']);
								break;
							default :
								$is_valid = $this->is_valid_string($field['value']);
						}
						$this->set_validity($is_valid);
						if ( !$is_valid ) {
							$this->add_invalid_field($field);
						}
					}
				}
			}
			else {
				$this->set_validity(false);
			}
		}
		
		public function validate_alphanum($value)
		{
			$this->set_validity( $this->is_alphanum($value) );
		}
		
		public function validate_length($min_len = null, $max_len = null, $value)
		{
			if ( isset($min_len, $max_len) )
				$this->set_validity( $this->is_between($min_len, $max_len, $value) );
			elseif ( isset($min_len) )
				$this->set_validity( $this->is_longer_than($min_len, $value) );
			elseif ( isset($max_len) )
				$this->set_validity( $this->is_shorter_than($max_len, $value) );
			else
				$this->set_validity(false);
		}
		
		public function validate_email($value)
		{
			$this->set_validity( $this->is_valid_email($value) );
		}
		
		public function validate_tel($value)
		{
			$this->set_validity( $this->is_valid_tel($value) );
		}
		
		public function is_valid_string($value)
		{
			if ( isset($value) && is_string($value) && strlen($value) > 0 )
				return true;
			else
				return false;
		}
		
		public function is_longer_than($len, $value)
		{
			if ( !$this->is_valid_string($value) )
				return false;
			else
				return ( strlen($value) > $len );
		}
		
		public function is_shorter_than($len, $value)
		{
			if ( !$this->is_valid_string($value) )
				return false;
			else
				return ( strlen($value) < $len );
		}
		
		public function is_between($min_len, $max_len, $value)
		{
			if ( !$this->is_valid_string($value) )
				return false;
			else
				return ( $this->is_longer_than($min_len, $value) && $this->is_shorter_than($max_len, $value) );
		}
		
		public function is_alphanum($value)
		{
			if ( !$this->is_valid_string($value) )
				return false;
			else
				return ctype_alnum($value) ? true : false;
		}
		
		public function is_valid_email($value)
		{
			if ( !$this->is_valid_string($value) )
				return false;
			else 
				return ( filter_var($value, FILTER_VALIDATE_EMAIL) === false ) ? false : true;
		}
		
		public function is_valid_tel($value)
		{
			$value = preg_replace("/[^0-9]/", "", $value);
			if ( !$this->is_valid_string($value) )
				return false;
			else
				return $this->is_between(6, 15, $value);
		}
	
	}

?>