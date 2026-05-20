<?php
/**
 * WeChat Pay Model - v3 API Implementation
 * 
 * This class implements WeChat Pay v3 API using RSA-SHA256 signature
 * 
 * @package		OpenCart
 * @author		Meng Wenbin
 * @copyright	Copyright (c) 2010 - 2017, Chengdu Guangda Network Technology Co. Ltd. (https://www.opencart.cn/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.cn
 */

class ModelExtensionPaymentWechatPay extends Model {
	public $errMsg;
	public $errCode;

	const MCH_BASE_URL = 'https://api.mch.weixin.qq.com';

	public function getMethod($address, $total) {
		$this->load->language('extension/payment/wechat_pay');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('payment_wechat_pay_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if ($this->config->get('payment_wechat_pay_total') > 0 && $this->config->get('payment_wechat_pay_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('payment_wechat_pay_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code'       => 'wechat_pay',
				'title'      => $this->language->get('text_title'),
				'terms'      => '',
				'sort_order' => $this->config->get('payment_wechat_pay_sort_order')
			);
		}

		return $method_data;
	}

	public function getErrMsg() {
		return $this->errMsg;
	}

	public function getErrCode() {
		return $this->errCode;
	}

	private function loadPrivateKey() {
		$privateKeyContent = $this->config->get('payment_wechat_pay_private_key');

		$this->log->write('WechatPay loadPrivateKey: content length=' . strlen($privateKeyContent ?: '') . ', first 50 chars=' . substr($privateKeyContent ?: '', 0, 50));

		if (empty($privateKeyContent)) {
			$this->errCode = 'CONFIG_ERROR';
			$this->errMsg = 'Merchant private key not configured';
			return false;
		}

		$privateKey = openssl_pkey_get_private($privateKeyContent);

		if ($privateKey === false) {
			$this->errCode = 'KEY_ERROR';
			$this->errMsg = 'Failed to load merchant private key: ' . openssl_error_string();
			$this->log->write('WechatPay loadPrivateKey failed: ' . openssl_error_string());
			return false;
		}

		$this->log->write('WechatPay loadPrivateKey success');
		return $privateKey;
	}

	private function loadPublicKey() {
		$publicKeyContent = $this->config->get('payment_wechat_pay_public_key');

		if (empty($publicKeyContent)) {
			$this->errCode = 'CONFIG_ERROR';
			$this->errMsg = 'Public key not configured';
			return false;
		}

		$publicKey = openssl_pkey_get_public($publicKeyContent);

		if ($publicKey === false) {
			$this->errCode = 'KEY_ERROR';
			$this->errMsg = 'Failed to load public key';
			return false;
		}

		return $publicKey;
	}

	public function generateNonceStr(int $length = 32): string {
		$chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
		$str = '';
		for ($i = 0; $i < $length; $i++) {
			$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		return $str;
	}

	private function generateSignV3(string $signString): string|false {
		$privateKey = $this->loadPrivateKey();
		if ($privateKey === false) {
			return false;
		}

		$signature = '';
		$result = openssl_sign($signString, $signature, $privateKey, OPENSSL_ALGO_SHA256);

		if (!$result) {
			$this->errCode = 'SIGN_ERROR';
			$this->errMsg = 'Failed to generate signature';
			return false;
		}

		return base64_encode($signature);
	}

	private function verifySignV3(string $signString, string $signature): bool {
		$publicKey = $this->loadPublicKey();
		if ($publicKey === false) {
			return false;
		}

		$result = openssl_verify($signString, base64_decode($signature), $publicKey, OPENSSL_ALGO_SHA256);

		if ($result !== 1) {
			$this->errCode = 'SIGN_ERROR';
			$this->errMsg = 'Signature verification failed';
			return false;
		}

		return true;
	}

	private function buildAuthorization(string $method, string $url, string $body = ''): string|false {
		$mchId = $this->config->get('payment_wechat_pay_mch_id');
		$serialNo = $this->config->get('payment_wechat_pay_cert_serial_no');

		if (empty($mchId) || empty($serialNo)) {
			$this->errCode = 'CONFIG_ERROR';
			$this->errMsg = 'Merchant ID or certificate serial number not configured';
			return false;
		}

		$timestamp = time();
		$nonce = $this->generateNonceStr();

		$signString = $method . "\n" . $url . "\n" . $timestamp . "\n" . $nonce . "\n" . $body . "\n";

		$this->log->write('WechatPay BuildAuth: timestamp=' . $timestamp . ', nonce=' . $nonce . ', serialNo=' . $serialNo);
		$this->log->write('WechatPay SignString: ' . str_replace("\n", '\\n', $signString));

		$signature = $this->generateSignV3($signString);
		if ($signature === false) {
			$this->log->write('WechatPay generateSignV3 failed');
			return false;
		}

		$this->log->write('WechatPay Signature generated successfully, length=' . strlen($signature));

		return sprintf(
			'WECHATPAY2-SHA256-RSA2048 mchid="%s",nonce_str="%s",timestamp="%d",serial_no="%s",signature="%s"',
			$mchId,
			$nonce,
			$timestamp,
			$serialNo,
			$signature
		);
	}

	private function httpRequestV3(string $method, string $url, array $data = [], int $timeout = 30): array|false {
		$urlPath = parse_url($url, PHP_URL_PATH);
		$body = empty($data) ? '' : json_encode($data);

		$this->log->write('WechatPay httpRequestV3: method=' . $method . ', url=' . $url . ', urlPath=' . $urlPath);

		$authorization = $this->buildAuthorization($method, $urlPath, $body);
		if ($authorization === false) {
			$this->log->write('WechatPay buildAuthorization failed: ' . $this->errMsg);
			return false;
		}

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		if (!empty($body)) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Content-Type: application/json',
			'Accept: application/json',
			'User-Agent: OpenCart/3.0 WechatPay/V3',
			'Authorization: ' . $authorization
		]);

		$response = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		$this->log->write('WechatPay HTTP Response: httpCode=' . $httpCode . ', response=' . substr($response, 0, 500));

		if (curl_errno($ch)) {
			$this->errCode = 'HTTP_ERROR';
			$this->errMsg = 'HTTP request failed: ' . curl_error($ch);
			return false;
		}

		if ($httpCode >= 400) {
			$this->errCode = 'HTTP_ERROR';
			$this->errMsg = 'HTTP request failed with status code: ' . $httpCode . ', Response: ' . $response;
			return false;
		}

		$result = json_decode($response, true);
		if (json_last_error() !== JSON_ERROR_NONE) {
			$this->errCode = 'JSON_ERROR';
			$this->errMsg = 'Failed to parse JSON response: ' . $response;
			return false;
		}

		return $result;
	}

	/**
	 * Unified Order (v3 API) - Native payment
	 * 
	 * @param array $order Order data
	 *   - body: Product description
	 *   - out_trade_no: Merchant order number
	 *   - total_fee: Order amount (yuan)
	 *   - notify_url: Callback notification URL
	 * @return array|false Returns WeChat Pay response array on success, false on failure
	 */
	public function unifiedOrder(array $order): array|false {
		$appid = $this->config->get('payment_wechat_pay_app_id');
		$mchId = $this->config->get('payment_wechat_pay_mch_id');

		$this->errMsg = '';
		$this->errCode = '';

		$this->log->write('WechatPay unifiedOrder: appid=' . ($appid ?: 'empty') . ', mchId=' . ($mchId ?: 'empty'));

		if (empty($appid)) {
			$this->errCode = 'CONFIG_ERROR';
			$this->errMsg = 'WeChat APPID not configured';
			$this->log->write('WechatPay Error: ' . $this->errMsg);
			return false;
		}

		if (empty($mchId)) {
			$this->errCode = 'CONFIG_ERROR';
			$this->errMsg = 'Merchant ID not configured';
			$this->log->write('WechatPay Error: ' . $this->errMsg);
			return false;
		}

		$apiV3Key = $this->config->get('payment_wechat_pay_api_v3_key');
		if (empty($apiV3Key)) {
			$this->errCode = 'CONFIG_ERROR';
			$this->errMsg = 'APIv3 Key not configured';
			$this->log->write('WechatPay Error: ' . $this->errMsg);
			return false;
		}

		$privateKey = $this->config->get('payment_wechat_pay_private_key');
		if (empty($privateKey)) {
			$this->errCode = 'CONFIG_ERROR';
			$this->errMsg = 'Private Key not configured';
			$this->log->write('WechatPay Error: ' . $this->errMsg);
			return false;
		}

		$serialNo = $this->config->get('payment_wechat_pay_cert_serial_no');
		if (empty($serialNo)) {
			$this->errCode = 'CONFIG_ERROR';
			$this->errMsg = 'Certificate Serial No not configured';
			$this->log->write('WechatPay Error: ' . $this->errMsg);
			return false;
		}

		$data = [
			'appid' => $appid,
			'mchid' => $mchId,
			'description' => $order['body'],
			'out_trade_no' => $order['out_trade_no'],
			'notify_url' => $order['notify_url'],
			'amount' => [
				'total' => (int)($order['total_fee'] * 100),
				'currency' => 'CNY'
			]
		];

		$this->log->write('WechatPay Request Data: ' . json_encode($data));

		$url = self::MCH_BASE_URL . '/v3/pay/transactions/native';
		$result = $this->httpRequestV3('POST', $url, $data);

		if ($result === false) {
			$this->log->write('WechatPay httpRequestV3 failed: ' . $this->errMsg);
			return false;
		}

		if (!isset($result['code_url'])) {
			$this->errCode = 'API_ERROR';
			$this->errMsg = 'Invalid response: code_url not found. Response: ' . json_encode($result);
			$this->log->write('WechatPay Error: ' . $this->errMsg);
			return false;
		}

		return $result;
	}

	/**
	 * Parse and verify payment callback notification (v3 API)
	 * 
	 * @param string $jsonData WeChat callback raw JSON data
	 * @return array|false Returns callback data array on success, false on failure
	 */
	public function parseCallback(string $jsonData): array|false {
		$data = json_decode($jsonData, true);
		if (json_last_error() !== JSON_ERROR_NONE) {
			$this->errCode = 'JSON_ERROR';
			$this->errMsg = 'Invalid JSON data';
			return false;
		}

		if (!isset($data['resource'])) {
			$this->errCode = 'CALLBACK_ERROR';
			$this->errMsg = 'Invalid callback data format';
			return false;
		}

		$resource = $data['resource'];
		if (!isset($resource['ciphertext']) || !isset($resource['nonce']) || !isset($resource['associated_data'])) {
			$this->errCode = 'CALLBACK_ERROR';
			$this->errMsg = 'Missing encrypted data fields';
			return false;
		}

		$decrypted = $this->decryptCallback(
			$resource['ciphertext'],
			$resource['nonce'],
			$resource['associated_data']
		);

		if ($decrypted === false) {
			return false;
		}

		$callbackData = json_decode($decrypted, true);
		if (json_last_error() !== JSON_ERROR_NONE) {
			$this->errCode = 'JSON_ERROR';
			$this->errMsg = 'Failed to parse decrypted data';
			return false;
		}

		return $callbackData;
	}

	private function decryptCallback(string $ciphertext, string $nonce, string $associatedData): string|false {
		$apiV3Key = $this->config->get('payment_wechat_pay_api_v3_key');

		if (empty($apiV3Key) || strlen($apiV3Key) !== 32) {
			$this->errCode = 'CONFIG_ERROR';
			$this->errMsg = 'APIv3 key not configured or invalid length';
			return false;
		}

		$ciphertext = base64_decode($ciphertext);
		if ($ciphertext === false) {
			$this->errCode = 'DECRYPT_ERROR';
			$this->errMsg = 'Invalid ciphertext';
			return false;
		}

		$authTag = substr($ciphertext, -16);
		$ciphertext = substr($ciphertext, 0, -16);

		$decrypted = openssl_decrypt(
			$ciphertext,
			'aes-256-gcm',
			$apiV3Key,
			OPENSSL_RAW_DATA,
			$nonce,
			$authTag,
			$associatedData
		);

		if ($decrypted === false) {
			$this->errCode = 'DECRYPT_ERROR';
			$this->errMsg = 'Failed to decrypt callback data';
			return false;
		}

		return $decrypted;
	}

	/**
	 * Build callback response (v3 API)
	 * 
	 * @param bool $success Whether successful
	 * @return string JSON response string
	 */
	public function buildCallbackResponse(bool $success): string {
		return json_encode([
			'code' => $success ? 'SUCCESS' : 'FAIL',
			'message' => $success ? 'Success' : 'Error'
		]);
	}

	private function getClientIp(): string {
		if (isset($_SERVER['HTTP_CLIENT_IP'])) {
			return $_SERVER['HTTP_CLIENT_IP'];
		} elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		} elseif (isset($_SERVER['REMOTE_ADDR'])) {
			return $_SERVER['REMOTE_ADDR'];
		}
		return '127.0.0.1';
	}
}
