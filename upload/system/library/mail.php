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
		$this->adaptor->setText($text);
	}

	/**
	 * Set Html
	 *
	 * @param string $html
	 *
	 * @return void
	 */
	public function setHtml(string $html): void {
		$this->adaptor->setHtml($html);
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
		return $this->adaptor->send();
	}
}
