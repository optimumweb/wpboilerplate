<?php

	/**
	 * Mail
	 *
	 * Simple mail class from the WordPress Boilerplate
	 * @author Jonathan Roy <jroy@optimumweb.ca>
	 * @version 1.0
	 * @package wpboilerplate
	 */
	
	class Mail {
		
		public $options;
		public $body;
		public $response;
		
		public function __construct()
		{
			$this->set_options( array(
				'to'			=> '',
				'from'			=> '',
				'reply_to'		=> '',
				'cc'			=> '',
				'bcc'			=> '',
				'subject'		=> 'New Email',
				'mime_version'	=> '1.0',
				'content_type'	=> 'text/html; charset=utf-8'
			) );
			
			$this->set_response('not sent');
		}
		
		public function set_options($new_options)
		{
			if ( is_array($new_options) )
				$this->options = array_merge((array) $this->options, (array) $new_options);
			else
				echo '<p class="error">"' . __CLASS__ . '" error: Invalid options!</p>';
		}
		
		public function get_options()
		{
			return $this->options;
		}
		
		public function set_option($key, $value)
		{
			$this->options[$key] = $value;
		}
		
		public function get_option($option_key)
		{
			return $this->options[$option_key];
		}
		
		public function set_body($body)
		{
			$this->body = $body;
		}
		
		public function get_body()
		{
			return $this->body;
		}
		
		public function set_response($response)
		{
			$this->response = $response;
		}
		
		public function get_response()
		{
			return $this->response;
		}
		
		public function build_fields_html($fields, $format = null)
		{
			$html = '';
			switch ( $format ) {
			
				case 'table' :
					$html .= '<table width="100%" cellpadding="2" cellspacing="0">';
					$html .= '<tbody>';
					foreach ( $fields as $field ) {
						if ( is_array($field['value']) ) $field['value'] = implode(', ', $field['value']);
						$html .= '<tr>';
						$html .= '<td width="100" align="right" style="white-space: nowrap; padding-right: 10px;"><strong>' . $field['label'] . '</strong></td>';
						$html .= '<td>' . $field['value'] . '</td>';
						$html .= '</tr>';
					}
					$html .= '</tbody></table>';
					break;
				
				default :
					foreach ( $fields as $field ) {
						if ( is_array($field['value']) ) $field['value'] = implode(', ', $field['value']);
						$html .= "<p>" . "<strong>" . $field['label'] . "</strong>: " . $field['value'] . "</p>";
					}
					
			}
			
			return $html;
		}
		
		private function build_mail_headers()
		{
			$headers = "";
			
			$options = $this->get_options();
			
			if ( $options['from'] )
				$headers .= "From: " . $options['from'] . "\n";
			if ( $options['reply_to'] )
				$headers .= "Reply-To: " . $options['reply_to'] . "\n";
			if ( $options['cc'] )
				$headers .= "Cc: " . $options['cc'] . "\n";
			if ( $options['bcc'] )
				$headers .= "Bcc: " . $options['bcc'] . "\n";
			if ( $options['mime_version'] )
				$headers .= "MIME-Version: " . $options['mime_version'] . "\n";
			if ( $options['content_type'] )
				$headers .= "Content-Type: " . $options['content_type'];
			
			return $headers;
		}
		
		public function send()
		{
			$sent = @mail(
				$this->get_option('to'),
				$this->get_option('subject'),
				$this->get_body(),
				$this->build_mail_headers()
			);
			
			if ( $sent )
				$this->set_response('sent');
			else
				$this->set_response('error');
		}
	
	}

?>