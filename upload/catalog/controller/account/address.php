<?php
namespace Opencart\Catalog\Controller\Account;
/**
 * Class Address
 *
 * @package Opencart\Catalog\Controller\Account
 */
class Address extends \Opencart\System\Engine\Controller {
	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('account/address');

		$json = [];

		$required = [
			'firstname'  => '',
			'lastname'   => '',
			'address_1'  => '',
			'address_2'  => '',
			'city'       => '',
			'postcode'   => '',
			'country_id' => 0,
			'zone_id'    => 0
		];

		$post_info = $this->request->post + $required;

		if (!$this->load->controller('account/login.validate')) {
			$this->session->data['redirect'] = $this->url->link('account/address', 'language=' . $this->config->get('config_language'));

			$json['redirect'] = $this->url->link('account/login', 'language=' . $this->config->get('config_language'), true);
		}

		if (!$json) {
			if (!oc_validate_length((string)$post_info['firstname'], 1, 32)) {
				$json['error']['firstname'] = $this->language->get('error_firstname');
			}

			if (!oc_validate_length((string)$post_info['lastname'], 1, 32)) {
				$json['error']['lastname'] = $this->language->get('error_lastname');
			}

			if (!oc_validate_length((string)$post_info['address_1'], 3, 128)) {
				$json['error']['address_1'] = $this->language->get('error_address_1');
			}

			if (!oc_validate_length((string)$post_info['city'], 2, 128)) {
				$json['error']['city'] = $this->language->get('error_city');
			}

			// Country
			$this->load->model('localisation/country');

			$country_info = $this->model_localisation_country->getCountry((int)$post_info['country_id']);

			if ($country_info && $country_info['postcode_required'] && !oc_validate_length((string)$post_info['postcode'], 2, 10)) {
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
				$json['error']['zone'] = $this->language->get('error_zone');
			}

			if (isset($this->request->get['address_id']) && ($this->customer->getAddressId() == (int)$this->request->get['address_id']) && !(bool)$post_info['default']) {
				$json['error']['warning'] = $this->language->get('error_default');
			}
		}

		if (!$json) {
			// Address
			$this->load->model('account/address');

			// Add Address
			if (!isset($this->request->get['address_id'])) {
				$this->model_account_address->addAddress($this->customer->getId(), $post_info);

				$this->session->data['success'] = $this->language->get('text_add');

				$json['redirect'] = $this->url->link('account/address', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'], true);
			}

			// Edit Address
			if (isset($this->request->get['address_id'])) {
				$this->model_account_address->editAddress($this->customer->getId(), (int)$this->request->get['address_id'], $post_info);

				// If address is in session update it.
				if (isset($this->session->data['shipping_address']['address_id']) && ($this->session->data['shipping_address']['address_id'] == (int)$this->request->get['address_id'])) {
					$this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getId(), (int)$this->request->get['address_id']);

					unset($this->session->data['order_id']);
					unset($this->session->data['shipping_method']);
					unset($this->session->data['shipping_methods']);
					unset($this->session->data['payment_method']);
					unset($this->session->data['payment_methods']);
				}

				// If address is in session update it.
				if (isset($this->session->data['payment_address']['address_id']) && ($this->session->data['payment_address']['address_id'] == (int)$this->request->get['address_id'])) {
					$this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getId(), (int)$this->request->get['address_id']);

					unset($this->session->data['order_id']);
					unset($this->session->data['shipping_method']);
					unset($this->session->data['shipping_methods']);
					unset($this->session->data['payment_method']);
					unset($this->session->data['payment_methods']);
				}

				$json['success'] = $this->language->get('text_edit');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Delete
	 *
	 * @return void
	 */
	public function delete(): void {
		$this->load->language('account/address');

		$json = [];

		if (isset($this->request->get['address_id'])) {
			$address_id = (int)$this->request->get['address_id'];
		} else {
			$address_id = 0;
		}

		if (!$this->load->controller('account/login.validate')) {
			$this->session->data['redirect'] = $this->url->link('account/address', 'language=' . $this->config->get('config_language'));

			$json['redirect'] = $this->url->link('account/login', 'language=' . $this->config->get('config_language'), true);
		}

		if (!$json) {
			if ($this->customer->getAddressId() == $address_id) {
				$json['error'] = $this->language->get('error_default');
			}

			// Total Addresses
			$this->load->model('account/address');

			if ($this->model_account_address->getTotalAddresses($this->customer->getId()) == 1) {
				$json['error'] = $this->language->get('error_delete');
			}

			// Subscriptions
			$this->load->model('account/subscription');

			// Total Subscriptions
			$subscription_total = $this->model_account_subscription->getTotalSubscriptionByShippingAddressId($address_id);

			if ($subscription_total) {
				$json['error'] = sprintf($this->language->get('error_subscription'), $subscription_total);
			}

			$subscription_total = $this->model_account_subscription->getTotalSubscriptionByPaymentAddressId($address_id);

			if ($subscription_total) {
				$json['error'] = sprintf($this->language->get('error_subscription'), $subscription_total);
			}
		}

		if (!$json) {
			// Delete address from database.
			$this->model_account_address->deleteAddresses($this->customer->getId(), $address_id);

			// Delete address from session.
			if (isset($this->session->data['shipping_address']['address_id']) && ($this->session->data['shipping_address']['address_id'] == $address_id)) {
				unset($this->session->data['order_id']);
				unset($this->session->data['shipping_address']);
				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);
			}

			// Delete address from session.
			if (isset($this->session->data['payment_address']['address_id']) && ($this->session->data['payment_address']['address_id'] == $address_id)) {
				unset($this->session->data['order_id']);
				unset($this->session->data['payment_address']);
				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);
			}

			$json['success'] = $this->language->get('text_delete');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
