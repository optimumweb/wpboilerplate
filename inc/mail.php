<?php

/**
 * Edit the section below
 */

$to = "jroy66@gmail.com";

$subject = "This email was sent using PHP, jQuery and AJAX";


/**
 * Do not edit below here
 */

$from = ( isset( $_POST['email'] ) ) ? $_POST['email'] : $to;

$headers  = "From: " . $from . "\r\n";
$headers .= "Reply-To: " . $from . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/plain; charset=ISO-8859-1;\r\n";
$headers .= "Content-Transfer-Encoding: 7bit\r\n";
$headers .= "X-Mailer: PHP" . phpversion() ."\r\n";

$message  = "This email was sent using PHP, jQuery and AJAX\r\n";
$message .= "--\r\n";
foreach ( $_POST as $key => $value ) {
	$message .= $key . ": " . $value . "\r\n";
}
$message .= "--\r\n";
$message .= "Sent on " . date('l, F jS Y') . " at " . date('g:i A') . "\r\n";

$sent = @mail($to, $subject, $message, $headers);

if ( $sent ) {
	echo "success";
} else {
	echo "error";
}

?>