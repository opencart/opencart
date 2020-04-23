<?php
class PayPal {
	private $server = array(
		'sandbox' => 'https://api.sandbox.paypal.com',
		'production' => 'https://api.paypal.com'
	);
	private $environment = 'sandbox';
	private $partner_id = '';
	private $client_id = '';
	private $secret = '';
	private $access_token = '';
	private $errors = array();
	private $last_response = array();
		
	//IN:  paypal info
	public function __construct($paypal_info) {
		if (isset($paypal_info['partner_id']) && $paypal_info['partner_id']) {
			$this->partner_id = $paypal_info['partner_id'];
		}
		
		if (isset($paypal_info['client_id']) && $paypal_info['client_id']) {
			$this->client_id = $paypal_info['client_id'];
		}
		
		if (isset($paypal_info['secret']) && $paypal_info['secret']) {
			$this->secret = $paypal_info['secret'];
		}

		if (isset($paypal_info['environment']) && (($paypal_info['environment'] == 'production') || ($paypal_info['environment'] == 'sandbox'))) {
			$this->environment = $paypal_info['environment'];
		}
	}
	
	//IN:  token info
	//OUT: access token, if no return - check errors
	public function setAccessToken($token_info) {
		$command = '/v1/oauth2/token';
		
		$params = $token_info;
								
		$result = $this->execute('POST', $command, $params);
		
		if (isset($result['access_token']) && $result['access_token']) {
			$this->access_token = $result['access_token'];
			
			return $this->access_token;
		} else {
			$this->errors[] = $result;
						
			return false;
		}
	}
	
	//OUT: access token
	public function getAccessToken() {
		return $this->access_token;
	}
	
	//OUT: access token, if no return - check errors
	public function getClientToken() {
		$command = '/v1/identity/generate-token';
										
		$result = $this->execute('POST', $command);
		
		if (isset($result['client_token']) && $result['client_token']) {
			return $result['client_token'];
		} else {
			$this->errors[] = $result;
			
			return false;
		}
	}
					
	//IN:  partner id
	//OUT: merchant info, if no return - check errors
	public function getSellerCredentials($partner_id) {
		$command = '/v1/customer/partners/' . $partner_id . '/merchant-integrations/credentials';
				
		$result = $this->execute('GET', $command);
		
		if (isset($result['client_id']) && $result['client_id']) {
			return $result;
		} else {
			$this->errors[] = $result;
			
			return false;
		}
	}
		
	//IN:  webhook info
	public function createWebhook($webhook_info) {
		$command = '/v1/notifications/webhooks';
		
		$params = $webhook_info;
				
		$result = $this->execute('POST', $command, $params, true);
		
		if (isset($result['id']) && $result['id']) {
			return $result;
		} else {
			$this->errors[] = $result;
			
			return false;
		}
	}
	
	//IN:  webhook id
	//OUT: webhook info, if no return - check errors
	public function updateWebhook($webhook_id, $webhook_info) {
		$command = '/v1/notifications/webhooks/' . $webhook_id;
		
		$params = $webhook_info;
				
		$result = $this->execute('PATCH', $command, $params, true);
		
		if (isset($result['id']) && $result['id']) {
			return $result;
		} else {
			$this->errors[] = $result;
			
			return false;
		}
	}
	
	//IN:  webhook id
	public function deleteWebhook($webhook_id) {
		$command = '/v1/notifications/webhooks/' . $webhook_id;
				
		$result = $this->execute('DELETE', $command);
		
		return true;
	}
	
	//IN:  webhook id
	//OUT: webhook info, if no return - check errors
	public function getWebhook($webhook_id) {
		$command = '/v1/notifications/webhooks/' . $webhook_id;
				
		$result = $this->execute('GET', $command);
		
		if (isset($result['id']) && $result['id']) {
			return $result;
		} else {
			$this->errors[] = $result;
			
			return false;
		}
	}
	
	//OUT: webhooks info, if no return - check errors
	public function getWebhooks() {
		$command = '/v1/notifications/webhooks';
				
		$result = $this->execute('GET', $command);
		
		if (isset($result['webhooks']) && $result['webhooks']) {
			return $result['webhooks'];
		} else {
			$this->errors[] = $result;
			
			return false;
		}
	}
	
	//IN:  order info
	public function createOrder($order_info) {
		$command = '/v2/checkout/orders';
		
		$params = $order_info;
				
		$result = $this->execute('POST', $command, $params, true);
		
		if (isset($result['id']) && $result['id']) {
			return $result;
		} else {
			$this->errors[] = $result;
			
			return false;
		}
	}
	
	//IN:  order id
	//OUT: order info, if no return - check errors
	public function updateOrder($order_id, $order_info) {
		$command = '/v2/checkout/orders/' . $order_id;
		
		$params = $order_info;
				
		$result = $this->execute('PATCH', $command, $params, true);
		
		if (isset($result['id']) && $result['id']) {
			return $result;
		} else {
			$this->errors[] = $result;
			
			return false;
		}
	}
	
	//IN:  order id
	//OUT: order info, if no return - check errors
	public function getOrder($order_id) {
		$command = '/v2/checkout/orders/' . $order_id;
				
		$result = $this->execute('GET', $command);
		
		if (isset($result['id']) && $result['id']) {
			return $result;
		} else {
			$this->errors[] = $result;
			
			return false;
		}
	}
	
	//IN:  order id
	public function setOrderAuthorize($order_id) {
		$command = '/v2/checkout/orders/' . $order_id . '/authorize';
						
		$result = $this->execute('POST', $command);
		
		if (isset($result['id']) && $result['id']) {
			return $result;
		} else {
			$this->errors[] = $result;
			
			return false;
		}
	}
	
	//IN:  order id
	public function setOrderCapture($order_id) {
		$command = '/v2/checkout/orders/' . $order_id . '/capture';
						
		$result = $this->execute('POST', $command);
		
		if (isset($result['id']) && $result['id']) {
			return $result;
		} else {
			$this->errors[] = $result;
			
			return false;
		}
	}
				
	//OUT: number of errors
	public function hasErrors()	{
		return count($this->errors);
	}
	
	//OUT: array of errors
	public function getErrors()	{
		return $this->errors;
	}
	
	//OUT: last response
	public function getResponse() {
		return $this->last_response;
	}
	
	private function execute($method, $command, $params = array(), $json = false) {
		$this->errors = array();

		if ($method && $command) {
			$curl_options = array(
				CURLOPT_URL => $this->server[$this->environment] . $command,
				CURLOPT_HEADER => true,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_INFILESIZE => Null,
				CURLOPT_HTTPHEADER => array(),
				CURLOPT_CONNECTTIMEOUT => 10,
				CURLOPT_TIMEOUT => 10
			);
			
			$curl_options[CURLOPT_HTTPHEADER][] = 'Accept-Charset: utf-8';
			$curl_options[CURLOPT_HTTPHEADER][] = 'Accept: application/json';
			$curl_options[CURLOPT_HTTPHEADER][] = 'Accept-Language: en_US';
			$curl_options[CURLOPT_HTTPHEADER][] = 'Content-Type: application/json';
			$curl_options[CURLOPT_HTTPHEADER][] = 'PayPal-Request-Id: ' . $this->token(50);
			$curl_options[CURLOPT_HTTPHEADER][] = 'PayPal-Partner-Attribution-Id: OPENCARTLIMITED_Cart_OpenCartPCP';
			
			if ($this->access_token) {
				$curl_options[CURLOPT_HTTPHEADER][] = 'Authorization: Bearer ' . $this->access_token;
			} elseif ($this->client_id && $this->secret) {
				$curl_options[CURLOPT_USERPWD] = $this->client_id . ':' . $this->secret;
			} elseif ($this->client_id) {
				$curl_options[CURLOPT_USERPWD] = $this->client_id;
			}

			switch (strtolower(trim($method))) {
				case 'get':
					$curl_options[CURLOPT_HTTPGET] = true;
					$curl_options[CURLOPT_URL] .= '?' . $this->buildQuery($params, $json);
										
					break;
				case 'post':
					$curl_options[CURLOPT_POST] = true;
					$curl_options[CURLOPT_POSTFIELDS] = $this->buildQuery($params, $json);
										
					break;
				case 'patch':
					$curl_options[CURLOPT_POST] = true;
					$curl_options[CURLOPT_POSTFIELDS] = $this->buildQuery($params, $json);
					$curl_options[CURLOPT_CUSTOMREQUEST] = strtoupper($method);
										
					break;
				case 'delete':
					$curl_options[CURLOPT_POST] = true;
					$curl_options[CURLOPT_POSTFIELDS] = $this->buildQuery($params, $json);
					$curl_options[CURLOPT_CUSTOMREQUEST] = strtoupper($method);
										
					break;
				case 'put':
					$curl_options[CURLOPT_PUT] = true;
					
					if ($params) {
						if ($buffer = fopen('php://memory', 'w+')) {
							$params_string = $this->buildQuery($params, $json);
							fwrite($buffer, $params_string);
							fseek($buffer, 0);
							$curl_options[CURLOPT_INFILE] = $buffer;
							$curl_options[CURLOPT_INFILESIZE] = strlen($params_string);
						} else {
							$this->errors[] = array('name' => 'FAILED_OPEN_TEMP_FILE', 'message' => 'Unable to open a temporary file');
						}
					}
					
					break;
				case 'head':
					$curl_options[CURLOPT_NOBODY] = true;
					
					break;
				default:
					$curl_options[CURLOPT_CUSTOMREQUEST] = strtoupper($method);
			}
			
			$ch = curl_init();
			curl_setopt_array($ch, $curl_options);
			$response = curl_exec($ch);
			
			if (curl_errno($ch)) {
				$curl_code = curl_errno($ch);
				
				$constant = get_defined_constants(true);
				$curl_constant = preg_grep('/^CURLE_/', array_flip($constant['curl']));
				
				$this->errors[] = array('name' => $curl_constant[$curl_code], 'message' => curl_strerror($curl_code));
			}
	
            /*$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			
			if (($status_code >= 0) && ($status_code < 200)) {
				$this->errors[] = 'Server Not Found (' . $status_code . ')';
			}
			
			if (($status_code >= 300) && ($status_code < 400)) {
				$this->errors[] = 'Page Redirect (' . $status_code . ')';
			}
			
			if (($status_code >= 400) && ($status_code < 500)) {
				$this->errors[] = 'Page not found (' . $status_code . ')';
			}
			
			if ($status_code >= 500) {
				$this->errors[] = 'Server Error (' . $status_code . ')';
			}*/
			
			$head = '';
			$body = '';
			
			$parts = explode("\r\n\r\n", $response, 3);
			
			if (isset($parts[0]) && isset($parts[1])) {
				if (($parts[0] == 'HTTP/1.1 100 Continue') && isset($parts[2])) {
					list($head, $body) = array($parts[1], $parts[2]);
				} else {
					list($head, $body) = array($parts[0], $parts[1]);
				}
            }
			
            $response_headers = array();
            $header_lines = explode("\r\n", $head);
            array_shift($header_lines);
			
            foreach ($header_lines as $line) {
                list($key, $value) = explode(':', $line, 2);
                $response_headers[$key] = $value;
            }
			
			curl_close($ch);
			
			if (isset($buffer) && is_resource($buffer)) {
                fclose($buffer);
            }

			$this->last_response = json_decode($body, true);
			
			return $this->last_response;		
		}
	}
	
	private function buildQuery($params, $json) {
		if (is_string($params)) {
            return $params;
        }
		
		if ($json) {
			return json_encode($params);
		} else {
			return http_build_query($params);
		}
    }
	
	private function token($length = 32) {
		// Create random token
		$string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	
		$max = strlen($string) - 1;
	
		$token = '';
	
		for ($i = 0; $i < $length; $i++) {
			$token .= $string[mt_rand(0, $max)];
		}	
	
		return $token;
	}
}