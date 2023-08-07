<?php
/**
 * @package		OpenCart
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2017, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.com
*/
namespace Opencart\System\Library;
/**
 * Class Mail
 */
class Mail {
	private object $adaptor;
	private array $option = [];

	/**
	 * Constructor
	 *
	 * @param	string	$adaptor
	 * @param   array	$option
	 *
 	*/
	public function __construct(string $adaptor = 'mail', array $option = []) {
		$class = 'Opencart\System\Library\Mail\\' . $adaptor;

		if (class_exists($class)) {
			$this->option = &$option;

			$this->adaptor = new $class($option);
		} else {
			throw new \Exception('Error: Could not load mail adaptor ' . $adaptor . '!');
		}
	}

	/**
     * setTo
     *
     * @param	string	$to
	 *
	 * @return  void
     */
	public function setTo(string|array $to): void {
		$this->option['to'] = $to;
	}

	/**
     * setFrom
     *
     * @param	string	$from
	 *
	 * @return  void
     */
	public function setFrom(string $from): void {
		$this->option['from'] = $from;
	}

	/**
     * setSender
     *
     * @param	string	$sender
	 *
	 * @return  void
     */
	public function setSender(string $sender): void {
		$this->option['sender'] = $sender;
	}

	/**
     * setReplyTo
     *
     * @param	string	$reply_to
	 *
	 * @return  void
     */
	public function setReplyTo(string $reply_to): void {
		$this->option['reply_to'] = $reply_to;
	}

	/**
     * setSubject
     *
     * @param	string	$subject
	 *
	 * @return  void
     */
	public function setSubject(string $subject): void {
		$this->option['subject'] = $subject;
	}

	/**
     * setText
     *
     * @param	string	$text
	 *
	 * @return  void
     */
	public function setText(string $text): void {
		$this->option['text'] = $text;
	}

	/**
     * setHtml
     *
     * @param	string	$html
	 *
	 * @return  void
     */
	public function setHtml(string $html): void {
		$this->option['html'] = $html;
	}

	/**
     * addAttachment
     *
     * @param	string	$filename
	 *
	 * @return  void
     */
	public function addAttachment(string $filename): void {
		$this->option['attachments'][] = $filename;
	}

	/**
     * Send
     *
	 * @return  bool
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

		return $this->adaptor->send();
	}
}
