<?php
class ControllerPaymentPPExpress extends Controller {
	protected function index() {
    	$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');

		if (!$this->config->get('pp_express_test')) {
    		$this->data['action'] = 'https://www.pp_express.com/cgi-bin/webscr';
  		} else {
			$this->data['action'] = 'https://www.sandbox.pp_express.com/cgi-bin/webscr';
		}		
		
		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		

		if (empty($comments)) {
			if (isset($HTTP_POST_VARS['ppecomments']) && tep_not_null($HTTP_POST_VARS['ppecomments'])) {
				$comments = tep_db_prepare_input($HTTP_POST_VARS['ppecomments']);

				$order->info['comments'] = $comments;
			}
		}

		if (MODULE_PAYMENT_pp_EXPRESS_TRANSACTION_SERVER == 'Live') {
			$api_url = 'https://api-3t.pp.com/nvp';
		} else {
			$api_url = 'https://api-3t.sandbox.pp.com/nvp';
		}

		$params = array(
			'USER'          => MODULE_PAYMENT_pp_EXPRESS_API_USERNAME,
			'PWD'           => MODULE_PAYMENT_pp_EXPRESS_API_PASSWORD,
			'VERSION'       => '3.2',
			'SIGNATURE'     => MODULE_PAYMENT_pp_EXPRESS_API_SIGNATURE,
			'METHOD'        => 'DoExpressCheckoutPayment',
			'TOKEN'         => $ppe_token,
			'PAYMENTACTION' => ((MODULE_PAYMENT_pp_EXPRESS_TRANSACTION_METHOD == 'Sale') ? 'Sale' : 'Authorization'),
			'PAYERID'       => $ppe_payerid,
			'AMT'           => $this->format_raw($order->info['total']),
			'CURRENCYCODE'  => $order->info['currency'],
			'BUTTONSOURCE'  => 'osCommerce22_Default_EC'
		);

		if (is_numeric($sendto) && ($sendto > 0)) {
			$params['SHIPTONAME'] = $order->delivery['firstname'] . ' ' . $order->delivery['lastname'];
			$params['SHIPTOSTREET'] = $order->delivery['street_address'];
			$params['SHIPTOCITY'] = $order->delivery['city'];
			$params['SHIPTOSTATE'] = tep_get_zone_code($order->delivery['country']['id'], $order->delivery['zone_id'], $order->delivery['state']);
			$params['SHIPTOCOUNTRYCODE'] = $order->delivery['country']['iso_code_2'];
			$params['SHIPTOZIP'] = $order->delivery['postcode'];
		}

		$post_string = '';

		foreach ($params as $key => $value) {
			$post_string .= $key . '=' . urlencode(trim($value)) . '&';
		}

		$post_string = substr($post_string, 0, -1);

		$response = $this->sendTransactionToGateway($api_url, $post_string);
		$response_array = array();
		
		parse_str($response, $response_array);

		if (($response_array['ACK'] != 'Success') && ($response_array['ACK'] != 'SuccessWithWarning')) {
			tep_redirect(tep_href_link(FILENAME_SHOPPING_CART, 'error_message=' . stripslashes($response_array['L_LONGMESSAGE0']), 'SSL'));
		}		



		$this->data['back'] = $this->url->https('checkout/payment');
		
		$this->id       = 'payment';
		$this->template = $this->config->get('config_template') . 'payment/pp_express.tpl';
		
		$this->render();		
	}
	
	function before_process() {
		global $order, $sendto, $ppe_token, $ppe_payerid, $HTTP_POST_VARS, $comments;


	}
	
	function sendTransactionToGateway($url, $parameters) {
		$server = parse_url($url);
	
		if (!isset($server['port'])) {
			$server['port'] = ($server['scheme'] == 'https') ? 443 : 80;
		}
		
		if (!isset($server['path'])) {
			$server['path'] = '/';
		}
		
		if (isset($server['user']) && isset($server['pass'])) {
			$header[] = 'Authorization: Basic ' . base64_encode($server['user'] . ':' . $server['pass']);
		}
		
		$curl = curl_init($server['scheme'] . '://' . $server['host'] . $server['path'] . (isset($server['query']) ? '?' . $server['query'] : ''));
			
		curl_setopt($curl, CURLOPT_PORT, $server['port']);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $parameters);
		
		$result = curl_exec($curl);
		
		curl_close($curl);
		
		return $result;
	}
}
?>