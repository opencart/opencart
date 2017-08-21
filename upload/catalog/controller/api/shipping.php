<?php
class ControllerApiShipping extends Controller {
	public function address() {
		$this->load->language('api/shipping');

		// Delete old shipping address, shipping methods and method so not to cause any issues if there is an error
		unset($this->session->data['shipping_address']);
		unset($this->session->data['shipping_methods']);
		unset($this->session->data['shipping_method']);

		$json = array();

		if ($this->cart->hasShipping()) {
			if (!isset($this->session->data['api_id'])) {
				$json['error']['warning'] = $this->language->get('error_permission');
			} else {
				// Add keys for missing post vars
				$keys = array(
					'firstname',
					'lastname',
					'company',
					'address_1',
					'address_2',
					'postcode',
					'city',
					'zone_id',
					'country_id'
				);

				foreach ($keys as $key) {
					if (!isset($this->request->post[$key])) {
						$this->request->post[$key] = '';
					}
				}

				if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
					$json['error']['firstname'] = $this->language->get('error_firstname');
				}

				if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
					$json['error']['lastname'] = $this->language->get('error_lastname');
				}

				if ((utf8_strlen(trim($this->request->post['address_1'])) < 3) || (utf8_strlen(trim($this->request->post['address_1'])) > 128)) {
					$json['error']['address_1'] = $this->language->get('error_address_1');
				}

				if ((utf8_strlen($this->request->post['city']) < 2) || (utf8_strlen($this->request->post['city']) > 32)) {
					$json['error']['city'] = $this->language->get('error_city');
				}

				$this->load->model('localisation/country');

				$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

				if ($country_info && $country_info['postcode_required'] && (utf8_strlen(trim($this->request->post['postcode'])) < 2 || utf8_strlen(trim($this->request->post['postcode'])) > 10)) {
					$json['error']['postcode'] = $this->language->get('error_postcode');
				}

				if ($this->request->post['country_id'] == '') {
					$json['error']['country'] = $this->language->get('error_country');
				}

				if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '') {
					$json['error']['zone'] = $this->language->get('error_zone');
				}

				// Custom field validation
				$this->load->model('account/custom_field');

				$custom_fields = $this->model_account_custom_field->getCustomFields($this->config->get('config_customer_group_id'));

				foreach ($custom_fields as $custom_field) {
					if ($custom_field['location'] == 'address') {
						if ($custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['location']][$custom_field['custom_field_id']])) {
							$json['error']['custom_field' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
						} elseif (($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !filter_var($this->request->post['custom_field'][$custom_field['location']][$custom_field['custom_field_id']], FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $custom_field['validation'])))) {
							$json['error']['custom_field' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
						}
					}
				}

				if (!$json) {
					$this->load->model('localisation/country');

					$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

					if ($country_info) {
						$country = $country_info['name'];
						$iso_code_2 = $country_info['iso_code_2'];
						$iso_code_3 = $country_info['iso_code_3'];
						$address_format = $country_info['address_format'];
					} else {
						$country = '';
						$iso_code_2 = '';
						$iso_code_3 = '';
						$address_format = '';
					}

					$this->load->model('localisation/zone');

					$zone_info = $this->model_localisation_zone->getZone($this->request->post['zone_id']);

					if ($zone_info) {
						$zone = $zone_info['name'];
						$zone_code = $zone_info['code'];
					} else {
						$zone = '';
						$zone_code = '';
					}

					$this->session->data['shipping_address'] = array(
						'firstname'      => $this->request->post['firstname'],
						'lastname'       => $this->request->post['lastname'],
						'company'        => $this->request->post['company'],
						'address_1'      => $this->request->post['address_1'],
						'address_2'      => $this->request->post['address_2'],
						'postcode'       => $this->request->post['postcode'],
						'city'           => $this->request->post['city'],
						'zone_id'        => $this->request->post['zone_id'],
						'zone'           => $zone,
						'zone_code'      => $zone_code,
						'country_id'     => $this->request->post['country_id'],
						'country'        => $country,
						'iso_code_2'     => $iso_code_2,
						'iso_code_3'     => $iso_code_3,
						'address_format' => $address_format,
						'custom_field'   => isset($this->request->post['custom_field']) ? $this->request->post['custom_field'] : array()
					);

					$json['success'] = $this->language->get('text_address');

					unset($this->session->data['shipping_method']);
					unset($this->session->data['shipping_methods']);
				}
			}
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function methods() {
		$this->load->language('api/shipping');

		// Delete past shipping methods and method just in case there is an error
		unset($this->session->data['shipping_methods']);
		unset($this->session->data['shipping_method']);

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} elseif ($this->cart->hasShipping()) {
			if (!isset($this->session->data['shipping_address'])) {
				$json['error'] = $this->language->get('error_address');
			}

			if (!$json) {
				// Shipping Methods
				$json['shipping_methods'] = array();

				$this->load->model('setting/extension');

				$results = $this->model_setting_extension->getExtensions('shipping');

				foreach ($results as $result) {
					if ($this->config->get('shipping_' . $result['code'] . '_status')) {
						$this->load->model('extension/shipping/' . $result['code']);

						$quote = $this->{'model_extension_shipping_' . $result['code']}->getQuote($this->session->data['shipping_address']);

						if ($quote) {
							$json['shipping_methods'][$result['code']] = array(
								'title'      => $quote['title'],
								'quote'      => $quote['quote'],
								'sort_order' => $quote['sort_order'],
								'error'      => $quote['error']
							);
						}
					}
				}

				$sort_order = array();

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
			$json['shipping_methods'] = array();
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function method() {
		$this->load->language('api/shipping');

		// Delete old shipping method so not to cause any issues if there is an error
		unset($this->session->data['shipping_method']);

		$json = array();

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

					$json['success'] = $this->language->get('text_method');
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
