<?php
/**
 * @package		OpenCart
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2017, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.com
*/

/**
* Session class
*/
class Session {
	protected $adaptor;
	protected $session_id;
	public $data = array();

	/**
	 * Constructor
	 *
	 * @param	string	$adaptor
	 * @param	object	$registry
	*/
	public function __construct($adaptor, $registry = '') {
		$class = 'Session\\' . $adaptor;

		if (class_exists($class)) {
			if ($registry) {
				$this->adaptor = new $class($registry);
			} else {
				$this->adaptor = new $class();
			}

			register_shutdown_function(array($this, 'close'));
		} else {
			trigger_error('Error: Could not load cache adaptor ' . $adaptor . ' session!');
			exit();
		}
	}

	/**
	 *
	 *
	 * @return	string
	*/
	public function getId() {
		return $this->session_id;
	}

	/**
	 * Generates a session ID
	 *
	 * @param	string	$length
	 *
	 * @return	string
	*/
	private function genId($length = 32) {
		$session_id = '';

		if (function_exists('random_bytes')) {
			$session_id = substr(bin2hex(random_bytes($length)), 0, $length);
		} else if (function_exists('openssl_random_pseudo_bytes')) {
			$session_id = substr(bin2hex(openssl_random_pseudo_bytes($length)), 0, $length);
		} else {
			exit('Error: OpenSSL extension is not enabled in PHP!');
		}

		return $session_id;
	}

	/**
	 * Starting and reading a session
	 *
	 * @param	string	$session_id
	 *
	 * @return	string
	*/
	public function start($session_id = '') {
		$length = 32;

		if (!$session_id) {
			$session_id = $this->genId($length);

			if (strlen($session_id) == $length) {
				$exists = $this->adaptor->exists($session_id);

				if ($exists) {
					do {
						$session_id = $this->genId($length);

						$exists = $this->adaptor->exists($session_id);
					} while ($exists == false);
				}
			}
		}

		if (preg_match('/^[a-zA-Z0-9,\-]{'. $length .'}$/', $session_id)) {
			$this->session_id = $session_id;
		} else {
			exit('Error: Invalid session ID!');
		}

		$this->data = $this->adaptor->read($session_id);

		return $session_id;
	}

	/**
	 * Update session data
	 */
	public function close() {
		$this->adaptor->write($this->session_id, $this->data);
	}

	/**
	 * Destroy if not defined
	 */
	public function __destroy() {
		$this->adaptor->destroy($this->session_id);
	}
}
