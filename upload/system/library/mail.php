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
class Mail {
	protected $to;
	protected $from;
	protected $sender;
	protected $reply_to;
	protected $subject;
	protected $text;
	protected $html;
	protected $attachments = array();
	public $parameter;

	/**
	 * Constructor
	 *
	 * @param	string	$file
	 *
 	*/
	public function __construct($adaptor = 'mail') {
		$class = 'Mail\\' . $adaptor;
		
		if (class_exists($class)) {
			$this->adaptor = new $class();
		} else {
			trigger_error('Error: Could not load mail adaptor ' . $adaptor . '!');
			exit();
		}	
	}
	
	/**
     * 
     *
     * @param	mixed	$to
     */
	public function setTo($to) {
		$this->to = $to;
	}
	
	/**
     * 
     *
     * @param	string	$from
     */
	public function setFrom($from) {
		$this->from = $from;
	}
	
	/**
     * 
     *
     * @param	string	$sender
     */
	public function setSender($sender) {
		$this->sender = $sender;
	}
	
	/**
     * 
     *
     * @param	string	$reply_to
	 * 
	 * @return	string
     */
	public function setReplyTo($reply_to) {
		$this->reply_to = $reply_to;
	}
	
	/**
     * 
     *
     * @param	string	$sql
	 * 
	 * @return	string
     */
	public function setSubject($subject) {
		$this->subject = $subject;
	}
	
	/**
     * 
     *
     * @param	string	$sql
	 * 
	 * @return	string
     */
	public function setText($text) {
		$this->text = $text;
	}
	
	/**
     * 
     *
     * @param	string	$sql
	 * 
	 * @return	string
     */
	public function setHtml($html) {
		$this->html = $html;
	}
	
	/**
     * 
     *
     * @param	string	$sql
	 * 
	 * @return	array
     */
	public function addAttachment($filename) {
		$this->attachments[] = $filename;
	}
	
	/**
     * 
     *
     * @param	string	$sql
	 * 
	 * @return	array
     */
	public function send() {
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

		if ((!$this->text) && (!$this->html)) {
			throw new \Exception('Error: E-Mail message required!');
		}
		
		foreach (get_object_vars($this) as $key => $value) {
			$this->adaptor->$key = $value;
		}
		
		$this->adaptor->send();
	}
}