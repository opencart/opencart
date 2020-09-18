<?php
class ModelExtensionPaymentAlipayCross extends Model {
	var $https_verify_url = 'https://mapi.alipay.com/gateway.do?service=notify_verify&';
	var $https_verify_url_test = 'https://openapi.alipaydev.com/gateway.do?service=notify_verify&';
	var $alipay_config;

	public function getMethod($address, $total) {
		$this->load->language('extension/payment/alipay_cross');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('payment_alipay_cross_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if ($this->config->get('payment_alipay_cross_total') > 0 && $this->config->get('payment_alipay_cross_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('payment_alipay_cross_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code'       => 'alipay_cross',
				'title'      => $this->language->get('text_title'),
				'terms'      => '',
				'sort_order' => $this->config->get('payment_alipay_cross_sort_order')
			);
		}

		return $method_data;
	}

	function buildRequestMysign($para_sort) {
		$prestr = $this->createLinkstring($para_sort);

		$mysign = "";
		switch (strtoupper(trim($this->alipay_config['sign_type']))) {
			case "MD5" :
				$mysign = $this->md5Sign($prestr, $this->alipay_config['key']);
				break;
			default :
				$mysign = "";
		}

		return $mysign;
	}


	function buildRequestPara($alipay_config, $para_temp) {
		$this->alipay_config = $alipay_config;

		$para_filter = $this->paraFilter($para_temp);

		$para_sort = $this->argSort($para_filter);

		$mysign = $this->buildRequestMysign($para_sort);

		$para_sort['sign'] = $mysign;
		$para_sort['sign_type'] = strtoupper(trim($this->alipay_config['sign_type']));

		return $para_sort;
	}

	function verifyNotify($alipay_config){
		$this->alipay_config = $alipay_config;

		if(empty($_POST)) {
			return false;
		}
		else {
			$isSign = $this->getSignVeryfy($_POST, $_POST["sign"]);

			$responseTxt = 'false';
			if (! empty($_POST["notify_id"])) {
				$responseTxt = $this->getResponse($_POST["notify_id"]);
			}

			//Veryfy
			if (preg_match("/true$/i",$responseTxt) && $isSign) {
				return true;
			} else {
				$this->log->write($responseTxt);
				return false;
			}
		}
	}

	function getSignVeryfy($para_temp, $sign) {
		$para_filter = $this->paraFilter($para_temp);

		$para_sort = $this->argSort($para_filter);

		$prestr = $this->createLinkstring($para_sort);

		switch (strtoupper(trim($this->alipay_config['sign_type']))) {
			case "MD5" :
				$isSgin = $this->md5Verify($prestr, $sign, $this->alipay_config['key']);
				break;
			default :
				$isSgin = false;
		}

		return $isSgin;
	}

	function getResponse($notify_id) {
		$partner = trim($this->alipay_config['partner']);
		$veryfy_url = $this->config->get('payment_alipay_cross_test') == "sandbox" ? $this->https_verify_url_test : $this->https_verify_url;
		$veryfy_url .= "partner=" . $partner . "&notify_id=" . $notify_id;
		$responseTxt = $this->getHttpResponseGET($veryfy_url, $this->alipay_config['cacert']);

		return $responseTxt;
	}

	function createLinkstring($para) {
		$arg  = "";
		while (list ($key, $val) = each ($para)) {
			$arg .= $key . "=" . $val . "&";
		}
		//remove the last char '&'
		$arg = substr($arg, 0, count($arg)-2);

		return $arg;
	}

	function paraFilter($para) {
		$para_filter = array();
		while (list ($key, $val) = each ($para)) {
			if($key == "sign" || $key == "sign_type" || $val == "")continue;
			else	$para_filter[$key] = $para[$key];
		}
		return $para_filter;
	}

	function argSort($para) {
		ksort($para);
		reset($para);
		return $para;
	}

	function getHttpResponseGET($url,$cacert_url) {
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HEADER, 0 );
		curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($curl, CURLOPT_CAINFO,$cacert_url);
		$responseText = curl_exec($curl);
		if (!$responseText) {
			$this->log->write('ALIPAY NOTIFY CURL_ERROR: ' . var_export(curl_error($curl), true));
		}
		curl_close($curl);

		return $responseText;
	}

	function md5Sign($prestr, $key) {
		$prestr = $prestr . $key;
		return md5($prestr);
	}

	function md5Verify($prestr, $sign, $key)
	{
		$prestr = $prestr . $key;
		$mysgin = md5($prestr);

		if ($mysgin == $sign) {
			return true;
		} else {
			return false;
		}
	}
}

