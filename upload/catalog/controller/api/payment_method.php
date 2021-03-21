<?php
namespace Opencart\Catalog\Controller\Api;
class PaymentMethod extends \Opencart\System\Engine\Controller {
	public function methods(): void {
		$this->load->language('api/payment_method');

		// Delete past shipping methods and method just in case there is an error
		unset($this->session->data['payment_methods']);
		unset($this->session->data['payment_method']);

		$json = [];

		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			// Payment Address
			if (!isset($this->session->data['payment_address'])) {
				$json['error'] = $this->language->get('error_address');
			}

			if (!$json) {
				// Totals
				$totals = [];
				$taxes = $this->cart->getTaxes();
				$total = 0;

				$this->load->model('setting/extension');

				$sort_order = [];

				$results = $this->model_setting_extension->getExtensionsByType('total');

				foreach ($results as $key => $value) {
					$sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
				}

				array_multisort($sort_order, SORT_ASC, $results);

				foreach ($results as $result) {
					if ($this->config->get('total_' . $result['code'] . '_status')) {
						$this->load->model('extension/' . $result['extension'] . '/total/' . $result['code']);

						// __call can not pass-by-reference so we get PHP to call it as an anonymous function.
						($this->{'model_extension_' . $result['extension'] . '_total_' . $result['code']}->getTotal)($totals, $taxes, $total);
					}
				}

				// Payment Methods
				$json['payment_methods'] = [];

				$this->load->model('setting/extension');

				$results = $this->model_setting_extension->getExtensionsByType('payment');

				$recurring = $this->cart->hasRecurringProducts();

				foreach ($results as $result) {
					if ($this->config->get('payment_' . $result['code'] . '_status')) {
						$this->load->model('extension/' . $result['extension'] . '/payment/' . $result['code']);

						$method = $this->{'model_extension_' . $result['extension'] . '_payment_' . $result['code']}->getMethod($this->session->data['payment_address'], $total);

						if ($method) {
							if ($recurring) {
								if (property_exists($this->{'model_extension_payment_' . $result['code']}, 'recurringPayments') && $this->{'model_extension_payment_' . $result['code']}->recurringPayments()) {
									$json['payment_methods'][$result['code']] = $method;
								}
							} else {
								$json['payment_methods'][$result['code']] = $method;
							}
						}
					}
				}

				$sort_order = [];

				foreach ($json['payment_methods'] as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}

				array_multisort($sort_order, SORT_ASC, $json['payment_methods']);

				if ($json['payment_methods']) {
					$this->session->data['payment_methods'] = $json['payment_methods'];
				} else {
					$json['error'] = $this->language->get('error_no_payment');
				}
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function method(): void {
		$this->load->language('api/payment_method');

		// Delete old payment method so not to cause any issues if there is an error
		unset($this->session->data['payment_method']);

		$json = [];

		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			// Payment Address
			if (!isset($this->session->data['payment_address'])) {
				$json['error'] = $this->language->get('error_address');
			}

			// Payment Method
			if (empty($this->session->data['payment_methods'])) {
				$json['error'] = $this->language->get('error_no_payment');
			} elseif (!isset($this->request->post['payment_method'])) {
				$json['error'] = $this->language->get('error_method');
			} elseif (!isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
				$json['error'] = $this->language->get('error_method');
			}

			if (!$json) {
				$this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];

				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
