<?php
namespace Opencart\System\Library\JWT;

require_once DIR_STORAGE.'vendor/jwt/src/JWT.php';
require_once DIR_STORAGE.'vendor/jwt/src/Key.php';

/**
 * Class JWT
 *
 * @package Opencart\System\Library\JWT
 */
class JWT {
	/**
	 * @var object
	 */
	private object $config;
	/**
	 * @var object
	 */
	private object $request;
	/**
	 * @var string
	 */
	private string $secret_key;
	/**
	 * @var string
	 */
	private string $host;
	/**
	 * @var int
	 */
	private int $admin_token_lifetime = 1800;
	/**
	 * @var int
	 */
	private int $customer_token_lifetime = 3600;

	/**
	 * Constructor
	 *
	 * @param object $registry
	 */
	public function __construct(\Opencart\System\Engine\Registry $registry) {
		$this->config = $registry->get('config');
		$this->request = $registry->get('request');
		$this->secret_key = base64_encode($this->config->get('config_encryption'));

		$host = parse_url(HTTP_SERVER, PHP_URL_HOST);

		if ($host !== false) {
			$this->host = $host;
		} else {
			if (isset($this->request->server['HTTP_HOST'])) {
				$this->host = $this->request->server['HTTP_HOST'];
			} else {
				$this->host = '';
			}
		}
	}

	/**
	 * createToken
	 *
	 * @param array $data
	 *
	 * @return string
	 */
	public function createToken(array $data): string {
		$token = \Firebase\JWT\JWT::encode($data, $this->secret_key, 'HS512');

		if (isset($data['aud']) && in_array($data['aud'], ['Admin', 'Catalog'])) {
			$aud = (string)$data['aud'];
		} else {
			return '';
		}

		if ($aud == 'Admin') {
			$lifetime = $this->admin_token_lifetime;
		} elseif ($aud == 'Catalog') {
			$lifetime = $this->customer_token_lifetime;
		}

		try {
			$option = [
				'expires' => time() + $lifetime,
				'path' => '/',
				'domain' => $this->host,
				'secure' => true,
				'httponly' => true,
				'SameSite' => 'Strict'
			];

			setcookie(hash('sha256', $this->host.$aud), $token, $option);

			return (string)$token;
		} catch (\Exception $e) {
			return '';
		}
	}

	/**
	 * validateToken
	 *
	 * @param array $data
	 *
	 * @return bool
	 */
	public function validateToken(array $data = []): bool {
		$jwt_cookie_index = hash('sha256', $this->host.APPLICATION);

		if (isset($this->request->cookie[$jwt_cookie_index]) && !empty($this->request->cookie[$jwt_cookie_index])) {
			$token = $this->request->cookie[$jwt_cookie_index];

			try {
				$decoded = \Firebase\JWT\JWT::decode($token, new \Firebase\JWT\Key($this->secret_key, 'HS512'));

				if ($decoded != false) {
					if ($data) {
						$iss = $decoded->iss;
						$aud = $decoded->aud;

						$keys = [
							'email',
							'user_id',
							'username',
							'customer_id',
							'user_group_id',
							'customer_group_id'
						];

						foreach ($keys as $key) {
							if (!isset($data[$key])) {
								$data[$key] = '';
							}
						}

						if ($aud == 'Admin') {
							$credentials = hash('sha256', $data['user_id'].$data['user_group_id'].$data['username'].$data['email']);
						} else if ($aud == 'Catalog') {
							$credentials = hash('sha256', $data['customer_id'].$data['customer_group_id'].$data['email']);
						} else {
							return false;
						}

						if ($credentials == $decoded->data && $iss == $this->host) {
							return true;
						} else {
							return false;
						}
					} else {
						return true;
					}
				} else {
					return false;
				}
			} catch (\Exception $e) {
				return false;
			}
		} else {
			return false;
		}
	}

	/**
	 * getHost
	 *
	 * @return string
	 */
	public function getHost(): string {
		return (string)$this->host;
	}

	/**
	 * getCustomerTokenLifetime
	 *
	 * @return int
	 */
	public function getAdminTokenLifetime(): int {
		return (int)$this->admin_token_lifetime;
	}

	/**
	 * getCustomerTokenLifetime
	 *
	 * @return int
	 */
	public function getCustomerTokenLifetime(): int {
		return (int)$this->customer_token_lifetime;
	}
}
