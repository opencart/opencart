<?php
class ControllerExtensionPaymentNMICollectJs extends Controller {
	public function index() {
		$this->load->language('extension/payment/nmi_collectjs');

		$data["payment_nmi_collectjs_tokenization_key"] = $this->config->get('payment_nmi_collectjs_tokenization_key');

		return $this->load->view('extension/payment/nmi_collectjs', $data);
	}

	public function send() {
		$url = 'https://secure.nmi.com/api/transact.php';

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$dup_seconds = $this->config->get('payment_nmi_collectjs_dup_seconds');
		if (!$dup_seconds) $dup_seconds = "0";

		$api_key = $this->config->get('payment_nmi_collectjs_api_key');

		$taxes = 0;
		foreach ($this->cart->getTaxes() as $key => $value) {
			$taxes += $value;
		}
		$shipping = 0;
		if ($this->cart->hasShipping() && isset($this->session->data['shipping_method']))
			$shipping = $this->session->data['shipping_method']['cost'];

		$data = array();

		$data['type'] = ($this->config->get('payment_nmi_collectjs_method') == 'capture') ? 'sale' : 'auth';
		if ($api_key) {
			$data['security_key'] = $api_key;
		} else {
			$data['username'] = $this->config->get('payment_nmi_collectjs_username');
			$data['password'] = $this->config->get('payment_nmi_collectjs_password');
		}
		$data['payment_token'] = $this->request->post['cc_number'];
		$data['amount'] = sprintf("%.2f", $order_info['total']);
		$data['currency'] = $this->session->data['currency'];
		$data['dup_seconds'] = $dup_seconds;
		$data['orderid'] = $this->session->data['order_id'];
		$data['ipaddress'] = $this->request->server['REMOTE_ADDR'];
		$data['tax'] = sprintf("%.2f", $taxes);
		$data['shipping'] = sprintf("%.2f", $shipping);
		$data['first_name'] = html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8');
		$data['last_name'] = html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');
		$data['company'] = html_entity_decode($order_info['payment_company'], ENT_QUOTES, 'UTF-8');
		$data['address1'] = html_entity_decode($order_info['payment_address_1'], ENT_QUOTES, 'UTF-8');
		$data['address2'] = html_entity_decode($order_info['payment_address_2'], ENT_QUOTES, 'UTF-8');
		$data['city'] = html_entity_decode($order_info['payment_city'], ENT_QUOTES, 'UTF-8');
		$data['state'] = html_entity_decode($order_info['payment_zone'], ENT_QUOTES, 'UTF-8');
		$data['zip'] = html_entity_decode($order_info['payment_postcode'], ENT_QUOTES, 'UTF-8');
		$data['country'] = html_entity_decode($order_info['payment_country'], ENT_QUOTES, 'UTF-8');
		$data['phone'] = $order_info['telephone'];
		$data['email'] = $order_info['email'];
		$data['order_description'] = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

		/* Customer Shipping Address Fields */
		if ($order_info['shipping_method']) {
			$data['shipping_firstname'] = html_entity_decode($order_info['shipping_firstname'], ENT_QUOTES, 'UTF-8');
			$data['shipping_lastname'] = html_entity_decode($order_info['shipping_lastname'], ENT_QUOTES, 'UTF-8');
			$data['shipping_company'] = html_entity_decode($order_info['shipping_company'], ENT_QUOTES, 'UTF-8');
			$data['shipping_address1'] = html_entity_decode($order_info['shipping_address_1'], ENT_QUOTES, 'UTF-8');
			$data['shipping_address2'] = html_entity_decode($order_info['shipping_address_2'], ENT_QUOTES, 'UTF-8');
			$data['shipping_city'] = html_entity_decode($order_info['shipping_city'], ENT_QUOTES, 'UTF-8');
			$data['shipping_state'] = html_entity_decode($order_info['shipping_zone'], ENT_QUOTES, 'UTF-8');
			$data['shipping_zip'] = html_entity_decode($order_info['shipping_postcode'], ENT_QUOTES, 'UTF-8');
			$data['shipping_country'] = html_entity_decode($order_info['shipping_country'], ENT_QUOTES, 'UTF-8');
		} else {
			$data['shipping_firstname'] = html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8');
			$data['shipping_lastname'] = html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');
			$data['shipping_company'] = html_entity_decode($order_info['payment_company'], ENT_QUOTES, 'UTF-8');
			$data['shipping_address1'] = html_entity_decode($order_info['payment_address_1'], ENT_QUOTES, 'UTF-8');
			$data['shipping_address2'] = html_entity_decode($order_info['payment_address_2'], ENT_QUOTES, 'UTF-8');
			$data['shipping_city'] = html_entity_decode($order_info['payment_city'], ENT_QUOTES, 'UTF-8');
			$data['shipping_state'] = html_entity_decode($order_info['payment_zone'], ENT_QUOTES, 'UTF-8');
			$data['shipping_zip'] = html_entity_decode($order_info['payment_postcode'], ENT_QUOTES, 'UTF-8');
			$data['shipping_country'] = html_entity_decode($order_info['payment_country'], ENT_QUOTES, 'UTF-8');
		}

		$curl = curl_init($url);
		$query = http_build_query($data);

		curl_setopt($curl, CURLOPT_PORT, 443);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $query);

		if ($this->config->get('payment_nmi_collectjs_debug')) $this->log->write('NMI COLLECTJS SEND: ' . $query);

		$response = curl_exec($curl);
		if ($this->config->get('payment_nmi_collectjs_debug')) $this->log->write('NMI COLLECTJS RECV: ' . $response);

		$json = array();

		if (curl_error($curl)) {
			$json['error'] = 'CURL ERROR: ' . curl_errno($curl) . '::' . curl_error($curl);

			$this->log->write('NMI COLLECTJS CURL ERROR: ' . curl_errno($curl) . '::' . curl_error($curl));
		} elseif ($response) {
			$response_data = array();
			parse_str($response, $response_data);

			if ($response_data['response'] == '1') { //success
				$message = '';
				if (isset($response_data['response_code'])) $message .= $response_data['response_code'] . ' ';
				if (isset($response_data['responsetext'])) $message .= $response_data['responsetext'] . "\n";
				if (isset($response_data['authcode'])) $message .= 'Authorization Code: ' . $response_data['authcode'] . "\n";
				if (isset($response_data['transactionid'])) $message .= 'Transaction ID: ' . $response_data['transactionid'] . "\n";
				if (isset($response_data['avsresponse'])) $message .= 'AVS Response: ' . $response_data['avsresponse'] . "\n";
				if (isset($response_data['cvvresponse'])) $message .= 'CVV Response: ' . $response_data['cvvresponse'];

				$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('payment_nmi_collectjs_order_status_id'), $message, false);
				$json['redirect'] = $this->url->link('checkout/success', 'language=' . $this->config->get('config_language'));
			}
			elseif ($response_data['response'] == '2') { //decline
				$json['error'] = 'Transaction declined by bank: ' . $response_data['response_code'] . ' ' . $response_data['responsetext'];
			}
			else { //error
				$json['error'] = 'Internal system error';
				$this->log->write('NMI COLLECTJS ERROR: ' . $response);
			}
		} else {
			$json['error'] = 'Empty Gateway Response';

			$this->log->write('NMI COLLECTJS CURL ERROR: Empty Gateway Response');
		}

		curl_close($curl);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
