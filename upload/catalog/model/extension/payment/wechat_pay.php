<?php
/**
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

	public function generateNonceStr(int $length = 32): string {
		$chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
		$str = '';
		for ($i = 0; $i < $length; $i++) {
			$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		return $str;
	}

	public function generateSign(array $params): string|false {
		if (empty($params)) {
			$this->errCode = 'PARAM_ERROR';
			$this->errMsg = 'Sign parameters cannot be empty';
			return false;
		}

		$params = array_filter($params, function($v) {
			return $v !== null && $v !== '';
		});

		unset($params['sign']);

		ksort($params);

		$string = '';
		foreach ($params as $key => $value) {
			$string .= $key . '=' . $value . '&';
		}

		$partnerkey = $this->config->get('payment_wechat_pay_api_secret');
		$string .= 'key=' . $partnerkey;

		$sign = strtoupper(md5($string));

		return $sign;
	}

	public function verifySign(array $params): bool {
		if (empty($params['sign'])) {
			$this->errCode = 'SIGN_ERROR';
			$this->errMsg = 'Sign does not exist';
			return false;
		}

		$receivedSign = $params['sign'];
		$calculatedSign = $this->generateSign($params);

		if ($calculatedSign === false) {
			return false;
		}

		if ($receivedSign !== $calculatedSign) {
			$this->errCode = 'SIGN_ERROR';
			$this->errMsg = 'Sign verification failed';
			return false;
		}

		return true;
	}

	public function arrayToXml(array $params): string|false {
		if (empty($params)) {
			$this->errCode = 'PARAM_ERROR';
			$this->errMsg = 'XML parameters cannot be empty';
			return false;
		}

		$xml = '<xml>';
		foreach ($params as $key => $value) {
			if (is_numeric($value)) {
				$xml .= '<' . $key . '>' . $value . '</' . $key . '>';
			} else {
				$xml .= '<' . $key . '><![CDATA[' . $value . ']]></' . $key . '>';
			}
		}
		$xml .= '</xml>';

		return $xml;
	}

	public function xmlToArray(string $xml): array|false {
		if (empty($xml)) {
			$this->errCode = 'PARAM_ERROR';
			$this->errMsg = 'XML data cannot be empty';
			return false;
		}

		$result = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);

		if ($result === false) {
			$this->errCode = 'XML_ERROR';
			$this->errMsg = 'XML parsing failed';
			return false;
		}

		return json_decode(json_encode($result), true);
	}

	public function httpRequest(string $url, string $data, int $timeout = 30): string|false {
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

		$response = curl_exec($ch);

		if (curl_errno($ch)) {
			$this->errCode = 'HTTP_ERROR';
			$this->errMsg = 'HTTP request failed: ' . curl_error($ch);
			return false;
		}

		return $response;
	}

	/**
	 * Unified Order - Replaces SDK getPrepayId method
	 * 
	 * Used for Native payment unified order, returns QR code URL
	 * 
	 * @param array $order Order data
	 *   - body: Product description
	 *   - out_trade_no: Merchant order number
	 *   - total_fee: Order amount (yuan)
	 *   - notify_url: Callback notification URL
	 *   - trade_type: Trade type (default NATIVE)
	 * @return array|false Returns WeChat Pay response array on success (contains code_url), false on failure
	 */
	public function unifiedOrder(array $order): array|false {
		$appid = $this->config->get('payment_wechat_pay_app_id');
		$mch_id = $this->config->get('payment_wechat_pay_mch_id');

		if (empty($appid)) {
			$this->errCode = 'CONFIG_ERROR';
			$this->errMsg = 'WeChat APPID not configured';
			return false;
		}

		if (empty($mch_id)) {
			$this->errCode = 'CONFIG_ERROR';
			$this->errMsg = 'Merchant ID not configured';
			return false;
		}

		$partnerkey = $this->config->get('payment_wechat_pay_api_secret');
		if (empty($partnerkey)) {
			$this->errCode = 'CONFIG_ERROR';
			$this->errMsg = 'Merchant key not configured';
			return false;
		}

		$params = [
			'appid' => $appid,
			'mch_id' => $mch_id,
			'nonce_str' => $this->generateNonceStr(),
			'body' => $order['body'],
			'out_trade_no' => $order['out_trade_no'],
			'total_fee' => (int)($order['total_fee'] * 100),
			'spbill_create_ip' => $this->getClientIp(),
			'notify_url' => $order['notify_url'],
			'trade_type' => $order['trade_type'] ?? 'NATIVE',
		];

		if (isset($order['product_id'])) {
			$params['product_id'] = $order['product_id'];
		}

		$sign = $this->generateSign($params);
		if ($sign === false) {
			return false;
		}
		$params['sign'] = $sign;

		$xml = $this->arrayToXml($params);
		if ($xml === false) {
			return false;
		}

		$url = self::MCH_BASE_URL . '/pay/unifiedorder';
		$response = $this->httpRequest($url, $xml);

		if ($response === false) {
			return false;
		}

		$result = $this->xmlToArray($response);
		if ($result === false) {
			return false;
		}

		if ($result['return_code'] !== 'SUCCESS') {
			$this->errCode = 'API_ERROR';
			$this->errMsg = $result['return_msg'] ?? 'Unified order failed';
			return false;
		}

		if ($result['result_code'] !== 'SUCCESS') {
			$this->errCode = 'API_ERROR';
			$this->errMsg = $result['err_code_des'] ?? 'Unified order failed';
			return false;
		}

		return $result;
	}

	/**
	 * Parse and verify payment callback notification - Replaces SDK getNotify method
	 * 
	 * Reads WeChat Pay callback XML data, parses and verifies signature
	 * 
	 * @param string $xmlData WeChat callback raw XML data
	 * @return array|false Returns callback data array on success, false on failure
	 */
	public function parseCallback(string $xmlData): array|false {
		$result = $this->xmlToArray($xmlData);
		if ($result === false) {
			return false;
		}

		if (!isset($result['return_code']) || $result['return_code'] !== 'SUCCESS') {
			$this->errCode = 'CALLBACK_ERROR';
			$this->errMsg = 'Callback data format error';
			return false;
		}

		if (!$this->verifySign($result)) {
			return false;
		}

		return $result;
	}

	/**
	 * Build callback response XML
	 * 
	 * @param bool $success Whether successful
	 * @return string XML response string
	 */
	public function buildCallbackResponse(bool $success): string {
		$params = [
			'return_code' => $success ? 'SUCCESS' : 'FAIL',
			'return_msg' => $success ? 'OK' : 'ERROR',
		];

		$xml = $this->arrayToXml($params);
		return $xml !== false ? $xml : '<xml><return_code>FAIL</return_code><return_msg>ERROR</return_msg></xml>';
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
