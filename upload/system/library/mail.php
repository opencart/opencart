<?php

require DIR_SYSTEM . 'helper/phpmailer/PHPMailerAutoload.php';

class Mail {
	protected $to;
	protected $from;
	protected $sender;
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
	public $smtp_secure = 'tls';

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
			trigger_error('Error: E-Mail to required!');
			exit();
		}

		if (!$this->from) {
			trigger_error('Error: E-Mail from required!');
			exit();
		}

		if (!$this->sender) {
			trigger_error('Error: E-Mail sender required!');
			exit();
		}

		if (!$this->subject) {
			trigger_error('Error: E-Mail subject required!');
			exit();
		}

		if ((!$this->text) && (!$this->html)) {
			trigger_error('Error: E-Mail message required!');
			exit();
		}

    $mail = new PHPMailer();

    // Set SMTP specific properties
    if ($this->protocol == 'smtp') {
      $mail->isSMTP();  //Tell PHPMailer to use SMTP
      $mail->Host = $this->smtp_hostname;
      $mail->Port = $this->smtp_port;
      $mail->SMTPAuth = true;
      $mail->Username = $this->smtp_username;
      $mail->Password = $this->smtp_password;
      $mail->Timeout = $this->smtp_timeout;
      $mail->SMTPSecure = $this->smtp_secure;
    }

    $mail->setFrom($this->from, $this->sender);
    if (is_array($this->to)) {
      foreach ($this->to as $to_addr) {
        $mail->addAddress($to_addr);
      }
    } else {
      $mail->addAddress($this->to);
    }
    $mail->addReplyTo($this->from, $this->sender);
    $mail->Subject = $this->subject;

    // Body
    if ($this->html) {
      $mail->isHTML(true);
      $mail->Body = $this->html;

      if ($this->text) {
        $mail->AltBody = $this->text;
      }
    } else {
      $mail->Body = $this->text;
    }

    // Attachments
    foreach ($this->attachments as $attachment) {
      if (file_exists($attachment)) {
        $mail->addAttachment($attachment);
      }
    }

    // Send the email
    if (!$mail->send()) {
      trigger_error('Error: ' . $mail->ErrorInfo);
      exit();
    }

  }
}
