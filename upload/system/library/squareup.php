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
    const API_VERSION = 'v2';
    const ENDPOINT_ADD_CARD = 'customers/%s/cards';
    const ENDPOINT_AUTH = 'oauth2/authorize';
    const ENDPOINT_CAPTURE_TRANSACTION = 'locations/%s/transactions/%s/capture';
    const ENDPOINT_CUSTOMERS = 'customers';
    const ENDPOINT_DELETE_CARD = 'customers/%s/cards/%s';
    const ENDPOINT_GET_TRANSACTION = 'locations/%s/transactions/%s';
    const ENDPOINT_LOCATIONS = 'locations';
    const ENDPOINT_REFRESH_TOKEN = 'oauth2/clients/%s/access-token/renew';
    const ENDPOINT_REFUND_TRANSACTION = 'locations/%s/transactions/%s/refund';
    const ENDPOINT_TOKEN = 'oauth2/token';
    const ENDPOINT_TRANSACTIONS = 'locations/%s/transactions';
    const ENDPOINT_VOID_TRANSACTION = 'locations/%s/transactions/%s/void';
    const PAYMENT_FORM_URL = 'https://js.squareup.com/v2/paymentform';
    const SCOPE = 'MERCHANT_PROFILE_READ PAYMENTS_READ SETTLEMENTS_READ CUSTOMERS_READ CUSTOMERS_WRITE';
    const VIEW_TRANSACTION_URL = 'https://squareup.com/dashboard/sales/transactions/%s/by-unit/%s';
    const SQUARE_INTEGRATION_ID = 'sqi_65a5ac54459940e3600a8561829fd970';

    public function __construct($registry) {
        $this->session = $registry->get('session');
        $this->url = $registry->get('url');
        $this->config = $registry->get('config');
        $this->log = $registry->get('log');
        $this->customer = $registry->get('customer');
        $this->currency = $registry->get('currency');
        $this->registry = $registry;
    }

    public function api($request_data) {
        $url = self::API_URL;

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

        $this->debug("SQUAREUP DEBUG START...");
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

        $redirect_uri = str_replace('&amp;', '&', $this->url->link('extension/payment/squareup/oauth_callback', 'user_token=' . $this->session->data['user_token']));

        $this->session->data['payment_squareup_oauth_redirect'] = $redirect_uri;

        $params = array(
            'client_id' => $client_id,
            'response_type' => 'code',
            'scope' => self::SCOPE,
            'locale' => 'en-US',
            'session' => 'false',
            'state' => $state,
            'redirect_uri' => $redirect_uri
        );

        return self::API_URL . '/' . self::ENDPOINT_AUTH . '?' . http_build_query($params);
    }

    public function fetchLocations($access_token, &$first_location_id) {
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

    public function exchangeCodeForAccessToken($code) {
        $request_data = array(
            'method' => 'POST',
            'endpoint' => self::ENDPOINT_TOKEN,
            'no_version' => true,
            'parameters' => array(
                'client_id' => $this->config->get('payment_squareup_client_id'),
                'client_secret' => $this->config->get('payment_squareup_client_secret'),
                'redirect_uri' => $this->session->data['payment_squareup_oauth_redirect'],
                'code' => $code
            )
        );

        return $this->api($request_data);
    }

    public function debug($text) {
        if ($this->config->get('payment_squareup_debug')) {
            $this->log->write($text);
        }
    }

    public function refreshToken() {
        $request_data = array(
            'method' => 'POST',
            'endpoint' => sprintf(self::ENDPOINT_REFRESH_TOKEN, $this->config->get('payment_squareup_client_id')),
            'no_version' => true,
            'auth_type' => 'Client',
            'token' => $this->config->get('payment_squareup_client_secret'),
            'parameters' => array(
                'access_token' => $this->config->get('payment_squareup_access_token')
            )
        );

        return $this->api($request_data);
    }

    public function addCard($square_customer_id, $card_data) {
        $request_data = array(
            'method' => 'POST',
            'endpoint' => sprintf(self::ENDPOINT_ADD_CARD, $square_customer_id),
            'auth_type' => 'Bearer',
            'parameters' => $card_data
        );

        $result = $this->api($request_data);

        return array(
            'id' => $result['card']['id'],
            'card_brand' => $result['card']['card_brand'],
            'last_4' => $result['card']['last_4']
        );
    }

    public function deleteCard($square_customer_id, $card) {
        $request_data = array(
            'method' => 'DELETE',
            'endpoint' => sprintf(self::ENDPOINT_DELETE_CARD, $square_customer_id, $card),
            'auth_type' => 'Bearer'
        );

        return $this->api($request_data);
    }

    public function addLoggedInCustomer() {
        $request_data = array(
            'method' => 'POST',
            'endpoint' => self::ENDPOINT_CUSTOMERS,
            'auth_type' => 'Bearer',
            'parameters' => array(
                'given_name' => $this->customer->getFirstName(),
                'family_name' => $this->customer->getLastName(),
                'email_address' => $this->customer->getEmail(),
                'phone_number' => $this->customer->getTelephone(),
                'reference_id' => $this->customer->getId()
            )
        );

        $result = $this->api($request_data);

        return array(
            'customer_id' => $this->customer->getId(),
            'sandbox' => $this->config->get('payment_squareup_enable_sandbox'),
            'square_customer_id' => $result['customer']['id']
        );
    }

    public function addTransaction($data) {
        if ($this->config->get('payment_squareup_enable_sandbox')) {
            $location_id = $this->config->get('payment_squareup_sandbox_location_id');
        } else {
            $location_id = $this->config->get('payment_squareup_location_id');
        }

        $request_data = array(
            'method' => 'POST',
            'endpoint' => sprintf(self::ENDPOINT_TRANSACTIONS, $location_id),
            'auth_type' => 'Bearer',
            'parameters' => $data
        );

        $result = $this->api($request_data);

        return $result['transaction'];
    }

    public function getTransaction($location_id, $transaction_id) {
        $request_data = array(
            'method' => 'GET',
            'endpoint' => sprintf(self::ENDPOINT_GET_TRANSACTION, $location_id, $transaction_id),
            'auth_type' => 'Bearer'
        );

        $result = $this->api($request_data);

        return $result['transaction'];
    }

    public function captureTransaction($location_id, $transaction_id) {
        $request_data = array(
            'method' => 'POST',
            'endpoint' => sprintf(self::ENDPOINT_CAPTURE_TRANSACTION, $location_id, $transaction_id),
            'auth_type' => 'Bearer'
        );

        $this->api($request_data);

        return $this->getTransaction($location_id, $transaction_id);
    }

    public function voidTransaction($location_id, $transaction_id) {
        $request_data = array(
            'method' => 'POST',
            'endpoint' => sprintf(self::ENDPOINT_VOID_TRANSACTION, $location_id, $transaction_id),
            'auth_type' => 'Bearer'
        );

        $this->api($request_data);

        return $this->getTransaction($location_id, $transaction_id);
    }

    public function refundTransaction($location_id, $transaction_id, $reason, $amount, $currency, $tender_id) {
        $request_data = array(
            'method' => 'POST',
            'endpoint' => sprintf(self::ENDPOINT_REFUND_TRANSACTION, $location_id, $transaction_id),
            'auth_type' => 'Bearer',
            'parameters' => array(
                'idempotency_key' => uniqid(),
                'tender_id' => $tender_id,
                'reason' => $reason,
                'amount_money' => array(
                    'amount' => $this->lowestDenomination($amount, $currency),
                    'currency' => $currency
                )
            )
        );

        $this->api($request_data);

        return $this->getTransaction($location_id, $transaction_id);
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
}