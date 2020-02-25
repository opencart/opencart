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
	private $expire = 3600;
	private $length = 32;
	private $called = false;
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

				$this->config = $registry->get('config');
				$this->request = $registry->get('request');
			} else {
				$this->adaptor = new $class();
			}

			if (ini_get('session.gc_maxlifetime')) {
				$this->expire = ini_get('session.gc_maxlifetime');
			}

			register_shutdown_function(array($this, 'close'));
		} else {
			trigger_error('Error: Could not load cache adaptor ' . $adaptor . ' session!');
			exit();
		}
	}

	/**
	 * Options cookie
	 *
	 * @return	array
	*/
	private function setup_cookie() {
		if ($this->config->get('session_name')) {
			$name = $this->config->get('session_name');
		} else {
			$name = 'OCSESSID';
		}

		if (ini_get('session.cookie_lifetime')) {
			$lifetime = ini_get('session.cookie_lifetime');
		} else {
			$lifetime = $this->expire;
		}

		if (ini_get('session.cookie_path')) {
			$path = ini_get('session.cookie_path');
		} else {
			$path = '/';
		}

		if (ini_get('session.cookie_domain')) {
			$domain = ini_get('session.cookie_domain');
		} else {
			$domain = null;
		}

		if (isset($this->request->server['HTTPS']) && $this->request->server['HTTPS'] == true) {
			$secure = true;
		} else {
			$secure = false;
		}

		if (PHP_VERSION_ID >= 70300) {
			if (ini_get('session.cookie_samesite')) {
				$samesite = ini_get('session.cookie_samesite');
			} else {
				$samesite = 'Lax';
			}
		} else {
			$samesite = 'None';
		}

		return array(
			'name' => $name,
			'expires' => time() + $lifetime,
			'path' => $path,
			'domain' => $domain,
			'secure' => $secure,
			'httponly' => true,
			'samesite' => $samesite
		);
	}

	/**
	 * Get cookie
	 *
	 * @return	boolean
	*/
	public function get_cookie() {
		$cookie = $this->setup_cookie();

		$name = $cookie['name'];

		return isset($this->request->cookie[$name]) ? $this->request->cookie[$name] : '';
	}

	/**
	 * Set cookie
	 *
	 * @return	boolean
	*/
	public function set_cookie() {
		$session_id = $this->session_id;

		$cookie = $this->setup_cookie();

		if (!$session_id) {
			$session_id = '';

			$cookie['expires'] = 1;
		}

		if (PHP_VERSION_ID >= 70300) {
			$options = array(
				'expires' => $cookie['expires'],
				'path' => $cookie['path'],
				'domain' => $cookie['domain'],
				'secure' => $cookie['secure'],
				'httponly' => $cookie['httponly'],
				'samesite' => $cookie['samesite']
			);

			if (setcookie($cookie['name'], $session_id, $options)) {
				return true;
			}
		} else {
			if (setcookie($cookie['name'], $session_id, $cookie['expires'], $cookie['path'], $cookie['domain'], $cookie['secure'], $cookie['httponly'])) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Get session_id
	 *
	 * @return	string
	*/
	public function getId() {
		return $this->session_id;
	}

	/**
	 * Starting and reading a session
	 *
	 * @param	string	$session_id
	 *
	 * @return	boolean
	*/
	public function start($session_id = '') {
		if (!$session_id) {
			$session_id = $this->generate_session_id();
		}

		$validate = $this->validate_session_id($session_id);

		if ($validate) {
			$this->session_id = $session_id;
		} else {
			exit('Error: Could not start session!');
		}

		$this->data = $this->adaptor->read($this->session_id);

		$this->gc_session();

		return true;
	}

	/**
	 * Close session
	 */
	public function close() {
		if ($this->session_id) {
			$this->adaptor->write($this->session_id, $this->data, $this->expire);
		}

		if ($this->called == true) {
			$this->called = false;

			$this->adaptor->gc($this->expire);
		}
	}

	/**
	 * Destroy session
	 */
	public function destroy() {
		if ($this->session_id) {
			$this->adaptor->destroy($this->session_id);
		}
	}

	/**
	 * Forget session
	 */
	public function forget() {
		$this->session_id = '';
		$this->data = array();

		$this->set_cookie();
	}

	/**
	 * Regenerate session_id
	*/
	public function regenerate_session_id() {
		if ($this->session_id) {
			$session_id = $this->generate_session_id();

			$validate = $this->validate_session_id($session_id);

			if ($validate) {
				$this->destroy();

				$this->session_id = $session_id;

				$this->set_cookie();
			}
		}
	}

	/**
	 * Generate session_id
	 *
	 * @return	string
	*/
	private function generate_session_id() {
		$session_id = '';

		if (function_exists('random_bytes')) {
			$generator = 'random';
		} else if (function_exists('openssl_random_pseudo_bytes')) {
			$generator = 'openssl';
		} else {
			exit('Error: OpenSSL extension is not enabled in PHP!');
		}

		do {
			if ($generator == 'random') {
				$session_id = substr(bin2hex(random_bytes($this->length)), 0, $this->length);
			} else if ($generator == 'openssl') {
				$session_id = substr(bin2hex(openssl_random_pseudo_bytes($this->length)), 0, $this->length);
			}

			$exists = $this->exists_session_id($session_id);
		} while ($exists == true);

		return $session_id;
	}

	/**
	 * Validate session_id
	 *
	 * @return	boolean
	 */
	private function validate_session_id($session_id) {
		if (preg_match('/^[a-zA-Z0-9,\-]{'. $this->length .'}$/', $session_id)) {
			return true;
		} else {
			exit('Error: Invalid session ID!');
		}
	}

	/**
	 * Check if exists session_id
	 *
	 * @return	boolean
	 */
	private function exists_session_id($session_id) {
		return $this->adaptor->exists($session_id);
	}

	/**
	 * Garbage collection
	 */
	private function gc_session() {
		if (ini_get('session.gc_probability')) {
			$gc_probability = ini_get('session.gc_probability');
		} else {
			$gc_probability = 1;
		}

		if (ini_get('session.gc_divisor')) {
			$gc_divisor = ini_get('session.gc_divisor');
		} else {
			$gc_divisor = 100;
		}

		if ((mt_rand() % $gc_divisor) < $gc_probability) {
			$this->called = true;
		}
	}
}
