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

		if (!$this->config->get('pp_direct_test')) {
			$api_endpoint = 'https://api-3t.pp.com/nvp';
		} else {
			$api_endpoint = 'https://api-3t.sandbox.pp.com/nvp';
		}

		$payment_data = array(
			'USER'          => $this->config->get('pp_direct_username'),
			'PWD'           => $this->config->get('pp_direct_password'),
			'VERSION'       => '3.2',
			'SIGNATURE'     => $this->config->get('pp_direct_signature'),
			'METHOD'        => 'DoExpressCheckoutPayment',
			'TOKEN'         => $ppe_token,
			'PAYMENTACTION' => (MODULE_PAYMENT_pp_EXPRESS_TRANSACTION_METHOD == 'Sale') ? 'Sale' : 'Authorization',
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



		$this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/payment';
		
		$this->id = 'payment';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/pp_express.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/pp_express.tpl';
		} else {
			$this->template = 'default/template/payment/pp_express.tpl';
		}	

		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));		
	}
}
?>