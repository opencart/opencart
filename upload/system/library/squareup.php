<?php
class Squareup {
	private $session;
	private $url;
	private $config;
	private $log;
	private $customer;
	private $currency;
	private $registry;

	const API_URL = 'https://connect.squareup.com';
	const API_SANDBOX_URL = 'https://connect.squareupsandbox.com';
	const API_VERSION = 'v2';
	const ENDPOINT_AUTH = 'oauth2/authorize';
	const ENDPOINT_CANCEL_PAYMENT = 'payments/%s/cancel';
	const ENDPOINT_CAPTURE_PAYMENT = 'payments/%s/complete';
	const ENDPOINT_CARDS = 'cards';
	const ENDPOINT_CUSTOMERS = 'customers';
	const ENDPOINT_CUSTOMERS_SEARCH = 'customers/search';
	const ENDPOINT_LOCATIONS = 'locations';
	const ENDPOINT_ORDERS = 'orders';
	const ENDPOINT_PAYMENTS = 'payments';
	const ENDPOINT_PAYMENT_LINKS = 'online-checkout/payment-links';
	const ENDPOINT_REFUND = 'refunds';
	const ENDPOINT_TOKEN = 'oauth2/token';
	const SCOPE = 'MERCHANT_PROFILE_READ PAYMENTS_READ PAYMENTS_WRITE ORDERS_READ ORDERS_WRITE SETTLEMENTS_READ CUSTOMERS_READ CUSTOMERS_WRITE';
	const SQUARE_VERSION = '2026-01-22';

	public function __construct($registry) {
		// enable auto_load from system/library/squareup
		require(DIR_SYSTEM.'library/squareup/vendor/autoload.php');

		$this->session = $registry->get('session');
		$this->url = $registry->get('url');
		$this->config = $registry->get('config');
		$this->log = $registry->get('log');
		$this->customer = $registry->get('customer');
		$this->currency = $registry->get('currency');
		$this->registry = $registry;
	}

	public function api($request_data) {
		$is_sandbox = false;
		if (isset($request_data['token'])) {
			if ($request_data['token'] == $this->config->get('payment_squareup_sandbox_token')) {
				$is_sandbox = true;
			}
		}

		$url = ($is_sandbox) ? self::API_SANDBOX_URL : self::API_URL;

		if (empty($request_data['no_version'])) {
			$url .= '/' . self::API_VERSION;
		}

		$url .= '/' . $request_data['endpoint'];

		$curl_options = array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true
		);

		if (!empty($request_data['content_type'])) {
			$content_type = $request_data['content_type'];
		} else {
			$content_type = 'application/json';
		}

		// handle method and parameters
		if (isset($request_data['parameters']) && is_array($request_data['parameters']) && count($request_data['parameters'])) {
			$params = $this->encodeParameters($request_data['parameters'], $content_type);
		} else {
			$params = null;
		}

		switch ($request_data['method']) {
			case 'GET' :
				$curl_options[CURLOPT_POST] = false;

				if (is_string($params)) {
					$curl_options[CURLOPT_URL] .= ((strpos($url, '?') === false) ? '?' : '&') . $params;
				}

				break;
			case 'POST' :
				$curl_options[CURLOPT_POST] = true;

				if ($params !== null) {
					$curl_options[CURLOPT_POSTFIELDS] = $params;
				}

				break;
			default : 
				$curl_options[CURLOPT_CUSTOMREQUEST] = $request_data['method'];

				if ($params !== null) {
					$curl_options[CURLOPT_POSTFIELDS] = $params;
				}

				break;
		}

		// handle headers
		$added_headers = array();

		$added_headers[] = 'Square-Version: ' . ' ' . self::SQUARE_VERSION;

		if (!empty($request_data['auth_type'])) {
			if (empty($request_data['token'])) {
				if ($this->config->get('payment_squareup_enable_sandbox')) {
					$token = $this->config->get('payment_squareup_sandbox_token');
				} else {
					$token = $this->config->get('payment_squareup_access_token');
				}
			} else {
				// custom token trumps sandbox/regular one
				$token = $request_data['token'];
			}
			
			$added_headers[] = 'Authorization: ' . $request_data['auth_type'] . ' ' . $token;
		}

		if (!is_array($params)) {
			// curl automatically adds Content-Type: multipart/form-data when we provide an array
			$added_headers[] = 'Content-Type: ' . $content_type;
		}

		if (isset($request_data['headers']) && is_array($request_data['headers'])) {
			$curl_options[CURLOPT_HTTPHEADER] = array_merge($added_headers, $request_data['headers']);
		} else {
			$curl_options[CURLOPT_HTTPHEADER] = $added_headers;
		}

		$this->debug("");
		$this->debug("");
		$this->debug("SQUAREUP DEBUG START...");
		$this->debug("SQUAREUP METHOD: " . $request_data['method']);
		$this->debug("SQUAREUP ENDPOINT: " . $curl_options[CURLOPT_URL]);
		$this->debug("SQUAREUP HEADERS: " . print_r($curl_options[CURLOPT_HTTPHEADER], true));
		$this->debug("SQUAREUP PARAMS: " . $params);

		// Fire off the request
		$ch = curl_init();
		curl_setopt_array($ch, $curl_options);
		$result = curl_exec($ch);

		if ($result) {
			$this->debug("SQUAREUP RESULT: " . $result);

			curl_close($ch);

			$return = json_decode($result, true);

			if (!empty($return['errors'])) {
				throw new \Squareup\Exception($this->registry, $return['errors']);
			} else {
				return $return;
			}
		} else {
			$info = curl_getinfo($ch);

			curl_close($ch);

			throw new \Squareup\Exception($this->registry, "CURL error. Info: " . print_r($info, true), true);
		}
	}

    public function verifyToken($access_token) {
		// We use a quick "live" check by calling a lightweight endpoint like ListLocations. 
		// If the call succeeds, the token is valid and active.
		// If the call fails with a NOT_AUTHORIZED error, the token is either invalid, revoked, or expired
		try {
			$request_data = array(
				'method' => 'GET',
				'endpoint' => self::ENDPOINT_LOCATIONS,
				'auth_type' => 'Bearer',
				'token' => $access_token
			);

			$this->api($request_data);
		} catch (\Squareup\Exception $e) {
			if ($e->isAccessTokenRevoked() || $e->isAccessTokenExpired()) {
				return false;
			}

			// In case some other error occurred
			throw $e;
		}

		return true;
	}

    public function authLink($client_id) {
		$state = $this->authState();

		$redirect_uri = str_replace('&amp;', '&', $this->url->link('extension/payment/squareup/oauth_callback', 'user_token=' . $this->session->data['user_token'], true));

		$this->session->data['payment_squareup_oauth_redirect'] = $redirect_uri;

		$params = array(
			'client_id' => $client_id,
//			'response_type' => 'code',
			'scope' => self::SCOPE,
//			'locale' => 'en-US',
			'session' => 'false',
			'state' => $state,
			'redirect_uri' => $redirect_uri
		);

		return self::API_URL . '/' . self::ENDPOINT_AUTH . '?' . http_build_query($params);
    }

    public function listLocations($access_token, &$first_location_id) {
		// see https://developer.squareup.com/reference/square/locations-api/list-locations
		$request_data = array(
			'method' => 'GET',
			'endpoint' => self::ENDPOINT_LOCATIONS,
			'auth_type' => 'Bearer',
			'token' => $access_token
		);

		$api_result = $this->api($request_data);

		$locations = array_filter($api_result['locations'], array($this, 'filterLocation'));

		if (!empty($locations)) {
			$first_location = current($locations);
			$first_location_id = $first_location['id'];
		} else {
			$first_location_id = null;
		}

		return $locations;
	}

	public function retrieveLocation($access_token, $location_id) {
		// see https://developer.squareup.com/reference/square/locations-api/retrieve-location
		$request_data = array(
			'method' => 'GET',
			'endpoint' => self::ENDPOINT_LOCATIONS.'/'.$location_id,
			'auth_type' => 'Bearer',
			'token' => $access_token
		);
		$api_result = $this->api($request_data);
		return isset($api_result['location']) ? $api_result : null;
	}

	public function exchangeCodeForAccessAndRefreshTokens($code) {
		// see https://developer.squareup.com/reference/square/o-auth-api/obtain-token
		$request_data = array(
			'method' => 'POST',
			'endpoint' => self::ENDPOINT_TOKEN,
			'no_version' => true,
			'parameters' => array(
				'client_id' => $this->config->get('payment_squareup_client_id'),
				'client_secret' => $this->config->get('payment_squareup_client_secret'),
				'redirect_uri' => $this->session->data['payment_squareup_oauth_redirect'],
				'code' => $code,
				'grant_type' => 'authorization_code'
			)
		);

		return $this->api($request_data);
	}

	public function debug($text) {
		if ($this->config->get('payment_squareup_debug')) {
			$this->log->write($text);
		}
	}

	public function retrieveOrder($access_token, $order_id) {
		// see https://developer.squareup.com/reference/square/orders-api/retrieve-order
		$request_data = array(
			'method' => 'GET',
			'endpoint' => self::ENDPOINT_ORDERS.'/'.$order_id,
			'auth_type' => 'Bearer',
			'token' => $access_token
		);
		$api_result = $this->api($request_data);
		return isset($api_result['order']) ? $api_result : null;
	}

	public function getPayment($access_token, $payment_id) {
		// see https://developer.squareup.com/reference/square/payments-api/get-payment
		$request_data = array(
			'method' => 'GET',
			'endpoint' => self::ENDPOINT_PAYMENTS.'/'.$payment_id,
			'auth_type' => 'Bearer',
			'token' => $access_token
		);
		$api_result = $this->api($request_data);
		return isset($api_result['payment']) ? $api_result : null;
	}

	public function createPaymentLink($access_token, $amount, $currency, $redirect_url, $billing_address, $email, $phone, $item_summary) {
		// see https://developer.squareup.com/reference/square/checkout-api/create-payment-link
		if ($access_token == $this->config->get('payment_squareup_sandbox_token')) {
			$location_id = $this->config->get('payment_squareup_sandbox_location_id');
		} else {
			$location_id = $this->config->get('payment_squareup_location_id');
		}
		$idempotency_key = bin2hex(random_bytes(16));

		$request_data = array(
			'method' => 'POST',
			'endpoint' => self::ENDPOINT_PAYMENT_LINKS,
			'auth_type' => 'Bearer',
			'token' => $access_token,
			'parameters' => array(
				'idempotency_key' => $idempotency_key,
				'autocomplete' => true,
				'quick_pay' => array(
					'name' => $this->config->get('config_name') . ' - ' . $item_summary,
					'price_money' => array(
						'amount' => $this->lowestDenomination($amount, $currency),
						'currency' => $currency
					),
					'location_id' => $location_id
				),
				/*
				'order' => array(
					'location_id' => $location_id,
					'line_items' => array(
						array(
							'name' => $this->config->get('config_name') . ' - ' . $item_summary,
							'quantity' => '1',
							'base_price_money' => array(
								'amount' => $this->lowestDenomination($amount, $currency),
								'currency' => $currency
							)
						)
					)
				),
				*/
				'checkout_options' => array(
					'redirect_url' => $redirect_url,
					'ask_for_shipping_address' => false,
					'enable_coupon' => false,
					'accepted_payment_methods' => array(
						'apple_pay' => false,
						'google_pay' => false,
						'cash_app_pay' => false,
						'afterpay_clearpay' => false
					)
				),
				'pre_populated_data' => array(
					'buyer_email' => $email,
					'buyer_address' => ($billing_address) ? $billing_address : array(),
					'buyer_phone_number' => $phone
				)
			)
		);

		$result = $this->api($request_data);
		return $result;
	}

	public function refreshToken() {
		// see https://developer.squareup.com/reference/square/o-auth-api/obtain-token
		$request_data = array(
			'method' => 'POST',
			'endpoint' => self::ENDPOINT_TOKEN,
			'no_version' => true,
			'auth_type' => 'Bearer',
			'token' => $this->config->get('payment_squareup_access_token'),
			'parameters' => array(
				'client_id' => $this->config->get('payment_squareup_client_id'),
				'grant_type' => 'refresh_token',
				'client_secret' => $this->config->get('payment_squareup_client_secret'),
				'refresh_token' => $this->config->get('payment_squareup_refresh_token')
			)
		);

		return $this->api($request_data);
	}

	public function listCards($access_token, $customer_id) {
		// see https://developer.squareup.com/reference/square/cards-api/list-cards
		$request_data = array(
			'method' => 'GET',
			'endpoint' => self::ENDPOINT_CARDS,
			'auth_type' => 'Bearer',
			'token' => $access_token,
			'parameters' => array(
				'customer_id' => $customer_id,
				'include_disabled' => false
			)
		);

		$result = $this->api($request_data);
		return $result;
	}

	public function createCard($access_token, $source_id, $verification_token, $customer_id, $billing_address) {
		// see https://developer.squareup.com/reference/square/cards-api/create-card
		$idempotency_key = bin2hex(random_bytes(16));

		$parameters = array(
			'idempotency_key' => $idempotency_key,
			'source_id'       => $source_id,
			'card'            => array(
				'customer_id'     => $customer_id,
				'billing_address' => ($billing_address) ? $billing_address : array(),
			)
		);

		// Only inject the verification_token parameter if it is present and not empty
		if (!empty($verification_token)) {
			$parameters['verification_token'] = $verification_token;
		}

		$request_data = array(
			'method'     => 'POST',
			'endpoint'   => self::ENDPOINT_CARDS,
			'auth_type'  => 'Bearer',
			'token'      => $access_token,
			'parameters' => $parameters
		);

		$result = $this->api($request_data);
		return $result;
	}

	public function disableCard($access_token, $card_id) {
		// see https://developer.squareup.com/reference/square/cards-api/disable-card
		$request_data = array(
			'method' => 'POST',
			'endpoint' => self::ENDPOINT_CARDS . "/$card_id/disable",
			'auth_type' => 'Bearer',
			'token' => $access_token,
			'parameters' => array()
		);

		$result = $this->api($request_data);
		return $result;
	}

	public function createCustomer($access_token, $billing_address, $email, $phone) {
		// see https://developer.squareup.com/reference/square/customers-api/create-customer
		$idempotency_key = bin2hex(random_bytes(16));

		$request_data = array(
			'method' => 'POST',
			'endpoint' => self::ENDPOINT_CUSTOMERS,
			'auth_type' => 'Bearer',
			'token' => $access_token,
			'parameters' => array(
				'idempotency_key' => $idempotency_key,
				'given_name' => $billing_address['first_name'],
				'family_name' => $billing_address['last_name'],
				'email_address' => $email,
				'address' => ($billing_address) ? $billing_address : array(),
				'phone_number' => $phone
			)
		);

		$result = $this->api($request_data);
		return $result;
	}

	public function searchCustomers($access_token, $email, $phone) {
		// see https://developer.squareup.com/reference/square/customers-api/search-customers
		$request_data = array(
			'method' => 'POST',
			'endpoint' => self::ENDPOINT_CUSTOMERS_SEARCH,
			'auth_type' => 'Bearer',
			'token' => $access_token,
			'parameters' => array(
				'query' => array(
					'filter' => array(
						'email_address' => array(
							'exact' => $email
						),
						'phone_number' => array(
							'exact' => $phone
						)
					)
				)
			)
		);

		$result = $this->api($request_data);
		return $result;
	}

	public function completePayment($access_token, $payment_id) {
		// see https://developer.squareup.com/reference/square/payments-api/complete-payment
		$request_data = array(
			'method' => 'POST',
			'endpoint' => str_replace('%s', $payment_id, self::ENDPOINT_CAPTURE_PAYMENT),
			'auth_type' => 'Bearer',
			'token' => $access_token,
			'parameters' => array()
		);

		$result = $this->api($request_data);
		return $result;
	}

	public function cancelPayment($access_token, $payment_id) {
		// see https://developer.squareup.com/reference/square/payments-api/cancel-payment
		$request_data = array(
			'method' => 'POST',
			'endpoint' => str_replace('%s', $payment_id, self::ENDPOINT_CANCEL_PAYMENT),
			'auth_type' => 'Bearer',
			'token' => $access_token,
			'parameters' => array()
		);

		$result = $this->api($request_data);
		return $result;
	}

	public function refundPayment($access_token, $payment_id, $amount, $currency, $reason) {
		// see https://developer.squareup.com/docs/payments-api/refund-payments
		$idempotency_key = bin2hex(random_bytes(16));

		$request_data = array(
			'method' => 'POST',
			'endpoint' => self::ENDPOINT_REFUND,
			'auth_type' => 'Bearer',
			'token' => $access_token,
			'parameters' => array(
				'idempotency_key' => $idempotency_key,
				'payment_id' => $payment_id,
				'amount_money' => array(
					'amount' => $this->lowestDenomination($amount, $currency),
					'currency' => $currency
				),
				'reason' => $reason
			)
		);

		$result = $this->api($request_data);
		return $result;
	}

	public function createPayment($access_token, $amount, $currency, $billing_address, $email, $phone, $source_id, $reference_id, $statement_description_identifier, $customer_id='', $verification_token='') {
		// see https://developer.squareup.com/reference/square/payments-api/create-payment
		$is_sandbox = $this->config->get('payment_squareup_enable_sandbox') ? true : false;
		$location_id = ($is_sandbox) ? $this->config->get('payment_squareup_sandbox_location_id') : $this->config->get('payment_squareup_location_id');
		$idempotency_key = bin2hex(random_bytes(16));
//		$autocomplete = !$this->cart->hasRecurringProducts() && $this->config->get('payment_squareup_delay_capture');
		$autocomplete = $this->config->get('payment_squareup_delay_capture') ? false : true;

		$request_data = array(
			'method' => 'POST',
			'endpoint' => self::ENDPOINT_PAYMENTS,
			'auth_type' => 'Bearer',
			'token' => $access_token,
			'parameters' => array(
				'idempotency_key' => $idempotency_key,
				'amount_money' => array(
					'amount' => $this->lowestDenomination($amount, $currency),
					'currency' => $currency
				),
				'source_id' => $source_id,
				'autocomplete' => $autocomplete,
				'location_id' => $location_id,
				'reference_id' => $reference_id,
				'billing_address' => ($billing_address) ? $billing_address : array(),
				'buyer_email_address' => $email,
//				'buyer_phone_number' => $phone,
				'statement_description_identifier' => $statement_description_identifier,
				'customer_details' => array(
					'customer_initiated' => true,
					'seller_keyed_in' => false
				)
			)
		);

		if ($verification_token) {
			$request_data['parameters']['verification_token'] = $verification_token;
		}

		if ($this->startsWith($source_id,'ccof:')) {
			// source_id refers to a customer card on file, e.g. for a recurring payment
			$request_data['parameters']['customer_id'] = $customer_id;
			$request_data['parameters']['recurring'] = true;
			$request_data['parameters']['customer_details']['customer_initiated'] = false;
		}

		$result = $this->api($request_data);
		return $result;
	}

	public function lowestDenomination($value, $currency) {
		$power = $this->currency->getDecimalPlace($currency);

		$value = (float)$value;

		return (int)($value * pow(10, $power));
	}

	public function standardDenomination($value, $currency) {
		$power = $this->currency->getDecimalPlace($currency);

		$value = (int)$value;

		return (float)($value / pow(10, $power));
	}

	protected function filterLocation($location) {
		if (empty($location['capabilities'])) {
			return false;
		}

		return in_array('CREDIT_CARD_PROCESSING', $location['capabilities']);
	}

	protected function encodeParameters($params, $content_type) {
		switch ($content_type) {
			case 'application/json' :
				return json_encode($params);
			case 'application/x-www-form-urlencoded' :
				return http_build_query($params);
			default :
			case 'multipart/form-data' :
				// curl will handle the params as multipart form data if we just leave it as an array
				return $params;
		}
	}

	protected function authState() {
		if (!isset($this->session->data['payment_squareup_oauth_state'])) {
			$this->session->data['payment_squareup_oauth_state'] = bin2hex(openssl_random_pseudo_bytes(32));
		}

		return $this->session->data['payment_squareup_oauth_state'];
	}

	public function phoneFormat($raw_number, $country_code) {
		require_once( DIR_SYSTEM . 'library/squareup/vendor/autoload.php' );

		$phone_util = libphonenumber\PhoneNumberUtil::getInstance();
		try {
			$number_proto = $phone_util->parse($raw_number, $country_code);
			
			// 1. STricter Check: isValidNumber verifies the number type, prefix, and full length
			if (!$phone_util->isValidNumber($number_proto)) {
				return ''; 
			}

			$result = $phone_util->format($number_proto, libphonenumber\PhoneNumberFormat::E164);
			
			// 2. Square Safety Guard: Strip the '+' and count the actual digits
			$digit_count = strlen(preg_replace('/\D/', '', $result));
			
			// Square API strictly requires between 9 and 16 digits
			if ($digit_count >= 9 && $digit_count <= 16) {
				return $result;
			}
			
			return ''; // Drop it if it falls outside Square's constraints
			
		} catch (libphonenumber\NumberParseException $e) {
			return '';
		}
	}

	protected function startsWith($haystack, $needle) {
		if (strlen($haystack) < strlen($needle)) {
			return false;
		}
		return (substr($haystack, 0, strlen($needle)) == $needle);
	}
}