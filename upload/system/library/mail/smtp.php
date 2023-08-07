<?php
/**
 * Basic SMTP mail class
 */
namespace Opencart\System\Library\Mail;
/**
 * Class Smtp
 */
class Smtp {
	/**
	 * @var array
	 */
	protected array $option = [];
	/**
	 * @var array|int[]
	 */
	protected array $default = [
		'smtp_port'     => 25,
		'smtp_timeout'  => 5,
		'max_attempts'  => 3,
		'verp'          => false
	];

	/**
	 * Constructor
	 *
	 * @param    array  $option
	 */
	public function __construct(array &$option = []) {
		foreach ($this->default as $key => $value) {
			if (!isset($option[$key])) {
				$option[$key] = $value;
			}
		}

		$this->option = &$option;
	}

	/**
	 * Send
	 *
	 * @return    bool
	 */
	public function send(): bool {
		if (empty($this->option['smtp_hostname'])) {
			throw new \Exception('Error: SMTP hostname required!');
		}

		if (empty($this->option['smtp_username'])) {
			throw new \Exception('Error: SMTP username required!');
		}

		if (empty($this->option['smtp_password'])) {
			throw new \Exception('Error: SMTP password required!');
		}

		if (empty($this->option['smtp_port'])) {
			throw new \Exception('Error: SMTP port required!');
		}

		if (empty($this->option['smtp_timeout'])) {
			throw new \Exception('Error: SMTP timeout required!');
		}

		if (is_array($this->option['to'])) {
			$to = implode(',', $this->option['to']);
		} else {
			$to = $this->option['to'];
		}

		$boundary = '----=_NextPart_' . md5(time());

		$header = 'MIME-Version: 1.0' . PHP_EOL;
		$header .= 'To: <' . $to . '>' . PHP_EOL;
		$header .= 'Subject: =?UTF-8?B?' . base64_encode($this->option['subject']) . '?=' . PHP_EOL;
		$header .= 'Date: ' . date('D, d M Y H:i:s O') . PHP_EOL;
		$header .= 'From: =?UTF-8?B?' . base64_encode($this->option['sender']) . '?= <' . $this->option['from'] . '>' . PHP_EOL;

		if (empty($this->option['reply_to'])) {
			$header .= 'Reply-To: =?UTF-8?B?' . base64_encode($this->option['sender']) . '?= <' . $this->option['from'] . '>' . PHP_EOL;
		} else {
			$header .= 'Reply-To: =?UTF-8?B?' . base64_encode($this->option['reply_to']) . '?= <' . $this->option['reply_to'] . '>' . PHP_EOL;
		}

		$header .= 'Return-Path: ' . $this->option['from'] . PHP_EOL;
		$header .= 'X-Mailer: PHP/' . phpversion() . PHP_EOL;
		$header .= 'Content-Type: multipart/mixed; boundary="' . $boundary . '"' . PHP_EOL . PHP_EOL;

		$message = '--' . $boundary . PHP_EOL;

		if (empty($this->option['html'])) {
			$message .= 'Content-Type: text/plain; charset="utf-8"' . PHP_EOL;
			$message .= 'Content-Transfer-Encoding: base64' . PHP_EOL . PHP_EOL;
			$message .= chunk_split(base64_encode($this->option['text']), 950) . PHP_EOL;
		} else {
			$message .= 'Content-Type: multipart/alternative; boundary="' . $boundary . '_alt"' . PHP_EOL . PHP_EOL;
			$message .= '--' . $boundary . '_alt' . PHP_EOL;
			$message .= 'Content-Type: text/plain; charset="utf-8"' . PHP_EOL;
			$message .= 'Content-Transfer-Encoding: base64' . PHP_EOL . PHP_EOL;

			if (!empty($this->option['text'])) {
				$message .= chunk_split(base64_encode($this->option['text']), 950) . PHP_EOL;
			} else {
				$message .= chunk_split(base64_encode('This is a HTML email and your email client software does not support HTML email!'), 950) . PHP_EOL;
			}

			$message .= '--' . $boundary . '_alt' . PHP_EOL;
			$message .= 'Content-Type: text/html; charset="utf-8"' . PHP_EOL;
			$message .= 'Content-Transfer-Encoding: base64' . PHP_EOL . PHP_EOL;
			$message .= chunk_split(base64_encode($this->option['html']), 950) . PHP_EOL;
			$message .= '--' . $boundary . '_alt--' . PHP_EOL;
		}

		if (!empty($this->option['attachments'])) {
			foreach ($this->option['attachments'] as $attachment) {
				if (is_file($attachment)) {
					$handle = fopen($attachment, 'r');

					$content = fread($handle, filesize($attachment));

					fclose($handle);

					$message .= '--' . $boundary . PHP_EOL;
					$message .= 'Content-Type: application/octet-stream; name="' . basename($attachment) . '"' . PHP_EOL;
					$message .= 'Content-Transfer-Encoding: base64' . PHP_EOL;
					$message .= 'Content-Disposition: attachment; filename="' . basename($attachment) . '"' . PHP_EOL;
					$message .= 'Content-ID: <' . urlencode(basename($attachment)) . '>' . PHP_EOL;
					$message .= 'X-Attachment-Id: ' . urlencode(basename($attachment)) . PHP_EOL . PHP_EOL;
					$message .= chunk_split(base64_encode($content), 950);
				}
			}
		}

		$message .= '--' . $boundary . '--' . PHP_EOL;

		if (substr($this->option['smtp_hostname'], 0, 3) == 'tls') {
			$hostname = substr($this->option['smtp_hostname'], 6);
		} else {
			$hostname = $this->option['smtp_hostname'];
		}

		$handle = fsockopen($hostname, $this->option['smtp_port'], $errno, $errstr, $this->option['smtp_timeout']);

		if ($handle) {
			if (substr(PHP_OS, 0, 3) != 'WIN') {
				socket_set_timeout($handle, $this->option['smtp_timeout'], 0);
			}

			while ($line = fgets($handle, 515)) {
				if (substr($line, 3, 1) == ' ') {
					break;
				}
			}

			fputs($handle, 'EHLO ' . getenv('SERVER_NAME') . "\r\n");

			$reply = '';

			while ($line = fgets($handle, 515)) {
				$reply .= $line;

				//some SMTP servers respond with 220 code before responding with 250. hence, we need to ignore 220 response string
				if (substr($reply, 0, 3) == 220 && substr($line, 3, 1) == ' ') {
					$reply = '';

					continue;
				} elseif (substr($line, 3, 1) == ' ') {
					break;
				}
			}

			if (substr($reply, 0, 3) != 250) {
				throw new \Exception('Error: EHLO not accepted from server!');
			}

			if (substr($this->option['smtp_hostname'], 0, 3) == 'tls') {
				fputs($handle, 'STARTTLS' . "\r\n");

				$this->handleReply($handle, 220, 'Error: STARTTLS not accepted from server!');

				if (stream_socket_enable_crypto($handle, true, STREAM_CRYPTO_METHOD_TLS_CLIENT) !== true) {
					throw new \Exception('Error: TLS could not be established!');
				}

				fputs($handle, 'EHLO ' . getenv('SERVER_NAME') . "\r\n");

				$this->handleReply($handle, 250, 'Error: EHLO not accepted from server!');
			}

			if (!empty($this->option['smtp_username']) && !empty($this->option['smtp_password'])) {
				fputs($handle, 'AUTH LOGIN' . "\r\n");

				$this->handleReply($handle, 334, 'Error: AUTH LOGIN not accepted from server!');

				fputs($handle, base64_encode($this->option['smtp_username']) . "\r\n");

				$this->handleReply($handle, 334, 'Error: Username not accepted from server!');

				fputs($handle, base64_encode($this->option['smtp_password']) . "\r\n");

				$this->handleReply($handle, 235, 'Error: Password not accepted from server!');

			} else {
				fputs($handle, 'HELO ' . getenv('SERVER_NAME') . "\r\n");

				$this->handleReply($handle, 250, 'Error: HELO not accepted from server!');
			}

			if ($this->option['verp']) {
				fputs($handle, 'MAIL FROM: <' . $this->option['from'] . '>XVERP' . "\r\n");
			} else {
				fputs($handle, 'MAIL FROM: <' . $this->option['from'] . '>' . "\r\n");
			}

			$this->handleReply($handle, 250, 'Error: MAIL FROM not accepted from server!');

			if (!is_array($this->option['to'])) {
				fputs($handle, 'RCPT TO: <' . $this->option['to'] . '>' . "\r\n");

				$reply = $this->handleReply($handle, false, 'RCPT TO [!array]');

				if ((substr($reply, 0, 3) != 250) && (substr($reply, 0, 3) != 251)) {
					throw new \Exception('Error: RCPT TO not accepted from server!');
				}
			} else {
				foreach ($this->option['to'] as $recipient) {
					fputs($handle, 'RCPT TO: <' . $recipient . '>' . "\r\n");

					$reply = $this->handleReply($handle, false, 'RCPT TO [array]');

					if ((substr($reply, 0, 3) != 250) && (substr($reply, 0, 3) != 251)) {
						throw new \Exception('Error: RCPT TO not accepted from server!');
					}
				}
			}

			fputs($handle, 'DATA' . "\r\n");

			$this->handleReply($handle, 354, 'Error: DATA not accepted from server!');

			// According to rfc 821 we should not send more than 1000 including the CRLF
			$message = str_replace("\r\n", "\n", $header . $message);
			$message = str_replace("\r", "\n", $message);

			$lines = explode("\n", $message);

			foreach ($lines as $line) {
				// see https://php.watch/versions/8.2/str_split-empty-string-empty-array
				$results = ($line === '') ? [''] : str_split($line, 998);

				foreach ($results as $result) {
					fputs($handle, $result . "\r\n");
				}
			}

			fputs($handle, '.' . "\r\n");

			$this->handleReply($handle, 250, 'Error: DATA not accepted from server!');

			fputs($handle, 'QUIT' . "\r\n");

			$this->handleReply($handle, 221, 'Error: QUIT not accepted from server!');

			fclose($handle);

			return true;
		} else {
			throw new \Exception('Error: ' . $errstr . ' (' . $errno . ')');

			return false;
		}
	}

	/**
	 * handleReply
	 *
	 * @param	array	$handle
	 * @param	bool	$status_code
	 * @param	bool	$error_text
	 * @param	int		$counter
	 *
	 * @return      string
	 */
	private function handleReply($handle, $status_code = false, $error_text = false, $counter = 0) {
		$reply = '';

		while (($line = fgets($handle, 515)) !== false) {
			$reply .= $line;

			if (substr($line, 3, 1) == ' ') {
				break;
			}
		}

		// Handle slowish server responses (generally due to policy servers)
		if (!$line && empty($reply) && $counter < $this->option['max_attempts']) {
			sleep(1);

			$counter++;

			return $this->handleReply($handle, $status_code, $error_text, $counter);
		}

		if ($status_code) {
			if (substr($reply, 0, 3) != $status_code) {
				throw new \Exception($error_text);
			}
		}

		return $reply;
	}
}
