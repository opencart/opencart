<?php
namespace Opencart\Admin\Controller\Localisation;
/**
 * Class Identifier
 *
 * @package Opencart\Admin\Controller\Catalog
 */
class Identifier extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('localisation/identifier');

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
			'href' => $this->url->link('localisation/identifier', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('localisation/identifier.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('localisation/identifier.delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/identifier', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('localisation/identifier');

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

		$data['action'] = $this->url->link('localisation/identifier.list', 'user_token=' . $this->session->data['user_token'] . $url);

		// Identifiers
		$data['identifiers'] = [];

		$filter_data = [
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('localisation/identifier');

		$results = $this->model_localisation_identifier->getIdentifiers($filter_data);

		foreach ($results as $result) {
			$data['identifiers'][] = ['edit' => $this->url->link('localisation/identifier.form', 'user_token=' . $this->session->data['user_token'] . '&identifier_id=' . $result['identifier_id'] . $url)] + $result;
		}

		$url = '';

		// Total Identifiers
		$identifier_total = $this->model_localisation_identifier->getTotalIdentifiers();

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $identifier_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('localisation/identifier.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($identifier_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($identifier_total - $this->config->get('config_pagination_admin'))) ? $identifier_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $identifier_total, ceil($identifier_total / $this->config->get('config_pagination_admin')));

		return $this->load->view('localisation/identifier_list', $data);
	}

	/**
	 * Form
	 *
	 * @return void
	 */
	public function form(): void {
		$this->load->language('localisation/identifier');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['identifier_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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
			'href' => $this->url->link('localisation/identifier', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('localisation/identifier.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('localisation/identifier', 'user_token=' . $this->session->data['user_token'] . $url);

		// Identifier
		if (isset($this->request->get['identifier_id'])) {
			$this->load->model('localisation/identifier');

			$identifier_info = $this->model_localisation_identifier->getIdentifier((int)$this->request->get['identifier_id']);
		}

		if (!empty($identifier_info)) {
			$data['identifier_id'] = $identifier_info['identifier_id'];
		} else {
			$data['identifier_id'] = 0;
		}

		if (!empty($identifier_info)) {
			$data['name'] = $identifier_info['name'];
		} else {
			$data['name'] = '';
		}

		if (!empty($identifier_info)) {
			$data['code'] = $identifier_info['code'];
		} else {
			$data['code'] = '';
		}

		if (!empty($identifier_info)) {
			$data['validation'] = $identifier_info['validation'];
		} else {
			$data['validation'] = '';
		}

		if (!empty($identifier_info)) {
			$data['status'] = $identifier_info['status'];
		} else {
			$data['status'] = 0;
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/identifier_form', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('localisation/identifier');

		$json = [];

		if (!$this->user->hasPermission('modify', 'localisation/identifier')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		$required = [
			'identifier_id' => 0,
			'name'          => '',
			'code'          => ''
		];

		$post_info = $this->request->post + $required;

		if (!oc_validate_length($post_info['name'], 1, 64)) {
			$json['error']['name'] = $this->language->get('error_name');
		}

		if (!oc_validate_length($post_info['code'], 3, 48)) {
			$json['error']['code'] = $this->language->get('error_code');
		}

		// Identifier
		$this->load->model('localisation/identifier');

		$identifier_info = $this->model_localisation_identifier->getIdentifierByCode($post_info['code']);

		if ($identifier_info && (!$post_info['identifier_id'] || ($identifier_info['identifier_id'] != $post_info['identifier_id']))) {
			$json['error']['code'] = $this->language->get('error_exists');
		}

		if (!$json) {
			if (!$post_info['identifier_id']) {
				$json['identifier_id'] = $this->model_localisation_identifier->addIdentifier($this->request->post);
			} else {
				$this->model_localisation_identifier->editIdentifier($post_info['identifier_id'], $this->request->post);
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
		$this->load->language('localisation/identifier');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'localisation/identifier')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Identifier
			$this->load->model('localisation/identifier');

			foreach ($selected as $identifier_id) {
				$this->model_localisation_identifier->deleteIdentifier($identifier_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
