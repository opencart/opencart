<?php
namespace Opencart\Catalog\Controller\Checkout;
class ShippingMethod extends \Opencart\System\Engine\Controller {
	public function index(): string {
		$this->load->language('checkout/checkout');

		if (isset($this->session->data['shipping_methods'])) {
			$data['shipping_methods'] = $this->session->data['shipping_methods'];
		} else {
			$data['shipping_methods'] = [];
		}

		if (isset($this->session->data['shipping_method']['code'])) {
			$data['code'] = $this->session->data['shipping_method']['code'];
		} else {
			$data['code'] = '';
		}

		return $this->load->view('checkout/shipping_method', $data);
	}

	public function getMethods(): void {
		$this->load->language('checkout/checkout');

		$json = [];

		// Validate if shipping is required. If not the customer should not have reached this page.
		if (!$this->cart->hasShipping()) {
			//$json['redirect'] = $this->url->link('checkout/checkout', 'language=' . $this->config->get('config_language'), true);
		}

		if (!isset($this->session->data['shipping_address'])) {
			//$json['redirect'] = $this->url->link('checkout/checkout', 'language=' . $this->config->get('config_language'), true);
		}

		$method_data = [];

		if (!$json) {
			// Shipping Methods
			$this->load->model('setting/extension');

			$results = $this->model_setting_extension->getExtensionsByType('shipping');

			foreach ($results as $result) {
				if ($this->config->get('shipping_' . $result['code'] . '_status')) {
					$this->load->model('extension/' . $result['extension'] . '/shipping/' . $result['code']);

					$quote = $this->{'model_extension_' . $result['extension'] . '_shipping_' . $result['code']}->getQuote($this->session->data['shipping_address']);

					if ($quote) {
						$method_data[$result['code']] = [
							'title'      => $quote['title'],
							'quote'      => $quote['quote'],
							'sort_order' => $quote['sort_order'],
							'error'      => $quote['error']
						];
					}
				}
			}

			$sort_order = [];

			foreach ($method_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $method_data);

			if (!$method_data) {
				$json['warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact', 'language=' . $this->config->get('config_language')));
			}
		}

		if (!$json) {
			$json['shipping_methods'] = $method_data;

			$this->session->data['shipping_methods'] = $method_data;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function save(): void {
		$this->load->language('checkout/checkout');

		$json = [];

		// Validate if shipping is required. If not the customer should not have reached this page.
		if (!$this->cart->hasShipping()) {
			$json['redirect'] = $this->url->link('checkout/checkout', 'language=' . $this->config->get('config_language'), true);
		}

		// Validate if shipping address has been set.
		if (!isset($this->session->data['shipping_address'])) {
			$json['redirect'] = $this->url->link('checkout/checkout', 'language=' . $this->config->get('config_language'), true);
		}

		if (!isset($this->request->post['shipping_method'])) {
			$json['error'] = $this->language->get('error_shipping');
		} else {
			$shipping = explode('.', $this->request->post['shipping_method']);

			if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
				$json['error'] = $this->language->get('error_shipping');
			}
		}

		if (!$json) {
			$json['success'] = $this->language->get('text_success');

			$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
