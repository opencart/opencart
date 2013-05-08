<?php
class Mail {
	protected $to;
	protected $from;
	protected $sender;
	protected $subject;
	protected $text;
	protected $html;
	protected $attachments = array();
	public $protocol = 'mail';
	public $hostname;
	public $username;
	public $password;
	public $port = 25;
	public $timeout = 5;
	public $newline = "\n";
	public $crlf = "\r\n";
	public $verp = false;
	public $parameter = '';

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
		
		if (is_array($this->to)) {
			$to = $this->to;
		} else if(strpos($this->to, ';') !== false) {
			$to = explode(';', $this->to);
		} else {
			$to = $this->to;
		}
		
		$to = array_filter(array_map('trim', $to)); //trim all 'to' emails and remove blank array items
		$sender = html_entity_decode($this->sender);
		
		$message = Swift_Message::newInstance()
			->setSubject($this->subject)
			->setFrom(array($this->from => $sender))
			->setTo($to);
		
		if ($this->html) {
			$message->setBody($this->text . $this->newline . 'This is a HTML email and your email client software does not support HTML email!');
			$message->addPart($this->html, 'text/html');
		} else {
			$message->setBody($this->text);
		}
		
		foreach ($this->attachments as $attachment) {
			if (file_exists($attachment)) {                    
				$message->attach(Swift_Attachment::fromPath($attachment));
			}
		}
		
		$result = false;
		$failures = array();
	
		if ($this->protocol == 'mail') {
	
			$transport = Swift_MailTransport::newInstance();
			$mailer = Swift_Mailer::newInstance($transport);
			
			$result = $mailer->send($message, $failures);
	
		} elseif ($this->protocol == 'smtp') {
			
			$transport = Swift_SmtpTransport::newInstance($this->hostname, $this->port)
				->setUsername($this->username)
				->setPassword($this->password);
			
			$mailer = Swift_Mailer::newInstance($transport);
			$result = $mailer->send($message, $failures);
		}
	
		if(!$result) {
			error_log('Failed to send email: ' . print_r($failures, true));
		}
	}
}
?>