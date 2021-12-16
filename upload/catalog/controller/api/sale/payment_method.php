<?php
namespace Opencart\Catalog\Controller\Api\Sale;
class PaymentMethod extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('api/sale/payment_method');

		$json = [];

		if (!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) {
			$json['error'] = $this->language->get('error_product');
		}

		// Payment Address
		if ($this->config->get('config_checkout_address') && !isset($this->session->data['payment_address'])) {
			$json['error'] = $this->language->get('error_payment_address');
		}

		if (!$json) {
			if (isset($this->session->data['payment_address'])) {
				$payment_address = $this->session->data['payment_address'];
			} else {
				$payment_address = [
					'address_id'     => 0,
					'firstname'      => '',
					'lastname'       => '',
					'company'        => '',
					'address_1'      => '',
					'address_2'      => '',
					'city'           => '',
					'postcode'       => '',
					'zone_id'        => 0,
					'zone'           => '',
					'zone_code'      => '',
					'country_id'     => 0,
					'country'        => '',
					'iso_code_2'     => '',
					'iso_code_3'     => '',
					'address_format' => '',
					'custom_field'   => []
				];
			}

			// Payment Methods
			$this->load->model('checkout/payment_method');

			$payment_methods = $this->model_checkout_payment_method->getMethods($payment_address);

			if ($payment_methods) {
				$this->session->data['payment_methods'] = $payment_methods;

				$json['payment_methods'] = $payment_methods;
			} else {
				$json['error'] = $this->language->get('error_no_payment');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function save(): void {
		$this->load->language('api/sale/payment_method');

		$json = [];

		// Payment Address
		if ($this->config->get('config_checkout_address') && !isset($this->session->data['payment_address'])) {
			$json['error'] = $this->language->get('error_payment_address');
		}

		// Payment Method
		if (!isset($this->request->post['payment_method']) || !isset($this->session->data['payment_methods']) || !isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
			$json['error'] = $this->language->get('error_payment_method');
		}

		if (!$json) {
			$this->session->data['payment_method'] = $this->request->post['payment_method'];

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
