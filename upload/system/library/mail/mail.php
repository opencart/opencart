<?php
namespace Opencart\System\Library\Mail;
class Mail {
	protected string $to = '';
	protected string $from = '';
	protected string $sender = '';
	protected string $reply_to = '';
	protected string $subject = '';
	protected string $text = '';
	protected string $html = '';
	protected array $attachments = [];
	protected string $parameter;

	public function __construct(array $args) {
		foreach ($args as $key => $value) {
			if (property_exists($this, $key)) {
				$this->{$key} = $value;
			}
		}
	}

	public function send(): bool {
		if (is_array($this->to)) {
			$to = implode(',', $this->to);
		} else {
			$to = $this->to;
		}

		if (version_compare(phpversion(), '8.0', '>=') || substr(PHP_OS, 0, 3) == 'WIN') {
			$eol = "\r\n";
		} else {
			$eol = PHP_EOL;
		}

		$boundary = '----=_NextPart_' . md5(time());

		$header  = 'MIME-Version: 1.0' . $eol;
		$header .= 'Date: ' . date('D, d M Y H:i:s O') . $eol;
		$header .= 'From: =?UTF-8?B?' . base64_encode($this->sender) . '?= <' . $this->from . '>' . $eol;

		if (!$this->reply_to) {
			$header .= 'Reply-To: =?UTF-8?B?' . base64_encode($this->sender) . '?= <' . $this->from . '>' . $eol;
		} else {
			$header .= 'Reply-To: =?UTF-8?B?' . base64_encode($this->reply_to) . '?= <' . $this->reply_to . '>' . $eol;
		}

		$header .= 'Return-Path: ' . $this->from . $eol;
		$header .= 'X-Mailer: PHP/' . phpversion() . $eol;
		$header .= 'Content-Type: multipart/mixed; boundary="' . $boundary . '"' . $eol . $eol;

		if (!$this->html) {
			$message  = '--' . $boundary . $eol;
			$message .= 'Content-Type: text/plain; charset="utf-8"' . $eol;
			$message .= 'Content-Transfer-Encoding: base64' . $eol . $eol;
			$message .= base64_encode($this->text) . $eol;
		} else {
			$message  = '--' . $boundary . $eol;
			$message .= 'Content-Type: multipart/alternative; boundary="' . $boundary . '_alt"' . $eol . $eol;
			$message .= '--' . $boundary . '_alt' . $eol;
			$message .= 'Content-Type: text/plain; charset="utf-8"' . $eol;
			$message .= 'Content-Transfer-Encoding: base64' . $eol . $eol;

			if ($this->text) {
				$message .= base64_encode($this->text) . $eol;
			} else {
				$message .= base64_encode('This is a HTML email and your email client software does not support HTML email!') . $eol;
			}

			$message .= '--' . $boundary . '_alt' . $eol;
			$message .= 'Content-Type: text/html; charset="utf-8"' . $eol;
			$message .= 'Content-Transfer-Encoding: base64' . $eol . $eol;
			$message .= base64_encode($this->html) . $eol;
			$message .= '--' . $boundary . '_alt--' . $eol;
		}

		foreach ($this->attachments as $attachment) {
			if (is_file($attachment)) {
				$handle = fopen($attachment, 'r');

				$content = fread($handle, filesize($attachment));

				fclose($handle);

				$message .= '--' . $boundary . $eol;
				$message .= 'Content-Type: application/octet-stream; name="' . basename($attachment) . '"' . $eol;
				$message .= 'Content-Transfer-Encoding: base64' . $eol;
				$message .= 'Content-Disposition: attachment; filename="' . basename($attachment) . '"' . $eol;
				$message .= 'Content-ID: <' . urlencode(basename($attachment)) . '>' . $eol;
				$message .= 'X-Attachment-Id: ' . urlencode(basename($attachment)) . $eol . $eol;
				$message .= chunk_split(base64_encode($content));
			}
		}

		$message .= '--' . $boundary . '--' . $eol;

		ini_set('sendmail_from', $this->from);

		if ($this->parameter) {
			return mail($to, '=?UTF-8?B?' . base64_encode($this->subject) . '?=', $message, $header, $this->parameter);
		} else {
			return mail($to, '=?UTF-8?B?' . base64_encode($this->subject) . '?=', $message, $header);
		}
	}
}
