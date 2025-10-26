<?php
namespace Opencart\System\Library\Mail;
/**
 * Class SMTP
 *
 * Basic SMTP mail class
 */
class Smtp {
	/**
	 * @var string
	 */
	protected string|array $to = '';
	/**
	 * @var string
	 */
	protected string $from = '';
	/**
	 * @var string
	 */
	protected string $sender = '';
	/**
	 * @var string
	 */
	protected string $reply_to = '';
	/**
	 * @var string
	 */
	protected string $subject = '';
	/**
	 * @var string
	 */
	protected string $text = '';
	/**
	 * @var string
	 */
	protected string $html = '';
	/**
	 * @var string
	 */
	protected string $parameter = '';
	/**
	 * @var string
	 */
	protected string $smtp_hostname = '';
	/**
	 * @var string
	 */
	protected string $smtp_username = '';
	/**
	 * @var string
	 */
	protected string $smtp_password = '';
	/**
	 * @var int
	 */
	protected int $smtp_port = 25;
	/**
	 * @var int
	 */
	protected int $smtp_timeout = 5;
	/**
	 * @var int
	 */
	protected int $max_attempts = 3;
	/**
	 * @var bool
	 */
	protected bool $verp = false;

	/**
	 * Constructor
	 *
	 * @param array<string, mixed> $option
	 */
	public function __construct(array $option = []) {
		foreach ($option as $key => $value) {
			$this->{$key} = $value;
		}
	}

	/**
	 * Set To
	 *
	 * @param array<string>|string $to
	 *
	 * @return void
	 */
	public function setTo(string|array $to): void {
		$this->to = $to;
	}

	/**
	 * Set From
	 *
	 * @param string $from
	 *
	 * @return void
	 */
	public function setFrom(string $from): void {
		$this->from = $from;
	}

	/**
	 * Set Sender
	 *
	 * @param string $sender
	 *
	 * @return void
	 */
	public function setSender(string $sender): void {
		$this->sender = $sender;
	}

	/**
	 * Set Reply To
	 *
	 * @param string $reply_to
	 *
	 * @return void
	 */
	public function setReplyTo(string $reply_to): void {
		$this->reply_to = $reply_to;
	}

	/**
	 * Set Subject
	 *
	 * @param string $subject
	 *
	 * @return void
	 */
	public function setSubject(string $subject): void {
		$this->subject = $subject;
	}

	/**
	 * Set Text
	 *
	 * @param string $text
	 *
	 * @return void
	 */
	public function setText(string $text): void {
		$this->text = $text;
	}

	/**
	 * Set Html
	 *
	 * @param string $html
	 *
	 * @return void
	 */
	public function setHtml(string $html): void {
		$this->html = $html;
	}

	/**
	 * Send
	 *
	 * @return bool
	 */
	public function send(): bool {
		if (empty($this->to)) {
			throw new \Exception('Error: E-Mail to required!');
		}

		if (empty($this->from)) {
			throw new \Exception('Error: E-Mail from required!');
		}

		if (empty($this->sender)) {
			throw new \Exception('Error: E-Mail sender required!');
		}

		if (empty($this->subject)) {
			throw new \Exception('Error: E-Mail subject required!');
		}

		if (empty($this->text) && empty($this->html)) {
			throw new \Exception('Error: E-Mail message required!');
		}

		if (empty($this->smtp_hostname)) {
			throw new \Exception('Error: SMTP hostname required!');
		}

		if (empty($this->smtp_username)) {
			throw new \Exception('Error: SMTP username required!');
		}

		if (empty($this->smtp_password)) {
			throw new \Exception('Error: SMTP password required!');
		}

		if (empty($this->smtp_port)) {
			throw new \Exception('Error: SMTP port required!');
		}

		if (empty($this->smtp_timeout)) {
			throw new \Exception('Error: SMTP timeout required!');
		}

		$servername = parse_url(HTTP_SERVER, PHP_URL_HOST);

		if (!is_array($this->to)) {
			$to = $this->to;
		} else {
			$to = implode(',', $this->to);
		}

		$boundary = '----=_NextPart_' . md5((string)time());

		// Header
		$header  = 'MIME-Version: 1.0' . PHP_EOL;
		$header .= 'To: <' . $to . '>' . PHP_EOL;
		$header .= 'Subject: =?UTF-8?B?' . base64_encode($this->subject) . '?=' . PHP_EOL;
		$header .= 'Date: ' . date('D, d M Y H:i:s O') . PHP_EOL;
		$header .= 'From: =?UTF-8?B?' . base64_encode($this->sender) . '?= <' . $this->from . '>' . PHP_EOL;

		if (empty($this->reply_to)) {
			$header .= 'Reply-To: =?UTF-8?B?' . base64_encode($this->sender) . '?= <' . $this->from . '>' . PHP_EOL;
		} else {
			$header .= 'Reply-To: =?UTF-8?B?' . base64_encode($this->reply_to) . '?= <' . $this->reply_to . '>' . PHP_EOL;
		}

		$header .= 'Message-ID: <' . base_convert(str_replace(['.', ' '], '', microtime()), 10, 36) . '.' . base_convert(bin2hex(openssl_random_pseudo_bytes(8)), 16, 36) . substr($this->from, strrpos($this->from, '@')) . '>' . PHP_EOL;
		$header .= 'Return-Path: ' . $this->from . PHP_EOL;
		$header .= 'X-Mailer: PHP/' . PHP_VERSION . PHP_EOL;
		$header .= 'Content-Type: multipart/mixed; boundary="' . $boundary . '"' . PHP_EOL . PHP_EOL;

		// Message
		$message = '--' . $boundary . PHP_EOL;

		if (empty($this->html)) {
			$message .= 'Content-Type: text/plain; charset="utf-8"' . PHP_EOL;
			$message .= 'Content-Transfer-Encoding: base64' . PHP_EOL . PHP_EOL;

			$message .= chunk_split(base64_encode($this->text)) . PHP_EOL;
		} else {
			$message .= 'Content-Type: multipart/alternative; boundary="' . $boundary . '_alt"' . PHP_EOL . PHP_EOL;
			$message .= '--' . $boundary . '_alt' . PHP_EOL;
			$message .= 'Content-Type: text/plain; charset="utf-8"' . PHP_EOL;
			$message .= 'Content-Transfer-Encoding: base64' . PHP_EOL . PHP_EOL;

			if (!empty($this->text)) {
				$message .= chunk_split(base64_encode($this->text)) . PHP_EOL;
			} else {
				$message .= chunk_split(base64_encode(strip_tags($this->html))) . PHP_EOL;
			}

			$message .= '--' . $boundary . '_alt' . PHP_EOL;
			$message .= 'Content-Type: text/html; charset="utf-8"' . PHP_EOL;
			$message .= 'Content-Transfer-Encoding: base64' . PHP_EOL . PHP_EOL;
			$message .= chunk_split(base64_encode($this->html)) . PHP_EOL;
			$message .= '--' . $boundary . '_alt--' . PHP_EOL;
		}

		if (!empty($this->attachments)) {
			foreach ($this->attachments as $attachment) {
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
					$message .= chunk_split(base64_encode($content));
				}
			}
		}

		$message .= '--' . $boundary . '--' . PHP_EOL;

		if (substr($this->smtp_hostname, 0, 3) == 'tls') {
			$hostname = substr($this->smtp_hostname, 6);
		} else {
			$hostname = $this->smtp_hostname;
		}

		$handle = fsockopen($hostname, $this->smtp_port, $errno, $errstr, $this->smtp_timeout);

		if (!$handle) {
			throw new \Exception('Error: ' . $errstr . ' (' . $errno . ')');
		}

		if (substr(PHP_OS, 0, 3) != 'WIN') {
			stream_set_timeout($handle, $this->smtp_timeout, 0);
		}

		while ($line = fgets($handle, 515)) {
			if (substr($line, 3, 1) == ' ') {
				break;
			}
		}

		fwrite($handle, 'EHLO ' . $servername . "\r\n");

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
			throw new \Exception('Error: ' . $reply);
		}

		if (substr($this->smtp_hostname, 0, 3) == 'tls') {
			fwrite($handle, 'STARTTLS' . "\r\n");

			$this->handleReply($handle, 220, 'Error: STARTTLS not accepted from server!');

			if (stream_socket_enable_crypto($handle, true, STREAM_CRYPTO_METHOD_TLS_CLIENT) !== true) {
				throw new \Exception('Error: TLS could not be established!');
			}

			fwrite($handle, 'EHLO ' . $servername . "\r\n");

			$this->handleReply($handle, 250, 'Error: EHLO not accepted from server!');
		}

		fwrite($handle, 'AUTH LOGIN' . "\r\n");

		$this->handleReply($handle, 334, 'Error: AUTH LOGIN not accepted from server!');

		fwrite($handle, base64_encode($this->smtp_username) . "\r\n");

		$this->handleReply($handle, 334, 'Error: Username not accepted from server!');

		fwrite($handle, base64_encode($this->smtp_password) . "\r\n");

		$this->handleReply($handle, 235, 'Error: Password not accepted from server!');

		if ($this->verp) {
			fwrite($handle, 'MAIL FROM: <' . $this->from . '>XVERP' . "\r\n");
		} else {
			fwrite($handle, 'MAIL FROM: <' . $this->from . '>' . "\r\n");
		}

		$this->handleReply($handle, 250, 'Error: MAIL FROM not accepted from server!');

		$recipients = [];

		if (!is_array($this->to)) {
			$recipients[] = $this->to;
		} else {
			$recipients = $this->to;
		}

		foreach ($recipients as $recipient) {
			fwrite($handle, 'RCPT TO: <' . $recipient . '>' . "\r\n");

			$reply = $this->handleReply($handle, false, 'RCPT TO [array]');

			if ((substr($reply, 0, 3) != 250) && (substr($reply, 0, 3) != 251)) {
				throw new \Exception('Error: RCPT TO not accepted from server!');
			}
		}

		fwrite($handle, 'DATA' . "\r\n");

		$this->handleReply($handle, 354, 'Error: DATA not accepted from server!');

		// According to rfc 821 we should not send more than 1000 including the CRLF
		$message = str_replace("\r\n", "\n", $header . $message);
		$message = str_replace("\r", "\n", $message);

		$lines = explode("\n", $message);

		foreach ($lines as $line) {
			// see https://php.watch/versions/8.2/str_split-empty-string-empty-array
			$results = ($line === '') ? [''] : str_split($line, 998);

			foreach ($results as $result) {
				fwrite($handle, $result . "\r\n");
			}
		}

		fwrite($handle, '.' . "\r\n");

		$this->handleReply($handle, 250, 'Error: DATA not accepted from server!');

		fclose($handle);

		return true;
	}

	/**
	 * Handle Reply
	 *
	 * @param resource     $handle
	 * @param false|int    $status_code
	 * @param false|string $error_text
	 * @param int          $counter
	 *
	 * @return string
	 */
	private function handleReply($handle, $status_code = false, $error_text = false, int $counter = 0): string {
		$reply = '';

		while (($line = fgets($handle, 515)) !== false) {
			$reply .= $line;

			if (substr($line, 3, 1) == ' ') {
				break;
			}
		}

		// Handle slowish server responses (generally due to policy servers)
		if (!$line && empty($reply) && $counter < $this->max_attempts) {
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
