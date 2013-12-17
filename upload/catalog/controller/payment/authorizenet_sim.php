<?php
class ControllerPaymentAuthorizeNetSim extends Controller {
	public function index() {
		$this->load->language('payment/authorizenet_sim');
		
    	$data['button_confirm'] = $this->language->get('button_confirm');
		$data['action'] = $this->config->get('authorizenet_sim_url');
		
		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
		/* 6.1 Essential Fields
		 * The following parameters are required, and validated with each request. If one
		 * is missing or the validation fails the customer will see an error page. The
		 * merchant will also receive an email explaining the problem.
		 */

		$data['x_login'] = $this->config->get('authorizenet_sim_merchant');
		$data['x_fp_sequence'] = $this->session->data['order_id'];
		$data['x_fp_timestamp'] = time();
		$data['x_amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);	
		$data['x_fp_hash'] = null; // calculated later, once all fields are populated		
		$data['x_show_form'] = 'PAYMENT_FORM';
		
		$mode = $this->config->get('authorizenet_sim_test');
		if ($mode == '0') {
			$data['x_test_request'] = 'false';
		} else {
			$data['x_test_request'] = 'true';
		}
		$data['x_type'] = 'AUTH_CAPTURE';
		$data['x_currency_code'] = $this->currency->getCode();
		
		/* Order Information Fields */
		$data['x_invoice_num'] = $this->session->data['order_id'];
		$data['x_description'] = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');
		
		/* Customer Name and Billing Address Fields */
		$data['x_first_name'] = html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8');
		$data['x_last_name'] = html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');
		$data['x_company'] = html_entity_decode($order_info['payment_company'], ENT_QUOTES, 'UTF-8');
		$data['x_address'] = html_entity_decode($order_info['payment_address_1'], ENT_QUOTES, 'UTF-8') . ' ' . html_entity_decode($order_info['payment_address_2'], ENT_QUOTES, 'UTF-8');
		$data['x_city'] = html_entity_decode($order_info['payment_city'], ENT_QUOTES, 'UTF-8');
		$data['x_state'] = html_entity_decode($order_info['payment_zone'], ENT_QUOTES, 'UTF-8');
		$data['x_zip'] = html_entity_decode($order_info['payment_postcode'], ENT_QUOTES, 'UTF-8');
		$data['x_country'] = html_entity_decode($order_info['payment_country'], ENT_QUOTES, 'UTF-8');
		$data['x_phone'] = $order_info['telephone'];
		
		/* Customer Shipping Address Fields */
		$data['x_ship_to_first_name'] = html_entity_decode($order_info['shipping_firstname'], ENT_QUOTES, 'UTF-8');
		$data['x_ship_to_last_name'] = html_entity_decode($order_info['shipping_lastname'], ENT_QUOTES, 'UTF-8');
		$data['x_ship_to_company'] = html_entity_decode($order_info['shipping_company'], ENT_QUOTES, 'UTF-8');
		$data['x_ship_to_address'] = html_entity_decode($order_info['shipping_address_1'], ENT_QUOTES, 'UTF-8') . ' ' . html_entity_decode($order_info['shipping_address_2'], ENT_QUOTES, 'UTF-8');
		$data['x_ship_to_city'] = html_entity_decode($order_info['shipping_city'], ENT_QUOTES, 'UTF-8');
		$data['x_ship_to_state'] = html_entity_decode($order_info['shipping_zone'], ENT_QUOTES, 'UTF-8');
		$data['x_ship_to_zip'] = html_entity_decode($order_info['shipping_postcode'], ENT_QUOTES, 'UTF-8');
		$data['x_ship_to_country'] = html_entity_decode($order_info['shipping_country'], ENT_QUOTES, 'UTF-8');
		
		/* Additional Customer Data Field */
		$data['x_customer_ip'] = $this->request->server['REMOTE_ADDR'];
		$data['x_email'] = $order_info['email'];
		
		/* 7 Relay Response Mode */
		$data['x_relay_response'] = 'true';
					
		// calculate this after all our fields are generated
		$data['x_fp_hash'] = $this->calculateFpHash($data['x_login'], $data['x_fp_sequence'], $data['x_fp_timestamp'], $data['x_amount'], $data['x_currency_code']);
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/authorizenet_sim.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/authorizenet_sim.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/authorizenet_sim.tpl', $data);
		}
	}
	
	public function calculateFpHash($login, $fp_sequence, $timestamp, $amount, $currency) {
		$this->load->library('hash'); 
		$hash = new Hash();

		$key  = $this->config->get('authorizenet_sim_key');
		
		$code = $login . "^" . $fp_sequence . "^" . $timestamp . "^" . $amount . "^" . $currency;
							
		$fp_hash = $hash->hmac_md5($code, $key);
	
		return $fp_hash;
	}

	public function calculateResponseHash() {
		$this->load->library('hash'); 
		$hash = new Hash();
		
		$data = $this->request->post;
		
		$code = $this->config->get('authorizenet_sim_md5') . 
			$this->config->get('authorizenet_sim_merchant') . $data['x_trans_id'] . $data['x_amount'];
	
		return md5($code);
	}
	
	public function callback() {
		$details =& $this->request->post;

		// MD5 Generation
		$data['md5_hash'] = $this->calculateResponseHash();
		
		$data['response_hash'] = strtolower($details['x_MD5_Hash']);
		$data['x_response_reason_text'] = $details['x_response_reason_text'];
		$data['x_response_code'] = $details['x_response_code'];
		$data['order_id'] = $details['x_invoice_num'];
		
		$data['button_back'] = $this->language->get('button_back');
		$data['button_confirm'] = $this->language->get('button_continue');
		$data['confirm'] = $this->url->link('checkout/success');
		$data['back'] = $this->url->link('checkout/checkout');
		
		if (isset($data['md5'])) {
			if ($data['response_hash'] == $data['md5_hash']) {
				$order_id = $data['order_id'];
				$this->load->model('checkout/order');
				$order_info = $this->model_checkout_order->getOrder($order_id);
				
				if ($order_info) {
					if ($data['x_response_code'] == '1') {
						$this->model_checkout_order->confirm($order_id, $this->config->get('authorizenet_sim_order_status_id'));
					} else {
						$this->model_checkout_order->confirm($order_id, $this->config->get('config_order_status_id') );
					}
				}
			} else {
				$this->language->load('payment/authorizenet_aim');
				
				$data['error_md5'] = $data->language->get('error_md5');
			}
		} else {
				$order_id = $data['order_id'];
				$this->load->model('checkout/order');
				$order_info = $this->model_checkout_order->getOrder($order_id);
				
				if ($order_info) {
					if ($data['x_response_code'] == '1') {
						$this->model_checkout_order->confirm($order_id, $this->config->get('authorizenet_sim_order_status_id'));
					} else {
						$this->model_checkout_order->confirm($order_id, $this->config->get('config_order_status_id') );
					}
				}
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/authorizenet_sim_callback.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/authorizenet_sim_callback.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/authorizenet_sim_callback.tpl', $data);
		}
	}
}
?>