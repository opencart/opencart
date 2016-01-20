<?php
class Password {

	/**
	 * @var bool
	 */
	protected $useLegacy = false;

	/**
	 * @var bool
	 */
	protected $isMd5 = false;

	/**
	 * @var null|string
	 */
	public $hash = null;

	/**
	 * @var null|string
	 */
	public $salt = null;

	/**
	 * Password constructor.
	 */
	public function __construct() {
		if (!function_exists('password_hash')) {
			$this->useLegacy = true;
		}
	}

	/**
	 * @param string $password
	 */
	public function passwordHash($password) {
		$hash = ($this->useLegacy) ? $this->getLegacyHash($password) : password_hash($password, PASSWORD_DEFAULT);

		if (is_array($hash)) {
			$this->hash = $hash['hash'];
			$this->salt = $hash['salt'];
		} else {
			$this->hash = $hash;
		}
	}

	/**
	 * @param string $password
	 * @param string $hash
	 * @param null $salt
	 * @return bool
	 * @throws Exception
	 */
	public function verify($password, $hash, $salt = null) {
		if ($this->isMd5Hash($hash) || $this->isSha1Hash($hash)) {
			if ($this->isMd5) {
				$hashPassword = md5($password);
			}  else {
				if (null === $salt) {
					throw new Exception('Salt cannot be null in "' . get_class($this) . '"');
				}

				$sha1Hash = $this->getLegacyHash($password, $salt);
				$hashPassword = $sha1Hash['hash'];
			}

			$result = ($hash === $hashPassword) ? true : false;

		} else {
			$result = password_verify($password, $hash);
		}

		return $result;
	}

	/**
	 * @param string $hash
	 * @return int|string
	 */
	public function passwordNeedsRehash($hash) {
		$result = ($this->useLegacy) ? $this->isMd5Hash($hash) : password_needs_rehash($hash, PASSWORD_DEFAULT);
		return $result;
	}

	/**
	 * @param string $password
	 * @param null $salt
	 * @return array
	 */
	public function getLegacyHash($password, $salt = null) {
		$salt = (null === $salt) ? $this->token(9) : $salt;
		return array(
			'hash' => sha1($salt . sha1($salt . sha1($password))),
			'salt' => $salt,
		);
	}

	/**
	 * @param $hash
	 * @return int
	 */
	private function isSha1Hash($hash) {
		return preg_match('/^[a-f0-9]{40}$/', $hash);
	}

	/**
	 * @param $hash
	 * @return int
	 */
	private function isMd5Hash($hash) {
		$md5 = preg_match('/^[a-f0-9]{32}$/', $hash);
		$this->isMd5 = (1 === $md5) ? true : false;
		return $md5;
	}

	/**
	 * @param int $length
	 * @return string
	 */
	private function token($length = 32) {
		// Create token to login with
		$string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

		$token = '';

		for ($i = 0; $i < $length; $i++) {
			$token .= $string[mt_rand(0, strlen($string) - 1)];
		}

		return $token;
	}
}
