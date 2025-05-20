<?php
namespace Opencart\System\Library\Cart;
/**
 * Class Api
 *
 * @package Opencart\System\Library\Cart
 */
class Api {
	/**
	 * @var string
	 */
	private string $domain;
	private string $path = '/';
	private string $username;
	private string $key;
	private int $store_id;
	private string $language;

	/**
	 * Constructor
	 *
	 * @param string $domain
	 * @param string $path
	 * @param string $username
	 * @param string $key
	 * @param int    $store_id
	 * @param string $language
	 */
	public function __construct(string $domain, string $path, string $username, string $key, int $store_id, string $language = 'en-gb') {
		$this->domain = $domain;
		$this->path = $path;
		$this->username = $username;
		$this->key = $key;
		$this->store_id = $store_id;
		$this->language = $language;
	}

	public function send(string $route, $data = []) {
		$time = time();

		// Build hash string
		$string  = $route . "\n";
		$string .= $this->username . "\n";
		$string .= $this->domain . "\n";
		$string .= $this->path . "\n";
		$string .= $this->store_id . "\n";
		$string .= $this->language . "\n";
		$string .= md5(http_build_query($data)) . "\n";
		$string .= $time . "\n";

		$signature = base64_encode(hash_hmac('sha1', $string, $this->key, true));

		// Make remote call
		$url  = 'http://' . $this->domain . $this->path . 'index.php?route=' . $route;
		$url .= '&username=' . urlencode($this->username);
		$url .= '&store_id=' . $this->store_id;
		$url .= '&language=' . $this->language;
		$url .= '&time=' . $time;
		$url .= '&signature=' . rawurlencode($signature);

		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));

		$response = curl_exec($curl);

		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		curl_close($curl);

		if ($status == 200) {
			$response_info = json_decode($response, true);
		} else {
			$response_info = [];
		}

		echo 'URL' . "\n";
		echo $url . "\n";

		echo 'STRING' . "\n";
		echo $string . "\n";

		echo 'RESPONSE' . "\n";
		echo $response;

		return $response_info;
	}
}
