<?php
class ModelExtensionPaymentAlipay extends Model {
	private $apiMethodName="alipay.trade.page.pay";
	private $postCharset = "UTF-8";
	private $alipaySdkVersion = "alipay-sdk-php-20161101";
	private $apiVersion="1.0";
	private $logFileName = "alipay.log";
	private $gateway_url = "https://openapi.alipay.com/gateway.do";
	private $alipay_public_key;
	private $private_key;
	private $appid;
	private $notifyUrl;
	private $returnUrl;
	private $format = "json";
	private $signtype = "RSA2";

	private $apiParas = array();

	public function getMethod($address, $total) {
		$this->load->language('extension/payment/alipay');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('payment_alipay_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if ($this->config->get('payment_alipay_total') > 0 && $this->config->get('payment_alipay_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('payment_alipay_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code'       => 'alipay',
				'title'      => $this->language->get('text_title'),
				'terms'      => '',
				'sort_order' => $this->config->get('payment_alipay_sort_order')
			);
		}

		return $method_data;
	}

	private function setParams($alipay_config){
		$this->gateway_url = $alipay_config['gateway_url'];
		$this->appid = $alipay_config['app_id'];
		$this->private_key = $alipay_config['merchant_private_key'];
		$this->alipay_public_key = $alipay_config['alipay_public_key'];
		$this->postCharset = $alipay_config['charset'];
		$this->signtype = $alipay_config['sign_type'];
		$this->notifyUrl = $alipay_config['notify_url'];
		$this->returnUrl = $alipay_config['return_url'];

		if (empty($this->appid)||trim($this->appid)=="") {
			throw new Exception("appid should not be NULL!");
		}
		if (empty($this->private_key)||trim($this->private_key)=="") {
			throw new Exception("private_key should not be NULL!");
		}
		if (empty($this->alipay_public_key)||trim($this->alipay_public_key)=="") {
			throw new Exception("alipay_public_key should not be NULL!");
		}
		if (empty($this->postCharset)||trim($this->postCharset)=="") {
			throw new Exception("charset should not be NULL!");
		}
		if (empty($this->gateway_url)||trim($this->gateway_url)=="") {
			throw new Exception("gateway_url should not be NULL!");
		}
	}

	function pagePay($builder,$config) {
		$this->setParams($config);
		$biz_content=null;
		if(!empty($builder)){
			$biz_content = json_encode($builder,JSON_UNESCAPED_UNICODE);
		}

		$log = new Log($this->logFileName);
		$log->write($biz_content);

		$this->apiParas["biz_content"] = $biz_content;

		$response = $this->pageExecute($this, "post");
		$log = new Log($this->logFileName);
		$log->write("response: ".var_export($response,true));

		return $response;
	}

	function check($arr, $config){
		$this->setParams($config);

		$result = $this->rsaCheckV1($arr, $this->signtype);

		return $result;
	}

	public function pageExecute($request, $httpmethod = "POST") {
		$iv=$this->apiVersion;

		$sysParams["app_id"] = $this->appid;
		$sysParams["version"] = $iv;
		$sysParams["format"] = $this->format;
		$sysParams["sign_type"] = $this->signtype;
		$sysParams["method"] = $this->apiMethodName;
		$sysParams["timestamp"] = date("Y-m-d H:i:s");
		$sysParams["alipay_sdk"] = $this->alipaySdkVersion;
		$sysParams["notify_url"] = $this->notifyUrl;
		$sysParams["return_url"] = $this->returnUrl;
		$sysParams["charset"] = $this->postCharset;
		$sysParams["gateway_url"] = $this->gateway_url;

		$apiParams = $this->apiParas;

		$totalParams = array_merge($apiParams, $sysParams);

		$totalParams["sign"] = $this->generateSign($totalParams, $this->signtype);

		if ("GET" == strtoupper($httpmethod)) {
			$preString=$this->getSignContentUrlencode($totalParams);
			$requestUrl = $this->gateway_url."?".$preString;

			return $requestUrl;
		} else {
			foreach ($totalParams as $key => $value) {
				if (false === $this->checkEmpty($value)) {
					$value = str_replace("\"", "&quot;", $value);
					$totalParams[$key] = $value;
				} else {
					unset($totalParams[$key]);
				}
			}
			return $totalParams;
		}
	}

	protected function checkEmpty($value) {
		if (!isset($value))
			return true;
		if ($value === null)
			return true;
		if (trim($value) === "")
			return true;

		return false;
	}

	public function rsaCheckV1($params, $signType='RSA') {
		$sign = $params['sign'];
		$params['sign_type'] = null;
		$params['sign'] = null;
		return $this->verify($this->getSignContent($params), $sign, $signType);
	}

	function verify($data, $sign, $signType = 'RSA') {
		$pubKey= $this->alipay_public_key;
		$res = "-----BEGIN PUBLIC KEY-----\n" .
			wordwrap($pubKey, 64, "\n", true) .
			"\n-----END PUBLIC KEY-----";

		(trim($pubKey)) or die('Alipay public key error!');

		if ("RSA2" == $signType) {
			$result = (bool)openssl_verify($data, base64_decode($sign), $res, OPENSSL_ALGO_SHA256);
		} else {
			$result = (bool)openssl_verify($data, base64_decode($sign), $res);
		}

		return $result;
	}

	public function getSignContent($params) {
		ksort($params);

		$stringToBeSigned = "";
		$i = 0;
		foreach ($params as $k => $v) {
			if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {
				if ($i == 0) {
					$stringToBeSigned .= "$k" . "=" . "$v";
				} else {
					$stringToBeSigned .= "&" . "$k" . "=" . "$v";
				}
				$i++;
			}
		}

		unset ($k, $v);
		return $stringToBeSigned;
	}

	public function generateSign($params, $signType = "RSA") {
		return $this->sign($this->getSignContent($params), $signType);
	}

	protected function sign($data, $signType = "RSA") {
		$priKey=$this->private_key;
		$res = "-----BEGIN RSA PRIVATE KEY-----\n" .
			wordwrap($priKey, 64, "\n", true) .
			"\n-----END RSA PRIVATE KEY-----";

		if ("RSA2" == $signType) {
			openssl_sign($data, $sign, $res, OPENSSL_ALGO_SHA256);
		} else {
			openssl_sign($data, $sign, $res);
		}

		$sign = base64_encode($sign);
		return $sign;
	}

	function getPostCharset(){
		return trim($this->postCharset);
	}
}
