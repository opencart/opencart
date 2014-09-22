<?php
class ModelModulePPLogin extends Model {
	public function getTokens($code) {
		if ($this->config->get('pp_login_sandbox')) {
			$endpoint = 'https://api.sandbox.paypal.com/v1/oauth2/token';
		} else {
			$endpoint = 'https://api.paypal.com/v1/oauth2/token';
		}

		$request  = '';
		$request .= 'client_id=' . $this->config->get('pp_login_client_id');
		$request .= '&client_secret=' . $this->config->get('pp_login_secret');
		$request .= '&grant_type=authorization_code';
		$request .= '&code=' . $code;
		$request .= '&redirect_uri=' . urlencode($this->url->link('module/pp_login/login', '', 'SSL'));

		$additional_opts = array(
			CURLOPT_USERPWD    => $this->config->get('pp_login_client_id') . ':' . $this->config->get('pp_login_secret'),
			CURLOPT_POST       => true,
			CURLOPT_POSTFIELDS => $request
		);

		$curl = $this->curl($endpoint, $additional_opts);

		$this->log('cURL Response: ' . print_r($curl, 1));

		return $curl;
	}

	public function getUserInfo($access_token) {
		if ($this->config->get('pp_login_sandbox')) {
			$endpoint = 'https://api.sandbox.paypal.com/v1/identity/openidconnect/userinfo/?schema=openid';
		} else {
			$endpoint = 'https://api.paypal.com/v1/identity/openidconnect/userinfo/?schema=openid';
		}

		$header   = array();
		$header[] = 'Content-Type: application/json';
		$header[] = 'Authorization: Bearer ' . $access_token;

		$additional_opts = array(
			CURLOPT_HTTPHEADER => $header,
		);

		$curl = $this->curl($endpoint, $additional_opts);

		$this->log('cURL Response: ' . print_r($curl, 1));

		return $curl;
	}

	private function curl($endpoint, $additional_opts = array()) {
		$default_opts = array(
			CURLOPT_PORT           => 443,
			CURLOPT_HEADER         => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_FORBID_REUSE   => 1,
			CURLOPT_FRESH_CONNECT  => 1,
			CURLOPT_URL            => $endpoint,
		);

		$ch = curl_init($endpoint);

		$opts = $default_opts + $additional_opts;

		curl_setopt_array($ch, $opts);

		$response = json_decode(curl_exec($ch));

		curl_close($ch);

		return $response;
	}

	public function log($data) {
		if ($this->config->get('pp_login_debug')) {
			$backtrace = debug_backtrace();
			$this->log->write('Log In with PayPal debug (' . $backtrace[1]['class'] . '::' . $backtrace[1]['function'] . ') - ' . $data);
		}
	}
}