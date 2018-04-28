<?php

class MailSender{
	private $EMAIL_TO;


	private $EMAIL_FROM;
	private $EMAIL_FROM_NAME;

	private $REPLY_TO;
	private $REPLY_TO_NAME;

	private $SUBJECT;
	private $MARK;

	private $HEADER;
	private $MESSAGE = '';

	/* configuration */
	private $MESSAGE_ID;
	private $NOW;
	private $MIME_VERSION = '1.0';
	private $X_MAILER;
	private $EOL = "\n";
	/* /.configuration */

 function __construct(){
	 if (strtoupper(substr(PHP_OS,0,3)=='WIN')) {
		$this->EOL="\r\n";
	 } elseif (strtoupper(substr(PHP_OS,0,3)=='MAC')) {
		$this->EOL="\r";
	 } else {
		$this->EOL="\n";
	 }

	 $this->MARK = md5(date('r', time()));
	 $this->X_MAILER = 'PHP v'.phpversion();
	 $this->NOW = time();
	 $this->MESSAGE_ID = '<'.$this->NOW.' TheSystem@'.$_SERVER['SERVER_NAME'].'>';
 }

	public function mailTo($EMAIL_TO){
		if (filter_var($EMAIL_TO, FILTER_VALIDATE_EMAIL)) {
			if(empty($this->EMAIL_TO)){
				$this->EMAIL_TO = $EMAIL_TO;
			}
		}else{
			throw new Exception('Entered mail is not valid: '.$EMAIL_TO);
		}
	}

	public function mailFrom($EMAIL_FROM_NAME, $EMAIL_FROM){
		$this->EMAIL_FROM_NAME = $EMAIL_FROM_NAME;
		if (filter_var($EMAIL_FROM, FILTER_VALIDATE_EMAIL)) {
			$this->EMAIL_FROM = $EMAIL_FROM;
		}else{
			throw new Exception('Entered mail is not valid: '.$EMAIL_FROM);
		}
	}

	public function replyTo($REPLY_TO_NAME, $REPLY_TO){
		$this->REPLY_TO_NAME = $REPLY_TO_NAME;
		if (filter_var($REPLY_TO, FILTER_VALIDATE_EMAIL)) {
			$this->REPLY_TO = $REPLY_TO;
		}else{
			throw new Exception('Entered mail is not valid: '.$REPLY_TO);
		}
	}

	public function subject($SUBJECT){
		$this->SUBJECT = $SUBJECT;
	}

	private function createHeader(){
		$this->HEADER =
		"CONTENT-TRANSFER-Encoding: US-ASCII{$this->EOL}CONTENT-TYPE: multipart/mixed; boundary={$this->MARK}{$this->EOL}MIME-VERSION: {$this->MIME_VERSION}{$this->EOL}FROM: {$this->EMAIL_FROM_NAME}<{$this->EMAIL_FROM}>{$this->EOL}To: {$this->EMAIL_TO}{$this->EOL}Reply-To: {$this->REPLY_TO_NAME}<{$this->REPLY_TO}>{$this->EOL}MESSAGE_ID: {$this->MESSAGE_ID}{$this->EOL}X_MAILER: {$this->X_MAILER}{$this->EOL}Subject: {$this->SUBJECT}{$this->EOL}";
	}

	public function setHTMLMessage($MESSAGE){
		$this->MESSAGE .="{$this->EOL}--{$this->MARK}{$this->EOL}CONTENT-TRANSFER-ENCODING: binary{$this->EOL}CONTENT-TYPE: text/html; CHARSET=UTF-8{$this->EOL}{$this->EOL}{$MESSAGE}{$this->EOL}";
	}

	public function setPlainMessage($MESSAGE){
		$this->MESSAGE .=
		"--{$this->MARK}
		CONTENT-TRANSFER-ENCODING: binary
		CONTENT-TYPE: text/plain; CHARSET=UTF-8

		{$MESSAGE}
		";
	}

	public function setZIPAttachment($binary_file, $file_name){
		$this->MESSAGE .=
		"--{$this->MARK}
		CONTENT-TRANSFER-ENCODING: base64
		CONTENT-TYPE: application/zip; name='{$file_name}'

		{$binary_file}
		";
	}

	public function sendMail(){
		if( !(!empty($this->EMAIL_TO) && !empty($this->EMAIL_FROM) && !empty($this->REPLY_TO) && !empty($this->SUBJECT)) ){
			throw new Exception("For sending mail EMAIL_TO, EMAIL_FROM, REPLY_TO, SUBJECT must be declared");
		}

		$this->MESSAGE .= "{$this->EOL}--{$this->MARK}--{$this->EOL}.";

		self::createHeader();

		try{
			if(file_put_contents('test3.mail', $this->HEADER.$this->MESSAGE)){
				$address=$_SERVER['DOCUMENT_ROOT'].EXE_DIR;
				exec($address."/sendmail/sendmail -t < test3.mail");
				$errors = file($address."/sendmail/error.log", FILE_IGNORE_NEW_LINES);
				file_put_contents($address."/sendmail/error.log", "");
				if(!sizeof($errors)){
					file_put_contents("test3.mail", "");
					$stream = imap_open(MAIL_BOX, MAIL_USERNAME, MAIL_PASSWORD, NULL, 1, array('DISABLE_AUTHENTICATOR' => 'PLAIN')) or
					die(var_dump(imap_errors()));

					imap_append($stream, MAIL_BOX.".Sent", $this->HEADER.$this->MESSAGE);
					imap_close($stream);
					return array('errors_string', '', 'errors'=>'', 'error_occured'=>false);
				}
				return array('errors_string'=> implode(" | ", $errors), 'errors'=>$errors, 'error_occured'=>true);
			}else{
				return array('errors_string'=>"Couldn't create mail file", 'errors'=>array("Couldn't create mail file"), 'error_occured'=>true);;
			}
		}catch(Exception $ex){
			throw new Exception('Exception While Sending EMAIL: ' . $ex->getMessage());
		}
	}

// Old version
/*
	public function sendMail(){
		if( !(!empty($this->EMAIL_TO) && !empty($this->EMAIL_FROM) && !empty($this->REPLY_TO) && !empty($this->SUBJECT)) ){
			echo 'EMAIL_TO: '.$this->EMAIL_TO."<br>";
			echo 'EMAIL_FROM: '.$this->EMAIL_FROM."<br>";
			echo 'REPLY_TO: '.$this->REPLY_TO."<br>";
			echo 'SUBJECT: '.$this->SUBJECT."<br>";

			throw new Exception("For sending mail EMAIL_TO, EMAIL_FROM, REPLY_TO, SUBJECT must be declared");
		}

		$this->MESSAGE .= "\n--{$this->MARK}--";

		self::createHeader();

		try{
			if(mail($this->EMAIL_TO, $this->SUBJECT, $this->MESSAGE, $this->HEADER)){
				return true;
			}else{
				return false;
			}
		}catch(Exception $ex){
			throw new Exception('Exception While Sending EMAIL: ' . $ex->getMessage());
		}
	}*/

}
?>
