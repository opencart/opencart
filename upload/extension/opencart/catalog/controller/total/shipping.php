<?php
namespace Opencart\Catalog\Controller\Extension\Opencart\Total;
use \Opencart\System\Helper AS Helper;
class Shipping extends \Opencart\System\Engine\Controller {
	public function index(): string {
		if ($this->config->get('total_shipping_status') && $this->config->get('total_shipping_estimator') && $this->cart->hasShipping()) {
			$this->load->language('extension/opencart/total/shipping');

            $data['language'] = $this->config->get('config_language');

			if (isset($this->session->data['shipping_address'])) {
				$shipping_address = $this->session->data['shipping_address'];

				$data['postcode'] = $shipping_address['postcode'];
				$data['country_id'] = $shipping_address['country_id'];
				$data['zone_id'] = $shipping_address['zone_id'];
			} else {
				$data['postcode'] = '';
				$data['country_id'] = (int)$this->config->get('config_country_id');
				$data['zone_id'] = '';
			}

			$data['shipping_method'] = '';

			if (isset($this->session->data['shipping_method'])) {
				$shipping = explode('.', $this->session->data['shipping_method']);

				if (isset($shipping[0]) && isset($shipping[1]) && isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
					$data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]]['code'];
				}
			}

			$this->load->model('localisation/country');

			$data['countries'] = $this->model_localisation_country->getCountries();

			return $this->load->view('extension/opencart/total/shipping', $data);
		}

		return '';
	}

	public function quote(): void {
		$this->load->language('extension/opencart/total/shipping');

		$json = [];

		$keys = [
			'postcode',
			'country_id',
			'zone_id'
		];

		foreach ($keys as $key) {
			if (!isset($this->request->post[$key])) {
				$this->request->post[$key] = '';
			}
		}

		if (!$this->cart->hasProducts()) {
			$json['error']['warning'] = $this->language->get('error_product');
		}

		if (!$this->cart->hasShipping()) {
			$json['error']['warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact', 'language=' . $this->config->get('config_language')));
		}

		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry((int)$this->request->post['country_id']);

		if ($country_info && $country_info['postcode_required'] && (Helper\Utf8\strlen($this->request->post['postcode']) < 2 || Helper\Utf8\strlen($this->request->post['postcode']) > 10)) {
			$json['error']['postcode'] = $this->language->get('error_postcode');
		}

		if ($this->request->post['country_id'] == '') {
			$json['error']['country'] = $this->language->get('error_country');
		}

		if ($this->request->post['zone_id'] == '') {
			$json['error']['zone'] = $this->language->get('error_zone');
		}

		if (!$json) {
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

			$this->session->data['shipping_address'] = [
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
				'address_format' => $address_format,
				'custom_field'   => []
			];

			$this->tax->setShippingAddress($this->request->post['country_id'], $this->request->post['zone_id']);

			// Shipping Methods
			$this->load->model('checkout/shipping_method');

			$shipping_methods = $this->model_checkout_shipping_method->getMethods($this->session->data['shipping_address']);

			$this->session->data['shipping_methods'] = $shipping_methods;

			if ($shipping_methods) {
				$json['shipping_method'] = $shipping_methods;
			} else {
				$json['error']['warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact', 'language=' . $this->config->get('config_language')));
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function save(): void {
		$this->load->language('extension/opencart/total/shipping');

		$json = [];

		if (!empty($this->request->post['shipping_method'])) {
			$shipping = explode('.', $this->request->post['shipping_method']);

			if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
				$json['error'] = $this->language->get('error_shipping');
			}
		} else {
			$json['error'] = $this->language->get('error_shipping');
		}

		if (!$json) {
			$this->session->data['shipping_method'] = $this->request->post['shipping_method'];

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
