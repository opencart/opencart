<?php
namespace Opencart\System\Library;
/**
 * Class Curl
 *
 * @package Opencart\System\Library
 */
class Curl {
	/**
	 * @var array<int, mixed>
	 */
	private array $option = [
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_HEADER         => false,
		CURLOPT_CONNECTTIMEOUT => 30,
		CURLOPT_TIMEOUT        => 30,
		CURLOPT_POST           => true,
		CURLOPT_SSL_VERIFYPEER => true,
		CURLOPT_SSL_VERIFYHOST => 2,
		CURLOPT_PROTOCOLS      => CURLPROTO_HTTP | CURLPROTO_HTTPS,
		CURLOPT_FOLLOWLOCATION => false,
	];

	/**
	 * Set Option
	 *
	 * @see https://www.php.net/manual/en/curl.constants.php
	 *
	 * @param int   $key
	 * @param mixed $value
	 *
	 * @return void
	 */
	public function setOption(int $key, mixed $value): void {
		$this->option[$key] = $value;
	}

	/**
	 * Send
	 *
	 * @param string               $url
	 * @param array<string, mixed> $data
	 *
	 * @return array<string, mixed>
	 */
	public function send(string $url, array $data = []): array {
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);

		foreach ($this->option as $key => $value) {
			curl_setopt($curl, $key, $value);
		}

		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

		$response = curl_exec($curl);

		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		if ($status == 200) {
			$response_info = json_decode((string)$response, true);
		} else {
			$response_info = [];
		}

		return is_array($response_info) ? $response_info : [];
	}
}
