<?php
namespace Opencart\catalog\controller\api;
/**
 * Class Shipping Address
 *
 * Can be loaded using $this->load->controller('api/shipping_address');
 *
 * @package Opencart\Catalog\Controller\Api
 */
class ShippingAddress extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return array<string, mixed>
	 */
	public function index(): array {
		$this->load->language('api/shipping_address');

		$output = [];

		if ($this->cart->hasShipping()) {
			// Add keys for missing post vars
			$required = [
				'shipping_firstname'  => '',
				'shipping_lastname'   => '',
				'shipping_company'    => '',
				'shipping_address_1'  => '',
				'shipping_address_2'  => '',
				'shipping_postcode'   => '',
				'shipping_city'       => '',
				'shipping_zone_id'    => 0,
				'shipping_country_id' => 0
			];

			$post_info = $this->request->post + $required;

			if (!oc_validate_length($post_info['shipping_firstname'], 1, 32)) {
				$output['error']['shipping_firstname'] = $this->language->get('error_firstname');
			}

			if (!oc_validate_length($post_info['shipping_lastname'], 1, 32)) {
				$output['error']['shipping_lastname'] = $this->language->get('error_lastname');
			}

			if (!oc_validate_length($post_info['shipping_address_1'], 3, 128)) {
				$output['error']['shipping_address_1'] = $this->language->get('error_address_1');
			}

			if (!oc_validate_length($post_info['shipping_city'], 2, 128)) {
				$output['error']['shipping_city'] = $this->language->get('error_city');
			}

			// Country
			$this->load->model('localisation/country');

			$country_info = $this->model_localisation_country->getCountry((int)$post_info['shipping_country_id']);

			if ($country_info && $country_info['postcode_required'] && !oc_validate_length($post_info['shipping_postcode'], 2, 10)) {
				$output['error']['shipping_postcode'] = $this->language->get('error_postcode');
			}

			if (!$country_info) {
				$output['error']['shipping_country'] = $this->language->get('error_country');
			}

			// Zones
			$this->load->model('localisation/zone');

			// Total Zones
			$zone_total = $this->model_localisation_zone->getTotalZonesByCountryId((int)$post_info['shipping_country_id']);

			if ($zone_total && !$post_info['shipping_zone_id']) {
				$output['error']['shipping_zone'] = $this->language->get('error_zone');
			}

			// Custom fields validation
			$this->load->model('account/custom_field');

			$custom_fields = $this->model_account_custom_field->getCustomFields((int)$this->config->get('config_customer_group_id'));

			foreach ($custom_fields as $custom_field) {
				if ($custom_field['location'] == 'address') {
					if ($custom_field['required'] && empty($post_info['shipping_custom_field'][$custom_field['custom_field_id']])) {
						$output['error']['shipping_custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
					} elseif (($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !oc_validate_regex($post_info['shipping_custom_field'][$custom_field['custom_field_id']], $custom_field['validation'])) {
						$output['error']['shipping_custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_regex'), $custom_field['name']);
					}
				}
			}
		} else {
			$output['error']['warning'] = $this->language->get('error_shipping');
		}

		if (!$output) {
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

			// Address Format
			$this->load->model('localisation/address_format');

			$address_format_info = $this->model_localisation_address_format->getAddressFormat($address_format_id);

			if ($address_format_info) {
				$address_format = $address_format_info['address_format'];
			} else {
				$address_format = '';
			}

			// Zone
			$this->load->model('localisation/zone');

			$zone_info = $this->model_localisation_zone->getZone($post_info['shipping_zone_id']);

			if ($zone_info) {
				$zone = $zone_info['name'];
				$zone_code = $zone_info['code'];
			} else {
				$zone = '';
				$zone_code = '';
			}

			$this->session->data['shipping_address'] = [
				'address_id'     => $post_info['shipping_address_id'],
				'firstname'      => $post_info['shipping_firstname'],
				'lastname'       => $post_info['shipping_lastname'],
				'company'        => $post_info['shipping_company'],
				'address_1'      => $post_info['shipping_address_1'],
				'address_2'      => $post_info['shipping_address_2'],
				'postcode'       => $post_info['shipping_postcode'],
				'city'           => $post_info['shipping_city'],
				'zone_id'        => $post_info['shipping_zone_id'],
				'zone'           => $zone,
				'zone_code'      => $zone_code,
				'country_id'     => (int)$post_info['shipping_country_id'],
				'country'        => $country,
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format,
				'custom_field'   => $post_info['shipping_custom_field'] ?? []
			];

			$output['success'] = $this->language->get('text_success');
		}

		return $output;
	}
}
