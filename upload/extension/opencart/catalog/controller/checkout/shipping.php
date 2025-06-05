<?php
namespace Opencart\Catalog\Controller\Extension\Opencart\Checkout;
/**
 * Class Shipping
 *
 * @package Opencart\Catalog\Controller\Extension\Opencart\Checkout
 */
class Shipping extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return string
	 */
	public function index(): string {
		if ($this->config->get('total_shipping_status') && $this->config->get('total_shipping_estimator') && $this->cart->hasShipping()) {
			$this->load->language('extension/opencart/checkout/shipping');

			if (isset($this->session->data['shipping_address'])) {
				$data['postcode'] = $this->session->data['shipping_address']['postcode'];
				$data['country_id'] = $this->session->data['shipping_address']['country_id'];
				$data['zone_id'] = $this->session->data['shipping_address']['zone_id'];
			} else {
				$data['postcode'] = '';
				$data['country_id'] = (int)$this->config->get('config_country_id');
				$data['zone_id'] = '';
			}

			if (isset($this->session->data['shipping_method'])) {
				$data['code'] = $this->session->data['shipping_method']['code'];
			} else {
				$data['code'] = '';
			}

			$data['language'] = $this->config->get('config_language');
			$data['currency'] = $this->session->data['currency'];

			return $this->load->view('extension/opencart/checkout/shipping', $data);
		}

		return '';
	}

	/**
	 * Quote
	 *
	 * @return void
	 */
	public function quote(): void {
		$this->load->language('extension/opencart/checkout/shipping');

		$json = [];

		$required = [
			'postcode',
			'country_id',
			'zone_id'
		];

		$post_info = $this->request->post + $required;

		if (!$this->cart->hasProducts()) {
			$json['error']['warning'] = $this->language->get('error_product');
		}

		if (!$this->cart->hasShipping()) {
			$json['error']['warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact', 'language=' . $this->config->get('config_language')));
		}

		// Country
		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry((int)$post_info['country_id']);

		if ($country_info && $country_info['postcode_required'] && !oc_validate_length($post_info['postcode'], 2, 10)) {
			$json['error']['postcode'] = $this->language->get('error_postcode');
		}

		if (!$country_info) {
			$json['error']['country'] = $this->language->get('error_country');
		}

		// Zones
		$this->load->model('localisation/zone');

		// Total Zones
		$zone_total = $this->model_localisation_zone->getTotalZonesByCountryId((int)$post_info['country_id']);

		if ($zone_total && !$post_info['zone_id']) {
			$json['error']['address_zone'] = $this->language->get('error_zone');
		}

		if (!$json) {
			if ($country_info) {
				$country = $country_info['name'];
				$iso_code_2 = $country_info['iso_code_2'];
				$iso_code_3 = $country_info['iso_code_3'];
				$address_format_id = $country_info['address_format_id'];
			} else {
				$country = '';
				$iso_code_2 = '';
				$iso_code_3 = '';
				$address_format_id = 0;
			}

			// Zone
			$this->load->model('localisation/zone');

			$zone_info = $this->model_localisation_zone->getZone($post_info['zone_id']);

			if ($zone_info) {
				$zone = $zone_info['name'];
				$zone_code = $zone_info['code'];
			} else {
				$zone = '';
				$zone_code = '';
			}

			$this->session->data['shipping_address'] = [
				'postcode'   => $post_info['postcode'],
				'zone_id'    => $post_info['zone_id'],
				'zone'       => $zone,
				'zone_code'  => $zone_code,
				'country_id' => $post_info['country_id'],
				'country'    => $country,
				'iso_code_2' => $iso_code_2,
				'iso_code_3' => $iso_code_3
			];

			$this->tax->setShippingAddress($post_info['country_id'], $post_info['zone_id']);

			// Shipping Methods
			$this->load->model('checkout/shipping_method');

			$shipping_methods = $this->model_checkout_shipping_method->getMethods($this->session->data['shipping_address']);

			if ($shipping_methods) {
				$json['shipping_methods'] = $this->session->data['shipping_methods'] = $shipping_methods;
			} else {
				$json['error']['warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact', 'language=' . $this->config->get('config_language')));
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('extension/opencart/checkout/shipping');

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
			$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];

			$json['success'] = $this->language->get('text_success');

			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
