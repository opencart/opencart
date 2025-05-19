<?php
namespace Opencart\Admin\Controller\Localisation;
/**
 * Class Address Format
 *
 * @package Opencart\Admin\Controller\Localisation
 */
class AddressFormat extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('localisation/address_format');

		$this->document->setTitle($this->language->get('heading_title'));

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('localisation/address_format', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('localisation/address_format.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('localisation/address_format.delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/address_format', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('localisation/address_format');

		$this->response->setOutput($this->getList());
	}

	/**
	 * Get List
	 *
	 * @return string
	 */
	public function getList(): string {
		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['action'] = $this->url->link('localisation/address_format.list', 'user_token=' . $this->session->data['user_token'] . $url);

		// Address Formats
		$data['address_formats'] = [];

		$filter_data = [
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('localisation/address_format');

		$results = $this->model_localisation_address_format->getAddressFormats($filter_data);

		foreach ($results as $result) {
			$data['address_formats'][] = [
				'name'           => $result['name'],
				'address_format' => nl2br($result['address_format']),
				'edit'           => $this->url->link('localisation/address_format.form', 'user_token=' . $this->session->data['user_token'] . '&address_format_id=' . $result['address_format_id'] . $url)
			] + $result;
		}

		// Default
		$data['address_format_id'] = $this->config->get('config_address_format_id');

			// Total Address Formats
		$address_format_total = $this->model_localisation_address_format->getTotalAddressFormats($filter_data);

		// Pagination
		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $address_format_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('localisation/address_format.list', 'user_token=' . $this->session->data['user_token'] . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($address_format_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($address_format_total - $this->config->get('config_pagination_admin'))) ? $address_format_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $address_format_total, ceil($address_format_total / $this->config->get('config_pagination_admin')));

		return $this->load->view('localisation/address_format_list', $data);
	}

	/**
	 * Form
	 *
	 * @return void
	 */
	public function form(): void {
		$this->load->language('localisation/address_format');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['address_format_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('localisation/address_format', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('localisation/address_format.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('localisation/address_format', 'user_token=' . $this->session->data['user_token'] . $url);

		// Address Format
		if (isset($this->request->get['address_format_id'])) {
			$this->load->model('localisation/address_format');

			$address_format_info = $this->model_localisation_address_format->getAddressFormat($this->request->get['address_format_id']);
		}

		if (!empty($address_format_info)) {
			$data['address_format_id'] = $address_format_info['address_format_id'];
		} else {
			$data['address_format_id'] = 0;
		}

		if (!empty($address_format_info)) {
			$data['name'] = $address_format_info['name'];
		} else {
			$data['name'] = '';
		}

		if (!empty($address_format_info)) {
			$data['address_format'] = $address_format_info['address_format'];
		} else {
			$data['address_format'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/address_format_form', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('localisation/address_format');

		$json = [];

		if (!$this->user->hasPermission('modify', 'localisation/address_format')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		$required = [
			'address_format_id' => 0,
			'name'              => '',
			'address_format'    => ''
		];

		$post_info = $this->request->post + $required;

		if (!oc_validate_length($post_info['name'], 1, 128)) {
			$json['error']['name'] = $this->language->get('error_name');
		}

		if (!$json) {
			// Address Format
			$this->load->model('localisation/address_format');

			if (!$post_info['address_format_id']) {
				$json['address_format_id'] = $this->model_localisation_address_format->addAddressFormat($post_info);
			} else {
				$this->model_localisation_address_format->editAddressFormat($post_info['address_format_id'], $post_info);
			}

			$json['success'] = $this->language->get('text_success');
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
		$this->load->language('localisation/address_format');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'localisation/address_format')) {
			$json['error'] = $this->language->get('error_permission');
		}

		// Countries
		$this->load->model('localisation/country');

		foreach ($selected as $address_format_id) {
			if ($this->config->get('config_address_format_id') == $address_format_id) {
				$json['error'] = $this->language->get('error_default');
			}

			// Total Countries
			$country_total = $this->model_localisation_country->getTotalCountriesByAddressFormatId($address_format_id);

			if ($country_total) {
				$json['error'] = sprintf($this->language->get('error_country'), $country_total);
			}
		}

		if (!$json) {
			// Address Format
			$this->load->model('localisation/address_format');

			foreach ($selected as $address_format_id) {
				$this->model_localisation_address_format->deleteAddressFormat($address_format_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
