<?php
namespace Opencart\System\Library\Cart;
/**
 * Class Curl
 *
 * @package Opencart\System\Library\Cart
 */
class Curl {
	/**
	 * @var string
	 */
	private string $url = '';
	/**
	 * @var array<string, mixed>
	 */
	private array $option = [];

	/**
	 * Constructor
	 *
	 * @param string $url
	 */
	public function __construct(string $url) {
		$this->url = $url;
	}

	/**
	 * Set Option
	 *
	 * @param string $key
	 * @param array  $data<string, mixed> array of data
	 *
	 * @return void
	 */
	public function setOption(string $key, array $data = []): void {
		$this->option[$key] = $data;
	}

	public function send(string $route, $data = []) {
		// Make remote call
		$url  = 'http://' . $this->domain . $this->path . 'index.php?route=' . $route;

		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

		$response = curl_exec($curl);

		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		curl_close($curl);

		if ($status == 200) {
			$response_info = json_decode($response, true);
		} else {
			$response_info = [];
		}

		return $response_info;
	}
}
