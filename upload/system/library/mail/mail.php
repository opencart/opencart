<?php
/**
 * Basic PHP mail class
 */
namespace Opencart\System\Library\Mail;
class Mail {
	protected array $option = [];

	/**
	 * Constructor
	 *
	 * @param    array  $option
	 */
	public function __construct(array &$option = []) {
		$this->option = $option;
	}

	/**
	 * Send
	 *
	 * @return    bool
	 */
	public function send(): bool {
		print_r($this->option);

		if (is_array($this->option['to'])) {
			$to = implode(',', $this->option['to']);
		} else {
			$to = $this->option['to'];
		}

		$boundary = '----=_NextPart_' . md5(time());

		$header  = 'MIME-Version: 1.0' . PHP_EOL;
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

		if (empty($this->option['html'])) {
			$message  = '--' . $boundary . PHP_EOL;
			$message .= 'Content-Type: text/plain; charset="utf-8"' . PHP_EOL;
			$message .= 'Content-Transfer-Encoding: base64' . PHP_EOL . PHP_EOL;
			$message .= base64_encode($this->option['text']) . PHP_EOL;
		} else {
			$message  = '--' . $boundary . PHP_EOL;
			$message .= 'Content-Type: multipart/alternative; boundary="' . $boundary . '_alt"' . PHP_EOL . PHP_EOL;
			$message .= '--' . $boundary . '_alt' . PHP_EOL;
			$message .= 'Content-Type: text/plain; charset="utf-8"' . PHP_EOL;
			$message .= 'Content-Transfer-Encoding: base64' . PHP_EOL . PHP_EOL;

			if (!empty($this->option['text'])) {
				$message .= base64_encode($this->option['text']) . PHP_EOL;
			} else {
				$message .= base64_encode('This is a HTML email and your email client software does not support HTML email!') . PHP_EOL;
			}

			$message .= '--' . $boundary . '_alt' . PHP_EOL;
			$message .= 'Content-Type: text/html; charset="utf-8"' . PHP_EOL;
			$message .= 'Content-Transfer-Encoding: base64' . PHP_EOL . PHP_EOL;
			$message .= base64_encode($this->option['html']) . PHP_EOL;
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
					$message .= chunk_split(base64_encode($content));
				}
			}
		}

		$message .= '--' . $boundary . '--' . PHP_EOL;

		ini_set('sendmail_from', $this->option['from']);

		if (!empty($this->option['parameter'])) {
			return mail($to, '=?UTF-8?B?' . base64_encode($this->option['subject']) . '?=', $message, $header, $this->option['parameter']);
		} else {
			return mail($to, '=?UTF-8?B?' . base64_encode($this->option['subject']) . '?=', $message, $header);
		}
	}
}