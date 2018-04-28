<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class PHPMailHelper{
	public static function sendmail($email_to, $subject, $content){
		Loader::loadPlugin('phpmailer');
		$mail = new PHPMailer(true);
		try{
			$mail->isSMTP();
			$mail->Host = 'mx1.hostinger.ru';
			$mail->CharSet = 'UTF-8';
			$mail->SMTPAuth = true;
			$mail->Username = MAIL_USERNAME;
			$mail->Password = MAIL_PASSWORD;
			$mail->SMTPSecure = 'tls';
			$mail->Port = SMTP_PORT;
				//Recipients
			$mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
			$mail->addAddress($email_to);     // Add a recipient
			$mail->addReplyTo(REPLY_TO, REPLY_TO_NAME);


			//Content
			$mail->isHTML(true);
			$mail->Subject = $subject;
			$mail->Body    = $content;
			//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

			$mail->send();
			$mail_string = $mail->getSentMIMEMessage();

			$stream = imap_open(MAIL_BOX, MAIL_USERNAME, MAIL_PASSWORD, NULL, 1, array('DISABLE_AUTHENTICATOR' => 'PLAIN')) or
			die(var_dump(imap_errors()));

			imap_append($stream, MAIL_BOX.".Sent", $mail_string);
			imap_close($stream);
		}catch(Exception $ex){
			throw new Exception($ex->getMessage());
		}
	}
}

?>
