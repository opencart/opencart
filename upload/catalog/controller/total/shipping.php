<?php
class ControllerTotalShipping extends Controller {
	public function index() {
		if ($this->config->get('shipping_status') && $this->config->get('shipping_estimator') && $this->cart->hasShipping()) {
			$this->load->language('total/shipping');

			$data['heading_title'] = $this->language->get('heading_title');

			$data['text_shipping'] = $this->language->get('text_shipping');
			$data['text_shipping_method'] = $this->language->get('text_shipping_method');
			$data['text_select'] = $this->language->get('text_select');
			$data['text_none'] = $this->language->get('text_none');
			$data['text_loading'] = $this->language->get('text_loading');

			$data['entry_country'] = $this->language->get('entry_country');
			$data['entry_zone'] = $this->language->get('entry_zone');
			$data['entry_postcode'] = $this->language->get('entry_postcode');

			$data['button_quote'] = $this->language->get('button_quote');
			$data['button_shipping'] = $this->language->get('button_shipping');
			$data['button_cancel'] = $this->language->get('button_cancel');

			if (isset($this->session->data['shipping_address']['country_id'])) {
				$data['country_id'] = $this->session->data['shipping_address']['country_id'];
			} else {
				$data['country_id'] = $this->config->get('config_country_id');
			}

			$this->load->model('localisation/country');

			$data['countries'] = $this->model_localisation_country->getCountries();

			if (isset($this->session->data['shipping_address']['zone_id'])) {
				$data['zone_id'] = $this->session->data['shipping_address']['zone_id'];
			} else {
				$data['zone_id'] = '';
			}

			if (isset($this->session->data['shipping_address']['postcode'])) {
				$data['postcode'] = $this->session->data['shipping_address']['postcode'];
			} else {
				$data['postcode'] = '';
			}

			if (isset($this->session->data['shipping_method'])) {
				$data['shipping_method'] = $this->session->data['shipping_method']['code'];
			} else {
				$data['shipping_method'] = '';
			}

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/total/shipping.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/total/shipping.tpl', $data);
			} else {
				return $this->load->view('default/template/total/shipping.tpl', $data);
			}
		}
	}

	public function quote() {
		$this->load->language('total/shipping');

		$json = array();

		if (!$this->cart->hasProducts()) {
			$json['error']['warning'] = $this->language->get('error_product');
		}

		if (!$this->cart->hasShipping()) {
			$json['error']['warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
		}

		if ($this->request->post['country_id'] == '') {
			$json['error']['country'] = $this->language->get('error_country');
		}

		if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '') {
			$json['error']['zone'] = $this->language->get('error_zone');
		}

		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

		if ($country_info && $country_info['postcode_required'] && (utf8_strlen(trim($this->request->post['postcode'])) < 2 || utf8_strlen(trim($this->request->post['postcode'])) > 10)) {
			$json['error']['postcode'] = $this->language->get('error_postcode');
		}

		if (!$json) {
			$this->tax->setShippingAddress($this->request->post['country_id'], $this->request->post['zone_id']);

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
				'firstname'      => '',
				'lastname'       => '',
				'company'        => '',
				'address_1'      => '',
				'address_2'      => '',
				'postcode'       => $this->request->post['postcode'],
				'city'           => '',
				'zone_id'        => $this->request->post['zone_id'],
				'zone'           => $zone,
				'zone_code'      => $zone_code,
				'country_id'     => $this->request->post['country_id'],
				'country'        => $country,
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format
			);

			$quote_data = array();

			$this->load->model('extension/extension');

			$results = $this->model_extension_extension->getExtensions('shipping');

			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('shipping/' . $result['code']);

					$quote = $this->{'model_shipping_' . $result['code']}->getQuote($this->session->data['shipping_address']);

					if ($quote) {
						$quote_data[$result['code']] = array(
							'title'      => $quote['title'],
							'quote'      => $quote['quote'],
							'sort_order' => $quote['sort_order'],
							'error'      => $quote['error']
						);
					}
				}
			}

			$sort_order = array();

			foreach ($quote_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $quote_data);

			$this->session->data['shipping_methods'] = $quote_data;

			if ($this->session->data['shipping_methods']) {
				$json['shipping_method'] = $this->session->data['shipping_methods'];
			} else {
				$json['error']['warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function shipping() {
		$this->load->language('total/shipping');

		$json = array();

		if (!empty($this->request->post['shipping_method'])) {
			$shipping = explode('.', $this->request->post['shipping_method']);

			if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
				$json['warning'] = $this->language->get('error_shipping');
			}
		} else {
			$json['warning'] = $this->language->get('error_shipping');
		}

		if (!$json) {
			$shipping = explode('.', $this->request->post['shipping_method']);

			$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];

			$this->session->data['success'] = $this->language->get('text_success');

			$json['redirect'] = $this->url->link('checkout/cart');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function country() {
		$json = array();

		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);

		if ($country_info) {
			$this->load->model('localisation/zone');

			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']
			);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}