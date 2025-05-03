<?php
namespace Opencart\Catalog\Controller\Account;
/**
 * Class Address
 *
 * @package Opencart\Catalog\Controller\Account
 */
class Address extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('account/address');

		if (!$this->load->controller('account/login.validate')) {
			$this->session->data['redirect'] = $this->url->link('account/address', 'language=' . $this->config->get('config_language'));

			$this->response->redirect($this->url->link('account/login', 'language=' . $this->config->get('config_language'), true));
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/address', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'])
		];

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['add'] = $this->url->link('account/address.form', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token']);
		$data['back'] = $this->url->link('account/account', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token']);

		$data['list'] = $this->getList();

		$data['language'] = $this->config->get('config_language');

		$data['customer_token'] = $this->session->data['customer_token'];

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/address', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('account/address');

		if (!$this->load->controller('account/login.validate')) {
			$this->session->data['redirect'] = $this->url->link('account/address', 'language=' . $this->config->get('config_language'));

			$this->response->redirect($this->url->link('account/login', 'language=' . $this->config->get('config_language'), true));
		}

		$this->response->setOutput($this->getList());
	}

	/**
	 * Get List
	 *
	 * @return string
	 */
	protected function getList(): string {
		// Addresses
		$data['addresses'] = [];

		$this->load->model('account/address');

		$results = $this->model_account_address->getAddresses($this->customer->getId());

		foreach ($results as $result) {
			$find = [
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}'
			];

			$replace = [
				'firstname' => $result['firstname'],
				'lastname'  => $result['lastname'],
				'company'   => $result['company'],
				'address_1' => $result['address_1'],
				'address_2' => $result['address_2'],
				'city'      => $result['city'],
				'postcode'  => $result['postcode'],
				'zone'      => $result['zone'],
				'zone_code' => $result['zone_code'],
				'country'   => $result['country']
			];

			$data['addresses'][] = [
				'address' => str_replace(["\r\n", "\r", "\n"], '<br/>', preg_replace(["/\\s\\s+/", "/\r\r+/", "/\n\n+/"], '<br/>', trim(str_replace($find, $replace, $result['address_format'])))),
				'edit'    => $this->url->link('account/address.form', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'] . '&address_id=' . $result['address_id']),
				'delete'  => $this->url->link('account/address.delete', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'] . '&address_id=' . $result['address_id'])
			] + $result;
		}

		return $this->load->view('account/address_list', $data);
	}

	/**
	 * Form
	 *
	 * @return void
	 */
	public function form(): void {
		$this->load->language('account/address');

		if (!$this->load->controller('account/login.validate')) {
			$this->session->data['redirect'] = $this->url->link('account/address', 'language=' . $this->config->get('config_language'));

			$this->response->redirect($this->url->link('account/login', 'language=' . $this->config->get('config_language'), true));
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_address'] = !isset($this->request->get['address_id']) ? $this->language->get('text_address_add') : $this->language->get('text_address_edit');

		$data['error_upload_size'] = sprintf($this->language->get('error_upload_size'), $this->config->get('config_file_max_size'));

		$data['config_file_max_size'] = ((int)$this->config->get('config_file_max_size') * 1024 * 1024);

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/address', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'])
		];

		if (!isset($this->request->get['address_id'])) {
			$data['breadcrumbs'][] = [
				'text' => $this->language->get('text_address_add'),
				'href' => $this->url->link('account/address.form', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'])
			];
		} else {
			$data['breadcrumbs'][] = [
				'text' => $this->language->get('text_address_edit'),
				'href' => $this->url->link('account/address.form', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'] . '&address_id=' . (int)$this->request->get['address_id'])
			];
		}

		if (!isset($this->request->get['address_id'])) {
			$data['save'] = $this->url->link('account/address.save', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token']);
		} else {
			$data['save'] = $this->url->link('account/address.save', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'] . '&address_id=' . (int)$this->request->get['address_id']);
		}

		$this->session->data['upload_token'] = oc_token(32);

		$data['upload'] = $this->url->link('tool/upload', 'language=' . $this->config->get('config_language') . '&upload_token=' . $this->session->data['upload_token']);

		// Customer
		if (isset($this->request->get['address_id'])) {
			$this->load->model('account/address');

			$address_info = $this->model_account_address->getAddress($this->customer->getId(), (int)$this->request->get['address_id']);
		}

		if (!empty($address_info)) {
			$data['firstname'] = $address_info['firstname'];
		} else {
			$data['firstname'] = '';
		}

		if (!empty($address_info)) {
			$data['lastname'] = $address_info['lastname'];
		} else {
			$data['lastname'] = '';
		}

		if (!empty($address_info)) {
			$data['company'] = $address_info['company'];
		} else {
			$data['company'] = '';
		}

		if (!empty($address_info)) {
			$data['address_1'] = $address_info['address_1'];
		} else {
			$data['address_1'] = '';
		}

		if (!empty($address_info)) {
			$data['address_2'] = $address_info['address_2'];
		} else {
			$data['address_2'] = '';
		}

		if (!empty($address_info)) {
			$data['postcode'] = $address_info['postcode'];
		} else {
			$data['postcode'] = '';
		}

		if (!empty($address_info)) {
			$data['city'] = $address_info['city'];
		} else {
			$data['city'] = '';
		}

		// Countries
		if (!empty($address_info)) {
			$data['country_id'] = $address_info['country_id'];
		} else {
			$data['country_id'] = (int)$this->config->get('config_country_id');
		}

		// Zones
		if (!empty($address_info)) {
			$data['zone_id'] = $address_info['zone_id'];
		} else {
			$data['zone_id'] = 0;
		}

		// Custom Fields
		$data['custom_fields'] = [];

		$this->load->model('account/custom_field');

		$custom_fields = $this->model_account_custom_field->getCustomFields($this->customer->getGroupId());

		foreach ($custom_fields as $custom_field) {
			if ($custom_field['location'] == 'address') {
				$data['custom_fields'][] = $custom_field;
			}
		}

		if (!empty($address_info)) {
			$data['address_custom_field'] = $address_info['custom_field'];
		} else {
			$data['address_custom_field'] = [];
		}

		if (isset($this->request->get['address_id'])) {
			$data['default'] = $address_info['default'];
		} else {
			$data['default'] = false;
		}

		$data['back'] = $this->url->link('account/address', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token']);

		$data['language'] = $this->config->get('config_language');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/address_form', $data));
	}

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

			// Custom fields validation
			$this->load->model('account/custom_field');

			$custom_fields = $this->model_account_custom_field->getCustomFields($this->customer->getGroupId());

			foreach ($custom_fields as $custom_field) {
				if ($custom_field['location'] == 'address') {
					if ($custom_field['required'] && empty($post_info['custom_field'][$custom_field['custom_field_id']])) {
						$json['error']['custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
					} elseif (($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !oc_validate_regex((string)$post_info['custom_field'][$custom_field['custom_field_id']], $custom_field['validation'])) {
						$json['error']['custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_regex'), $custom_field['name']);
					}
				}
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
			$this->model_account_address->deleteAddress($this->customer->getId(), $address_id);

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
