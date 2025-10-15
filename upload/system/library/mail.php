<?php
/**
 * @package		OpenCart
 *
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2022, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 *
 * @see		   https://www.opencart.com
 */
namespace Opencart\System\Library;
/**
 * Class Mail
 *
 * Basic Mail Class
 */
class Mail {
	/**
	 * @var array<string, mixed>
	 */
	private object $adaptor;
	private string $text = '';
	private string $html = '';

	/**
	 * Constructor
	 *
	 * @param string               $adaptor
	 * @param array<string, mixed> $option
	 */
	public function __construct(string $adaptor = 'mail', array $option = []) {
		$class = 'Opencart\System\Library\Mail\\' . $adaptor;

		if (!class_exists($class)) {
			throw new \Exception('Error: Could not load mail adaptor ' . $adaptor . '!');
		}

		$this->adaptor = new $class($option);
	}

	/**
	 * Set To
	 *
	 * @param array<string>|string $to
	 *
	 * @return void
	 */
	public function setTo(string|array $to): void {
		if (is_array($to)) {
			$to = implode(',', $to);
		}

		$this->adaptor->setTo($to);
	}

	/**
	 * Set From
	 *
	 * @param string $from
	 *
	 * @return void
	 */
	public function setFrom(string $from): void {
		$this->adaptor->setFrom($from);
	}

	/**
	 * Set Sender
	 *
	 * @param string $sender
	 *
	 * @return void
	 */
	public function setSender(string $sender): void {
		$this->adaptor->setSender($sender);
	}

	/**
	 * Set Reply To
	 *
	 * @param string $reply_to
	 *
	 * @return void
	 */
	public function setReplyTo(string $reply_to): void {
		$this->adaptor->setReplyTo($reply_to);
	}

	/**
	 * Set Subject
	 *
	 * @param string $subject
	 *
	 * @return void
	 */
	public function setSubject(string $subject): void {
		$this->adaptor->setSubject($subject);
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
	 * Add Attachment
	 *
	 * @param string $filename
	 *
	 * @return void
	 */
	public function addAttachment(string $filename): void {
		$this->attachments[] = $filename;
	}

	/**
	 * Send
	 *
	 * @return bool
	 */
	public function send(): bool {
		if (empty($this->text) && empty($this->html)) {
			throw new \Exception('Error: E-Mail message required!');
		}

		$message = '--' . $boundary . PHP_EOL;

		if (empty($this->option['html'])) {
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

		$this->adaptor->setMessage($message);

		return $this->adaptor->send();
	}
}
