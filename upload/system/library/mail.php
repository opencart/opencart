<?php
/**
 * @package		OpenCart
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2017, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.com
*/

/**
* Mail class
*/
namespace Opencart\System\Library;
class Mail {
	private string $adaptor;
	protected string $to = '';
	protected string $from = '';
	protected string $sender = '';
	protected string $reply_to = '';
	protected string $subject = '';
	protected string $text = '';
	protected string $html = '';
	protected array $attachments = [];

	/**
	 * Constructor
	 *
	 * @param	string	$adaptor
	 *
 	*/
	public function __construct(string $adaptor = 'mail') {
		$class = 'Opencart\System\Library\Mail\\' . $adaptor;

		if (class_exists($class)) {
			$this->adaptor = $class;
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
	public function setTo(string $to): void {
		$this->to = $to;
	}

	/**
     * setFrom
     *
     * @param	string	$from
	 *
	 * @return  void
     */
	public function setFrom(string $from): void {
		$this->from = $from;
	}

	/**
     * setSender
     *
     * @param	string	$sender
	 *
	 * @return  void
     */
	public function setSender(string $sender): void {
		$this->sender = $sender;
	}

	/**
     * setReplyTo
     *
     * @param	string	$reply_to
	 *
	 * @return  void
     */
	public function setReplyTo(string $reply_to): void {
		$this->reply_to = $reply_to;
	}

	/**
     * setSubject
     *
     * @param	string	$subject
	 *
	 * @return  void
     */
	public function setSubject(string $subject): void {
		$this->subject = $subject;
	}

	/**
     * setText
     *
     * @param	string	$text
	 *
	 * @return  void
     */
	public function setText(string $text): void {
		$this->text = $text;
	}

	/**
     * setHtml
     *
     * @param	string	$html
	 *
	 * @return  void
     */
	public function setHtml(string $html): void {
		$this->html = $html;
	}

	/**
     * addAttachment
     *
     * @param	string	$filename
	 *
	 * @return  void
     */
	public function addAttachment(string $filename): void {
		$this->attachments[] = $filename;
	}

	/**
     * Send
     *
	 * @return  bool
     */
	public function send(): bool {
		if (!$this->to) {
			throw new \Exception('Error: E-Mail to required!');
		}

		if (!$this->from) {
			throw new \Exception('Error: E-Mail from required!');
		}

		if (!$this->sender) {
			throw new \Exception('Error: E-Mail sender required!');
		}

		if (!$this->subject) {
			throw new \Exception('Error: E-Mail subject required!');
		}

		if (!$this->text && !$this->html) {
			throw new \Exception('Error: E-Mail message required!');
		}

		$mail_data = [];

		foreach (get_object_vars($this) as $key => $value) $mail_data[$key] = $value;

		$mail = new $this->adaptor($mail_data);

		return $mail->send();
	}
}
