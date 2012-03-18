<?php

	define('WP_USE_THEMES', false);
	require('./wp-load.php');

	$validation = new Validation();

	$to = $_POST['to'] ? decrypt($_POST['to']) : false;
	$from = $_POST['from'] ? decrypt($_POST['from']) : false;
	$cc = $_POST['cc'] ? decrypt($_POST['cc']) : false;
	$bcc = $_POST['bcc'] ? decrypt($_POST['bcc']) : false;
	$reply_to = $_POST['reply_to'] ? decrypt($_POST['reply_to']) : false;
	$subject = $_POST['subject'] ? decrypt($_POST['subject']) : false;
	
	$fields = sanitize($_POST['field']);
	
	$validation->validate_fields($fields);
	
	if ( !!$to && $validation->is_valid() ) {
	
		$mail = new Mail();
		
		$mail->set_option('to', $to);
		
		if ( !!$from )
			$mail->set_option('from', $from);
		
		if ( !!$cc )
			$mail->set_option('cc', $cc);
		
		if ( !!$bcc )
			$mail->set_option('bcc', $bcc);
		
		if ( !!$reply_to ) {
			if ( $reply_to == 'sender' && $validation->is_valid_email($fields['email']['value']) )
				$mail->set_option('reply_to', $fields['email']['value']);
			else
				$mail->set_option('reply_to', $reply_to);
		}
		
		if ( !!$subject )
			$mail->set_option('subject', $subject);
		
		$body .= '<div style="background: #eee; padding: 40px;">';
		$body .= '<div style="max-width: 600px; min-width: 400px; margin: 0 auto;">';
		$body .= '<div style="margin-bottom: 10px;"><img src="' . CDN . '/img/email-logo.png" alt="" /></div>';
		$body .= '<div style="font-family: Helvetica, Arial, sans-serif; font-size: 12px; color: #333; line-height: 1.5; padding: 20px; background: #fff; border: 1px solid #ddd;">';
		
		if ( !!$subject )
			$body .= '<h1 style="margin: 0 0 10px 0;">' . $subject . '</h1>';
		
		$body .= $mail->build_fields_html($fields, array(
			'format'	=> 'table',
			'label_css' => 'width: 125px; white-space: nowrap; padding: 10px 10px 10px 0; border-top: 1px dotted #ddd; text-align: right; font-size: 10px; text-transform: uppercase; color: #666;',
			'value_css' => 'padding: 5px 0; border-top: 1px dotted #ddd; font-weight: bold;'
		));
		
		$body .= '</div></div></div>';
		
		$mail->set_body($body);
		
		$mail->send();
		$response = $mail->get_response();
		
		switch ($response) {
			case 200 :
				header("HTTP/1.1 200 OK");
				break;
			case 400 :
				header("HTTP/1.0 400 Bad Request");
				break;
			case 500 :
				header("HTTP/1.1 500 Internal Server Error");
				break;
		}
	}
	
	else {
		header("HTTP/1.1 500 Internal Server Error");
	}
