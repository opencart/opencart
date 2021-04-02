<?php
namespace Opencart\Catalog\Controller\Api;
class ShippingMethod extends \Opencart\System\Engine\Controller {
	public function methods(): void {
		$this->load->language('api/shipping');

		// Delete past shipping methods and method just in case there is an error
		unset($this->session->data['shipping_methods']);
		unset($this->session->data['shipping_method']);

		$json = [];

		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} elseif ($this->cart->hasShipping()) {
			if (!isset($this->session->data['shipping_address'])) {
				$json['error'] = $this->language->get('error_address');
			}

			if (!$json) {
				// Shipping Methods
				$json['shipping_methods'] = [];

				$this->load->model('setting/extension');

				$results = $this->model_setting_extension->getExtensionsByType('shipping');

				foreach ($results as $result) {
					if ($this->config->get('shipping_' . $result['code'] . '_status')) {
						$this->load->model('extension/' . $result['extension'] . '/shipping/' . $result['code']);

						$quote = $this->{'model_extension_' . $result['extension'] . '_shipping_' . $result['code']}->getQuote($this->session->data['shipping_address']);

						if ($quote) {
							$json['shipping_methods'][$result['code']] = [
								'title'      => $quote['title'],
								'quote'      => $quote['quote'],
								'sort_order' => $quote['sort_order'],
								'error'      => $quote['error']
							];
						}
					}
				}

				$sort_order = [];

				foreach ($json['shipping_methods'] as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}

				array_multisort($sort_order, SORT_ASC, $json['shipping_methods']);

				if ($json['shipping_methods']) {
					$this->session->data['shipping_methods'] = $json['shipping_methods'];
				} else {
					$json['error'] = $this->language->get('error_no_shipping');
				}
			}
		} else {
			$json['shipping_methods'] = [];
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function method(): void {
		$this->load->language('api/shipping_metho');

		// Delete old shipping method so not to cause any issues if there is an error
		unset($this->session->data['shipping_method']);

		$json = [];

		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			if ($this->cart->hasShipping()) {
				// Shipping Address
				if (!isset($this->session->data['shipping_address'])) {
					$json['error'] = $this->language->get('error_address');
				}

				// Shipping Method
				if (empty($this->session->data['shipping_methods'])) {
					$json['error'] = $this->language->get('error_no_shipping');
				} elseif (!isset($this->request->post['shipping_method'])) {
					$json['error'] = $this->language->get('error_method');
				} else {
					$shipping = explode('.', $this->request->post['shipping_method']);

					if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
						$json['error'] = $this->language->get('error_method');
					}
				}

				if (!$json) {
					$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];

					$json['success'] = $this->language->get('text_success');
				}
			} else {
				unset($this->session->data['shipping_address']);
				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}