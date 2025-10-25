<?php
namespace Opencart\System\Library\Mail;
/**
 * Class Mail
 *
 * Basic PHP mail class
 */
class Mail {
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

		if (!is_array($this->to)) {
			$to = $this->to;
		} else {
			$to = implode(',', $this->to);
		}

		$boundary = '----=_NextPart_' . md5((string)time());

		// Header
		$header  = 'MIME-Version: 1.0' . PHP_EOL;
		$header .= 'Date: ' . date('D, d M Y H:i:s O') . PHP_EOL;
		$header .= 'From: =?UTF-8?B?' . base64_encode($this->sender) . '?= <' . $this->from . '>' . PHP_EOL;

		if (empty($this->reply_to)) {
			$header .= 'Reply-To: =?UTF-8?B?' . base64_encode($this->sender) . '?= <' . $this->from . '>' . PHP_EOL;
		} else {
			$header .= 'Reply-To: =?UTF-8?B?' . base64_encode($this->reply_to) . '?= <' . $this->reply_to . '>' . PHP_EOL;
		}

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

		ini_set('sendmail_from', $this->from);

		if (!empty($this->parameter)) {
			return mail($to, '=?UTF-8?B?' . base64_encode($this->subject) . '?=', $message, $header, $this->parameter);
		} else {
			return mail($to, '=?UTF-8?B?' . base64_encode($this->subject) . '?=', $message, $header);
		}
	}
}
