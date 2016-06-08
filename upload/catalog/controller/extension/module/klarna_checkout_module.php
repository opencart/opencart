<?php
class ControllerExtensionModuleKlarnaCheckoutModule extends Controller {
	public function index() {
		$this->load->model('extension/payment/klarna_checkout');

		// If Payment Method or Module is disabled
		if (!$this->config->get('klarna_checkout_module_status') || !$this->config->get('klarna_checkout_status')) {
			$this->model_extension_payment_klarna_checkout->log('Not shown due to Payment Method or Module being disabled');
			return false;
		}

		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$this->model_extension_payment_klarna_checkout->log('Not shown due to empty cart');
			return false;
		}

		// Validate minimum quantity requirements.
		$products = $this->cart->getProducts();

		foreach ($products as $product) {
			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}

			if ($product['minimum'] > $product_total) {
				$this->model_extension_payment_klarna_checkout->log('Not shown due to cart not meeting minimum quantity reqs.');
				return false;
			}
		}

		// Validate cart has recurring products
		if ($this->cart->hasRecurringProducts()) {
			$this->model_extension_payment_klarna_checkout->log('Not shown due to cart having recurring products.');
			return false;
		}

		$this->setShipping();

		list($klarna_account, $connector) = $this->model_extension_payment_klarna_checkout->getConnector($this->config->get('klarna_checkout_account'), $this->session->data['shipping_address']['country_id'], $this->session->data['currency']);

		if (!$klarna_account || !$connector) {
			$this->model_extension_payment_klarna_checkout->log('Couldn\'t secure connection to Klarna API.');
			return false;
		}

		$data['klarna_checkout'] = $this->url->link('extension/payment/klarna_checkout', '', true);

		return $this->load->view('extension/module/klarna_checkout_module', $data);
	}

	private function setShipping() {
		$this->load->model('account/address');
		$this->load->model('localisation/country');
		$this->load->model('localisation/zone');

		if (isset($this->session->data['shipping_address']) && !empty($this->session->data['shipping_address'])) {
			$this->session->data['shipping_address'] = $this->session->data['shipping_address'];
		} elseif ($this->customer->isLogged() && $this->customer->getAddressId()) {
			$this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
		} else {
			$country_info = $this->model_localisation_country->getCountry($this->config->get('config_country_id'));

			$zone_info = $this->model_localisation_zone->getZone($this->config->get('config_zone_id'));

			$this->session->data['shipping_address'] = array(
				'address_id'	 => null,
				'firstname'		 => null,
				'lastname'		 => null,
				'company'		 => null,
				'address_1'		 => null,
				'address_2'		 => null,
				'postcode'		 => null,
				'city'			 => null,
				'zone_id'		 => $zone_info['zone_id'],
				'zone'			 => $zone_info['name'],
				'zone_code'		 => $zone_info['code'],
				'country_id'	 => $country_info['country_id'],
				'country'		 => $country_info['name'],
				'iso_code_2'	 => $country_info['iso_code_2'],
				'iso_code_3'	 => $country_info['iso_code_3'],
				'address_format' => '',
				'custom_field'	 => null,
			);
		}

		if (isset($this->session->data['shipping_address'])) {
			// Shipping Methods
			$method_data = array();

			$this->load->model('extension/extension');

			$results = $this->model_extension_extension->getExtensions('shipping');

			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('extension/shipping/' . $result['code']);

					$quote = $this->{'model_extension_shipping_' . $result['code']}->getQuote($this->session->data['shipping_address']);

					if ($quote) {
						$method_data[$result['code']] = array(
							'title'      => $quote['title'],
							'quote'      => $quote['quote'],
							'sort_order' => $quote['sort_order'],
							'error'      => $quote['error']
						);
					}
				}
			}

			$sort_order = array();

			foreach ($method_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $method_data);

			$this->session->data['shipping_methods'] = $method_data;
		}
	}
}
