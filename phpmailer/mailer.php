<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
// https://help.dreamhost.com/hc/en-us/articles/360031174411-PHPMailer-Installing-on-a-Shared-server


// Error code reference
// Error code 	Description
// missing-input-secret 	The secret parameter is missing.
// invalid-input-secret 	The secret parameter is invalid or malformed.
// missing-input-response 	The response parameter is missing.
// invalid-input-response 	The response parameter is invalid or malformed.
// bad-request 	The request is invalid or malformed.
// timeout-or-duplicate 	The response is no longer valid: either is too old or has been used previously.

require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';


$url = 'https://www.google.com/recaptcha/api/siteverify';
$data = array(
	'secret' => '--google recaptcha secret-- (https://www.google.com/recaptcha/admin/create)',
	'response' => $_REQUEST['g-recaptcha-response'],
);

$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    )
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
if ($result === FALSE) {
	die("error");
} else {
	$result = json_decode($result);
}


// https://anandlagad.com/how-to-send-email-using-phpmailer-and-gmail-with-example/
if ($result->success) {
	$mail = new PHPMailer(true); // "true" enables exceptions

	$sender = $_REQUEST['name'];
	$message = $_REQUEST['message'];
	$subject = "website email";
	$landing = "https://www.example.com/pages/thank-you/";

	$body = sprintf("<h3>From:</h3>%s<br><h3>Message:</h3><br>%s", $sender, nl2br($message));

	try {
		//........................................
		// use below mail sever settings in case of Sending email with PHPMailer and GMAIL
		$mail->IsSMTP();
		$mail->SMTPDebug = 0;
		$mail->SMTPAuth = TRUE;
		$mail->SMTPSecure = "tls";
		$mail->Host     = "smtp.gmail.com";
		$mail->Username = "-- GMAIL USERNAME -- ";
		$mail->Password = "--APP PASSWORD--";
		$mail->Port     = 587;
		$mail->Mailer   = "smtp";
		//........................................................

		// mail creation details
		$mail->SetFrom("anthony@example.com", "from website");
		$mail->AddAddress("winterbeef@example.com", "Recipient Name");
		$mail->AddReplyTo("anthony@example.com", "from website");
		// $mail->addCC('cc@example.com');
		// $mail->addBCC('bcc@example.com');
		// $mail->addAttachment('/var/tmp/file.tar.gz');
		// $mail->setLanguage('fr', '/optional/path/to/language/directory/');

		//Content
		$mail->Priority    = 1;
		$mail->CharSet     = 'UTF-8';
		$mail->Encoding    = '8bit';
		$mail->ContentType = 'text/html; charset=utf-8\r\n';
		$mail->isHTML(true);
		$mail->Subject = $subject;

		$mail->MsgHTML($body);
		$mail->AltBody = $body;
		$mail->WordWrap   = 80;

		$mail->send();
		$mail->SmtpClose();
		header("Location: ".$landing);
		echo "done";
	} catch (Exception $e) {
		echo 'Message could not be sent.';
		echo 'Mailer Error: ' . $mail->ErrorInfo;
	}

} else {
	print_r($result);

}
