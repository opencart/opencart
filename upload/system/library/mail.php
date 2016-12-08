<?php
class Mail {
	protected $to;
	protected $from;
	protected $sender;
	protected $reply_to;
	protected $subject;
	protected $text;
	protected $html;
	protected $attachments = array();
	public $protocol = 'mail';
	public $smtp_hostname;
	public $smtp_username;
	public $smtp_password;
	public $smtp_port = 25;
	public $smtp_timeout = 5;
	public $verp = false;
	public $parameter = '';

	public function __construct($config = array()) {
		foreach ($config as $key => $value) {
			$this->$key = $value;
		}
	}

	public function setTo($to) {
		$this->to = $to;
	}

	public function setFrom($from) {
		$this->from = $from;
	}

	public function setSender($sender) {
		$this->sender = $sender;
	}

	public function setReplyTo($reply_to) {
		$this->reply_to = $reply_to;
	}

	public function setSubject($subject) {
		$this->subject = $subject;
	}

	public function setText($text) {
		$this->text = $text;
	}

	public function setHtml($html) {
		$this->html = $html;
	}

	public function addAttachment($filename) {
		$this->attachments[] = $filename;
	}

	public function send() {
		if (!$this->to) {
			throw new \Exception('Error: E-Mail to required!');
		}

		if (!$this->from) {
			throw new \Exception('Error: E-Mail from required!');
		}

		if (!$this->sender) {
			throw new \Exception('Error: E-Mail sender required!');
		}

		if (!$this->subject) {
			throw new \Exception('Error: E-Mail subject required!');
		}

		if ((!$this->text) && (!$this->html)) {
			throw new \Exception('Error: E-Mail message required!');
		}

		if (is_array($this->to)) {
			$to = implode(',', $this->to);
		} else {
			$to = $this->to;
		}

		$mail = new PHPMailer;

		if($this->protocol == 'smtp') {

			if (substr($this->smtp_hostname, 0, 3) == 'tls') {
				$hostname = substr($this->smtp_hostname, 6);
			} else {
				$hostname = $this->smtp_hostname;
			}

			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = $hostname;  // Specify main and backup SMTP servers
			$mail->Helo = getenv('SERVER_NAME');
			$mail->Hostname = getenv('SERVER_NAME');

			if (!empty($this->smtp_username)  && !empty($this->smtp_password)) {
				$mail->SMTPAuth = true;                               // Enable SMTP authentication
				$mail->Username = $this->smtp_username;                 // SMTP username
				$mail->Password = $this->smtp_password;                           // SMTP password
			}

			if (substr($this->smtp_hostname, 0, 3) == 'tls') {
				$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
			}
			$mail->Port = $this->smtp_port;                                    // TCP port to connect to
		}

		$mail->setFrom($this->from);

		if (!is_array($to)) {
			$mail->addAddress((string)$this->to);     // Add a recipient
		} else {
			foreach($to as $addy) {
				$mail->addAddress((string)$addy);     // Add a recipient
			}
		}

		$mail->addReplyTo($this->from);

		foreach ($this->attachments as $attachment) {
			if (file_exists($attachment)) {
				$mail->addAttachment($attachment);         // Add attachments
			}
		}

		if (!$this->html) {
			$mail->isHTML(false);
			$mail->Body = $this->text . PHP_EOL;
		} else {
			$mail->isHTML(true);

			if ($this->text) {
				$altmessage = $this->text . PHP_EOL;
			} else {
				$altmessage = 'This is a HTML email and your email client software does not support HTML email!' . PHP_EOL;
			}

			$mail->Body    = $this->html . PHP_EOL;
			$mail->AltBody = $altmessage;
		}

		$mail->Subject = $this->subject;

		if(!$mail->send()) {
			throw new \Exception('Error: ' . $mail->ErrorInfo);
		}
	}
}
