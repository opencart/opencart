<?php
/**
 * @package		OpenCart
 *
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2022, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 *
 * @see		https://www.opencart.com
 */
namespace Opencart\System\Library;
/**
 * Class Mail
 */
class Mail {
	private string $class;
	/**
	 * @var array<string, mixed>
	 */
	private array $option = [];

	/**
	 * Constructor
	 *
	 * @param string               $adaptor
	 * @param array<string, mixed> $option
	 */
	public function __construct(string $adaptor = 'mail', array $option = []) {
		$class = 'Opencart\System\Library\Mail\\' . $adaptor;

		if (class_exists($class)) {
			$this->class = $class;
			$this->option = $option;
		} else {
			throw new \Exception('Error: Could not load mail adaptor ' . $adaptor . '!');
		}
	}

	/**
	 * Set To
	 *
	 * @param array<string>|string $to
	 *
	 * @return void
	 */
	public function setTo($to): void {
		$this->option['to'] = $to;
	}

	/**
	 * Set From
	 *
	 * @param string $from
	 *
	 * @return void
	 */
	public function setFrom(string $from): void {
		$this->option['from'] = $from;
	}

	/**
	 * Set Sender
	 *
	 * @param string $sender
	 *
	 * @return void
	 */
	public function setSender(string $sender): void {
		$this->option['sender'] = $sender;
	}

	/**
	 * Set Reply To
	 *
	 * @param string $reply_to
	 *
	 * @return void
	 */
	public function setReplyTo(string $reply_to): void {
		$this->option['reply_to'] = $reply_to;
	}

	/**
	 * Set Subject
	 *
	 * @param string $subject
	 *
	 * @return void
	 */
	public function setSubject(string $subject): void {
		$this->option['subject'] = $subject;
	}

	/**
	 * Set Text
	 *
	 * @param string $text
	 *
	 * @return void
	 */
	public function setText(string $text): void {
		$this->option['text'] = $text;
	}

	/**
	 * Set Html
	 *
	 * @param string $html
	 *
	 * @return void
	 */
	public function setHtml(string $html): void {
		$this->option['html'] = $html;
	}

	/**
	 * Add Attachment
	 *
	 * @param string $filename
	 *
	 * @return void
	 */
	public function addAttachment(string $filename): void {
		$this->option['attachments'][] = $filename;
	}

	/**
	 * Send
	 *
	 * @return bool
	 */
	public function send(): bool {
		if (empty($this->option['to'])) {
			throw new \Exception('Error: E-Mail to required!');
		}

		if (empty($this->option['from'])) {
			throw new \Exception('Error: E-Mail from required!');
		}

		if (empty($this->option['sender'])) {
			throw new \Exception('Error: E-Mail sender required!');
		}

		if (empty($this->option['subject'])) {
			throw new \Exception('Error: E-Mail subject required!');
		}

		if (empty($this->option['text']) && empty($this->option['html'])) {
			throw new \Exception('Error: E-Mail message required!');
		}

		$mail = new $this->class($this->option);

		return $mail->send();
	}
}
