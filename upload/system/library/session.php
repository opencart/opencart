<?php
/**
 * @package		OpenCart
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2022, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.com
*/
namespace Opencart\System\Library;
/**
 * Class Session
 */
class Session {
	/**
	 * @var object|mixed
	 */
	protected object $adaptor;
	/**
	 * @var string
	 */
	protected string $session_id;
	/**
	 * @var bool
	 */
	private $cookie_status = true;
	/**
	 * @var array
	 */
	public array $data = [];

	/**
	 * Constructor
	 *
	 * @param	string	$adaptor
	 * @param	object	$registry
 	*/
	public function __construct(string $adaptor, \Opencart\System\Engine\Registry $registry) {
		$cookie_status_path = DIR_SESSION . 'sess_block_' . md5($_SERVER['REMOTE_ADDR']);

		if (!isset($_COOKIE['cookie_status'])) {
			setcookie('cookie_status', 1, 0, '/');

			if (!is_file($cookie_status_path)) {
				file_put_contents($cookie_status_path, false);
			} else {
				$this->cookie_status = false;

				return false;
			}
		} else {
			if (is_file($cookie_status_path)) {
				@unlink($cookie_status_path);
			}
		}

		$class = 'Opencart\System\Library\Session\\' . $adaptor;

		if (class_exists($class)) {
			if ($registry) {
				$this->adaptor = new $class($registry);
			} else {
				$this->adaptor = new $class();
			}

			register_shutdown_function([&$this, 'close']);
			register_shutdown_function([&$this, 'gc']);
		} else {
			throw new \Exception('Error: Could not load session adaptor ' . $adaptor . ' session!');
		}
	}

	/**
	 * Get Session ID
	 *
	 * @return	string
 	*/	
	public function getId(): string {
		if (!$this->cookie_status) {
			return false;
		}

		return $this->session_id;
	}

	/**
	 * Start
	 *
	 * Starts a session.
	 *
	 * @param	string	$session_id
	 *
	 * @return	string	Returns the current session ID.
 	*/	
	public function start(string $session_id = ''): string {
		if (!$this->cookie_status) {
			return false;
		}

		if (!$session_id) {
			if (function_exists('random_bytes')) {
				$session_id = substr(bin2hex(random_bytes(26)), 0, 26);
			} else {
				$session_id = substr(bin2hex(openssl_random_pseudo_bytes(26)), 0, 26);
			}
		}

		if (preg_match('/^[a-zA-Z0-9,\-]{22,52}$/', $session_id)) {
			$this->session_id = $session_id;
		} else {
			throw new \Exception('Error: Invalid session ID!');
		}

		$this->data = $this->adaptor->read($session_id);

		return $session_id;
	}

	/**
	 * Close
	 *
	 * Writes the session data to storage
	 *
	 * @return	void
 	*/
	public function close(): void {
		if ($this->cookie_status) {
			$this->adaptor->write($this->session_id, $this->data);
		}
	}

	/**
	 * Destroy
	 *
	 * Deletes the current session from storage
	 *
	 * @return	void
 	*/
	public function destroy(): void {
		$this->data = [];

		if ($this->cookie_status) {
			$this->adaptor->destroy($this->session_id);
		}
	}

	/**
	 * GC
	 *
	 * Garbage Collection
	 *
	 * @return	void
	 */
	public function gc(): void {
		if (!$this->cookie_status) {
			$this->adaptor->gc($this->session_id);
		}
	}
}
