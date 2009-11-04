<?php 
final class Mail {
	protected $to;
  	protected $from;
  	protected $sender;
  	protected $subject;
  	protected $text;
  	protected $html;
  	protected $attachments = array();
	protected $protocol = 'mail';
	protected $smtp_host = '';
	protected $smtp_username = '';
	protected $smtp_password = '';
	protected $smtp_port = '25';
	protected $smtp_timeout	= 5;
	
	public function __construct($protocol = 'mail', $smtp_host = '', $smtp_username = '', $smtp_password = '', $smtp_port = '25', $smtp_timeout = '5') {
		$this->protocol = $protocol;
		$this->smtp_host = $smtp_host;
		$this->smtp_username = $smtp_username;
		$this->smtp_password = $smtp_password;
		$this->smtp_port = $smtp_port;
		$this->smtp_timeout = $smtp_timeout;
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
	
  	public function addAttachment($attachments) {
    	$this->attachments[] = $attachments;
  	}

  	public function send() {	
    	if (!$this->to) {
      		exit('Error: E-Mail to required!');
    	}
	
    	if (!$this->from) {
      		exit('Error: E-Mail from required!');
    	}
    
    	if (!$this->sender) {
      		exit('Error: E-Mail sender required!');
    	}
		
		if (!$this->subject) {
      		exit('Error: E-Mail subject required!');
    	}
			
		if ((!$this->text) && (!$this->html)) {
      		exit('Error: E-Mail message required!');
    	}

		if (is_array($this->to)) {
      		$to = implode(',', $this->to);
    	} else {
      		$to = $this->to;
    	}
	  	
		$boundary = '----=_NextPart_' . md5(rand());  
	    
		if (strpos(PHP_OS, 'WIN') === false) {
			$eol = "\n";
		} else {
			$eol = "\r\n";
		}
		
		$headers  = 'From: ' . $this->sender . '<' . $this->from . '>' . $eol; 
    	$headers .= 'Reply-To: ' . $this->sender . '<' . $this->from . '>' . $eol;   
		$headers .= 'Return-Path: ' . $this->from . $eol;
    	$headers .= 'X-Mailer: PHP/' . phpversion() . $eol;  
    	$headers .= 'MIME-Version: 1.0' . $eol; 
    	$headers .= 'Content-Type: multipart/mixed; boundary="' . $boundary . '"' . $eol;  
	
		if (!$this->html) {
	  		$message  = '--' . $boundary . $eol;  
	  		$message .= 'Content-Type: text/plain; charset="utf-8"' . $eol; 
	  		$message .= 'Content-Transfer-Encoding: base64' . $eol . $eol;
      		$message .= chunk_split(base64_encode($this->text));
		} else {
	  		$message  = '--' . $boundary . $eol;
	  		$message .= 'Content-Type: multipart/alternative; boundary="' . $boundary . '_alt"' . $eol . $eol;
	  		$message .= '--' . $boundary . '_alt' . $eol;
	  		$message .= 'Content-Type: text/plain; charset="utf-8"' . $eol; 
	  		$message .= 'Content-Transfer-Encoding: base64' . $eol;
	  
	  		if ($this->text) {
        		$message .= chunk_split(base64_encode($this->text));
	  		} else {
	    		$message .= chunk_split(base64_encode('This is a HTML email and your email client software does not support HTML email!'));
      		}	
	  
	  		$message .= '--' . $boundary . '_alt' . $eol;
      		$message .= 'Content-Type: text/html; charset="utf-8"' . $eol; 
      		$message .= 'Content-Transfer-Encoding: base64' . $eol . $eol;
	  		$message .= chunk_split(base64_encode($this->html)); 
			$message .= '--' . $boundary . '_alt--' . $eol;		 
		}
		
    	foreach ($this->attachments as $attachment) {  
      		$filename = basename($attachment);  
      		$handle = fopen($attachment, 'r'); 
      		$content = fread($handle, filesize($attachment));
      
	  		fclose($handle);  
	  
      		$message .= '--' . $boundary . $eol;
      		$message .= 'Content-Type: application/octetstream' . $eol;    
      		$message .= 'Content-Transfer-Encoding: base64' . $eol; 
      		$message .= 'Content-Disposition: attachment; filename="' . $filename . '"' . $eol; 
      		$message .= 'Content-ID: <' . $filename . '>' . $eol . $eol;
      		$message .= chunk_split(base64_encode($content));
    	}  

		if ($this->protocol == 'mail') {
			ini_set('sendmail_from', $this->from);
		
    		mail($to, $this->subject, $message, $headers);  
		} elseif ($this->protocol == 'smtp') {
			$fp = fsockopen($this->smtp_host, $this->smtp_port, $errno, $errstr, $this->smtp_timeout);	
			
			if (!$fp) {
				exit('Error: ' . $errstr . ' (' . $errno . ')');
			} else {
				$response = fgets($fp, 515);
				
				if (substr($response, 0, 3) != 220) {
					exit(' ' . substr(trim($response), 3));
				}
				
				if (!empty($this->smtp_username)  && !empty($this->smtp_password)) {
 					fputs($fp, 'EHLO ' . getenv('SERVER_ADDR') . $eol);
					
					$response = fgets($fp, 515);

					if (substr($response, 0, 3) != 250) {
						exit('Error: EHLO command failed!');
					}	
					
					fputs($fp, 'AUTH LOGIN ' . $eol);
				
					$response = fgets($fp, 515);
				
					if (substr($response, 0, 3) != 334) {
						exit('Error: AUTH LOGIN command failed!');
					}	
				
					fputs($fp, base64_encode($this->smtp_username) . $eol);
				
					$response = fgets($fp, 515);

					if (substr($response, 0, 3) != 334) {
						exit('Error: SMTP username command failed!');
					}
					
					fputs($fp, base64_encode($this->smtp_password) . $eol);
				
					$response = fgets($fp, 515);
					
					if (substr($response, 0, 3) != 235) {
						exit('Error: SMTP password command failed!');					
					}	
				} else {
					fputs($fp, 'HELO ' . getenv('SERVER_ADDR') . $eol);
					
					$response = fgets($fp, 515);

					if (substr($response, 0, 3) != 250) {
						exit('Error: HELO command failed!');
					}					
				}
				
				fputs($fp, 'MAIL FROM: <' . $this->from . '>' . $eol);
				
				$response = fgets($fp, 515);
					
				if (substr($response, 0, 3) != 250) {
					exit('Error: MAIL FROM command failed!');
				}
					
				if (!is_array($this->to)) {
					fputs($fp, 'RCPT TO: <' . $to . '>' . $eol);

					$response = fgets($fp, 515);
					
					if (substr($response, 0, 3) != 250) {
						exit('Error: RCPT TO command failed!');
					}
				} else {
					foreach ($this->to as $recipient) {
						fputs($fp, 'RCPT TO: <' . $recipient . '>' . $eol);
						
						$response = fgets($fp, 515);
				
						if (substr($response, 0, 3) != 250) {
							exit('Error: RCPT TO command failed!');
						}				
					}					
				}
				
				fputs($fp, 'DATA' . $eol);
				
				$response = fgets($fp, 515);

				if (substr($response, 0, 3) != 354) {
					exit('Error: DATA command failed!');
				}
				
				fputs($fp, 'To: ' . $to . $eol);
				fputs($fp, 'Subject: ' . $this->subject . $eol);
				fputs($fp, $headers . $message . $eol);
				fputs($fp, '.' . $eol);

				$response = fgets($fp, 515);

				if (substr($response, 0, 3) != 250) {
					exit('Error: Message could not be sent!');
				}
				
				fputs($fp, 'QUIT' . $eol);
				
				fclose($fp);
			}
		}
	} 
}
?>