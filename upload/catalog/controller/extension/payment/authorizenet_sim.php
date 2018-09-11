<?php
class ControllerExtensionPaymentAuthorizeNetSim extends Controller {
	public function index() {
		$this->load->language('extension/payment/authorizenet_sim');

		$data['button_confirm'] = $this->language->get('button_confirm');

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$data['x_login'] = $this->config->get('payment_authorizenet_sim_merchant');
		$data['x_fp_sequence'] = $this->session->data['order_id'];
		$data['x_fp_timestamp'] = time();
		$data['x_amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
		$data['x_fp_hash'] = null; // calculated later, once all fields are populated
		$data['x_show_form'] = 'PAYMENT_FORM';
		$data['x_test_request'] = $this->config->get('authorizenet_sim_mode');
		$data['x_type'] = 'AUTH_CAPTURE';
		$data['x_currency_code'] = $this->session->data['currency'];
		$data['x_invoice_num'] = $this->session->data['order_id'];
		$data['x_description'] = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');
		$data['x_first_name'] = $order_info['payment_firstname'];
		$data['x_last_name'] = $order_info['payment_lastname'];
		$data['x_company'] = $order_info['payment_company'];
		$data['x_address'] = $order_info['payment_address_1'] . ' ' . $order_info['payment_address_2'];
		$data['x_city'] = $order_info['payment_city'];
		$data['x_state'] = $order_info['payment_zone'];
		$data['x_zip'] = $order_info['payment_postcode'];
		$data['x_country'] = $order_info['payment_country'];
		$data['x_phone'] = $order_info['telephone'];
		$data['x_ship_to_first_name'] = $order_info['shipping_firstname'];
		$data['x_ship_to_last_name'] = $order_info['shipping_lastname'];
		$data['x_ship_to_company'] = $order_info['shipping_company'];
		$data['x_ship_to_address'] = $order_info['shipping_address_1'] . ' ' . $order_info['shipping_address_2'];
		$data['x_ship_to_city'] = $order_info['shipping_city'];
		$data['x_ship_to_state'] = $order_info['shipping_zone'];
		$data['x_ship_to_zip'] = $order_info['shipping_postcode'];
		$data['x_ship_to_country'] = $order_info['shipping_country'];
		$data['x_customer_ip'] = $this->request->server['REMOTE_ADDR'];
		$data['x_email'] = $order_info['email'];
		$data['x_relay_response'] = 'true';

		$data['x_fp_hash'] = hash_hmac('md5', $data['x_login'] . '^' . $data['x_fp_sequence'] . '^' . $data['x_fp_timestamp'] . '^' . $data['x_amount'] . '^' . $data['x_currency_code'], $this->config->get('payment_authorizenet_sim_key'));

		return $this->load->view('extension/payment/authorizenet_sim', $data);
	}

	public function callback() {
		if (md5($this->config->get('authorizenet_sim_response_key') . $this->request->post['x_login'] . $this->request->post['x_trans_id'] . $this->request->post['x_amount']) == strtolower($this->request->post['x_MD5_Hash'])) {
			$this->load->model('checkout/order');

			$order_info = $this->model_checkout_order->getOrder($this->request->post['x_invoice_num']);

			if ($order_info && $this->request->post['x_response_code'] == '1') {
				$message = '';

				if (isset($this->request->post['x_response_reason_text'])) {
					$message .= 'Response Text: ' . $this->request->post['x_response_reason_text'] . "\n";
				}

				if (isset($this->request->post['exact_issname'])) {
					$message .= 'Issuer: ' . $this->request->post['exact_issname'] . "\n";
				}

				if (isset($this->request->post['exact_issconf'])) {
					$message .= 'Confirmation Number: ' . $this->request->post['exact_issconf'];
				}

				if (isset($this->request->post['exact_ctr'])) {
					$message .= 'Receipt: ' . $this->request->post['exact_ctr'];
				}

				$this->model_checkout_order->addOrderHistory($this->request->post['x_invoice_num'], $this->config->get('payment_authorizenet_sim_order_status_id'), $message, true);

				$this->response->redirect($this->url->link('checkout/success'));
			} else {
				$this->response->redirect($this->url->link('checkout/failure'));
			}
		} else {
			$this->response->redirect($this->url->link('checkout/failure'));
		}
	}
}
