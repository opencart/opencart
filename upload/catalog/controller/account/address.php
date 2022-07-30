<?php
namespace Opencart\Catalog\Controller\Account;
use \Opencart\System\Helper as Helper;
class Address extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('account/address');

		if (!$this->customer->isLogged() || (!isset($this->request->get['customer_token']) || !isset($this->session->data['customer_token']) || ($this->request->get['customer_token'] != $this->session->data['customer_token']))) {
			$this->session->data['redirect'] = $this->url->link('account/address', 'language=' . $this->config->get('config_language'));

			$this->response->redirect($this->url->link('account/login', 'language=' . $this->config->get('config_language')));
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

		$data['add'] = $this->url->link('account/address|form', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token']);
		$data['back'] = $this->url->link('account/account', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token']);

		$data['list'] = $this->getList();

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/address', $data));
	}

	public function list(): void {
		$this->load->language('account/address');

		if (!$this->customer->isLogged() || (!isset($this->request->get['customer_token']) || !isset($this->session->data['customer_token']) || ($this->request->get['customer_token'] != $this->session->data['customer_token']))) {
			$this->session->data['redirect'] = $this->url->link('account/address', 'language=' . $this->config->get('config_language'));

			$this->response->redirect($this->url->link('account/login', 'language=' . $this->config->get('config_language')));
		}

		$this->response->setOutput($this->getList());
	}

	protected function getList(): string {
		$data['addresses'] = [];

		$this->load->model('account/address');

		$results = $this->model_account_address->getAddresses();

		foreach ($results as $result) {
			$data['addresses'][] = [
				'address_id' => $result['address_id'],
				'address'    => $result['address_format'],
				'edit'       => $this->url->link('account/address|form', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'] . '&address_id=' . $result['address_id']),
				'delete'     => $this->url->link('account/address|delete', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'] . '&address_id=' . $result['address_id'])
			];
		}

		return $this->load->view('account/address_list', $data);
	}

	public function form(): void {
		$this->load->language('account/address');

		$data['language'] = $this->config->get('config_language');

		if (!$this->customer->isLogged() || (!isset($this->request->get['customer_token']) || !isset($this->session->data['customer_token']) || ($this->request->get['customer_token'] != $this->session->data['customer_token']))) {
			$this->session->data['redirect'] = $this->url->link('account/address', 'language=' . $this->config->get('config_language'));

			$this->response->redirect($this->url->link('account/login', 'language=' . $this->config->get('config_language')));
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
				'href' => $this->url->link('account/address|form', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'])
			];
		} else {
			$data['breadcrumbs'][] = [
				'text' => $this->language->get('text_address_edit'),
				'href' => $this->url->link('account/address|form', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'] . '&address_id=' . $this->request->get['address_id'])
			];
		}

		if (!isset($this->request->get['address_id'])) {
			$data['save'] = $this->url->link('account/address|save', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token']);
		} else {
			$data['save'] = $this->url->link('account/address|save', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'] . '&address_id=' . $this->request->get['address_id']);
		}

		$data['upload'] = $this->url->link('tool/upload', 'language=' . $this->config->get('config_language'));

		if (isset($this->request->get['address_id'])) {
			$this->load->model('account/address');

			$address_info = $this->model_account_address->getAddress($this->request->get['address_id']);
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

		if (!empty($address_info)) {
			$data['country_id'] = $address_info['country_id'];
		} else {
			$data['country_id'] = $this->config->get('config_country_id');
		}

		if (!empty($address_info)) {
			$data['zone_id'] = $address_info['zone_id'];
		} else {
			$data['zone_id'] = '';
		}

		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();

		// Custom fields
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

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/address_form', $data));
	}

	public function save(): void {
		$this->load->language('account/address');

		$json = [];

		if (!$this->customer->isLogged() || (!isset($this->request->get['customer_token']) || !isset($this->session->data['customer_token']) || ($this->request->get['customer_token'] != $this->session->data['customer_token']))) {
			$this->session->data['redirect'] = $this->url->link('account/address', 'language=' . $this->config->get('config_language'));

			$json['redirect'] = $this->url->link('account/login', 'language=' . $this->config->get('config_language'), true);
		}

		if (!$json) {
			$keys = [
				'firstname',
				'lastname',
				'address_1',
				'address_2',
				'city',
				'postcode',
				'country_id',
				'zone_id'
			];

			foreach ($keys as $key) {
				if (!isset($this->request->post[$key])) {
					$this->request->post[$key] = '';
				}
			}

			if ((Helper\Utf8\strlen($this->request->post['firstname']) < 1) || (Helper\Utf8\strlen($this->request->post['firstname']) > 32)) {
				$json['error']['firstname'] = $this->language->get('error_firstname');
			}

			if ((Helper\Utf8\strlen($this->request->post['lastname']) < 1) || (Helper\Utf8\strlen($this->request->post['lastname']) > 32)) {
				$json['error']['lastname'] = $this->language->get('error_lastname');
			}

			if ((Helper\Utf8\strlen($this->request->post['address_1']) < 3) || (Helper\Utf8\strlen($this->request->post['address_1']) > 128)) {
				$json['error']['address_1'] = $this->language->get('error_address_1');
			}

			if ((Helper\Utf8\strlen($this->request->post['city']) < 2) || (Helper\Utf8\strlen($this->request->post['city']) > 128)) {
				$json['error']['city'] = $this->language->get('error_city');
			}

			$this->load->model('localisation/country');

			$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

			if ($country_info && $country_info['postcode_required'] && (Helper\Utf8\strlen($this->request->post['postcode']) < 2 || Helper\Utf8\strlen($this->request->post['postcode']) > 10)) {
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

			$custom_fields = $this->model_account_custom_field->getCustomFields($this->customer->getGroupId());

			foreach ($custom_fields as $custom_field) {
				if ($custom_field['location'] == 'address') {
					if ($custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['custom_field_id']])) {
						$json['error']['custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
					} elseif (($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !preg_match(html_entity_decode($custom_field['validation'], ENT_QUOTES, 'UTF-8'), $this->request->post['custom_field'][$custom_field['custom_field_id']])) {
						$json['error']['custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_regex'), $custom_field['name']);
					}
				}
			}
		}

		if (!$json) {
			$this->load->model('account/address');

			// Add Address
			if (!isset($this->request->get['address_id'])) {
				$this->model_account_address->addAddress($this->customer->getId(), $this->request->post);

				$this->session->data['success'] = $this->language->get('text_add');
			}

			// Edit Address
			if (isset($this->request->get['address_id'])) {
				$this->model_account_address->editAddress($this->request->get['address_id'], $this->request->post);

				// If address is in session update it.
				if (isset($this->session->data['shipping_address']) && ($this->session->data['shipping_address']['address_id'] == $this->request->get['address_id'])) {
					$this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->request->get['address_id']);

					unset($this->session->data['shipping_method']);
					unset($this->session->data['shipping_methods']);
				}

				// If address is in session update it.
				if (isset($this->session->data['payment_address']) && ($this->session->data['payment_address']['address_id'] == $this->request->get['address_id'])) {
					$this->session->data['payment_address'] = $this->model_account_address->getAddress($this->request->get['address_id']);

					unset($this->session->data['payment_method']);
					unset($this->session->data['payment_methods']);
				}

				$this->session->data['success'] = $this->language->get('text_edit');
			}

			$json['redirect'] = $this->url->link('account/address', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'], true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function delete(): void {
		$this->load->language('account/address');

		$json = [];

		if (isset($this->request->get['address_id'])) {
			$address_id = $this->request->get['address_id'];
		} else {
			$address_id = 0;
		}

		if (!$this->customer->isLogged() || (!isset($this->request->get['customer_token']) || !isset($this->session->data['customer_token']) || ($this->request->get['customer_token'] != $this->session->data['customer_token']))) {
			$this->session->data['redirect'] = $this->url->link('account/address', 'language=' . $this->config->get('config_language'));

			$json['redirect'] = $this->url->link('account/login', 'language=' . $this->config->get('config_language'), true);
		}

		if (!$json) {
			$this->load->model('account/address');

			if ($this->model_account_address->getTotalAddresses() == 1) {

				$json['error'] = $this->language->get('error_delete');
			}

			if ($this->customer->getAddressId() == $address_id) {
				$json['error'] = $this->language->get('error_default');
			}
		}

		if (!$json) {
			// Delete address from database.
			$this->model_account_address->deleteAddress($address_id);

			// Delete address from session.
			if (isset($this->session->data['shipping_address']['address_id']) && ($this->session->data['shipping_address']['address_id'] == $address_id)) {
				unset($this->session->data['shipping_address']);
				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
			}

			// Delete address from session.
			if (isset($this->session->data['payment_address']['address_id']) && ($this->session->data['payment_address']['address_id'] == $address_id)) {
				unset($this->session->data['payment_address']);
				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);
			}

			$json['success'] = $this->language->get('text_delete');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}