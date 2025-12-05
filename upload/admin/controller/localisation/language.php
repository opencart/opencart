<?php
namespace Opencart\Admin\Controller\Localisation;
/**
 * Class Language
 *
 * @package Opencart\Admin\Controller\Localisation
 */
class Language extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('localisation/language');

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
			'href' => $this->url->link('localisation/language', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('localisation/language.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('localisation/language.delete', 'user_token=' . $this->session->data['user_token']);
		$data['enable']	= $this->url->link('localisation/language.enable', 'user_token=' . $this->session->data['user_token']);
		$data['disable'] = $this->url->link('localisation/language.disable', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/language', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('localisation/language');

		$this->response->setOutput($this->getList());
	}

	/**
	 * Get List
	 *
	 * @return string
	 */
	public function getList(): string {
		if (isset($this->request->get['sort'])) {
			$sort = (string)$this->request->get['sort'];
		} else {
			$sort = 'name';
		}

		if (isset($this->request->get['order'])) {
			$order = (string)$this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['action'] = $this->url->link('localisation/language.list', 'user_token=' . $this->session->data['user_token'] . $url);

		// Languages
		$data['languages'] = [];

		$filter_data = [
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('localisation/language');

		$results = $this->model_localisation_language->getLanguages($filter_data);

		foreach ($results as $result) {
			$data['languages'][] = ['edit' => $this->url->link('localisation/language.form', 'user_token=' . $this->session->data['user_token'] . '&language_id=' . $result['language_id'] . $url)] + $result;
		}

		// Default
		$data['code'] = $this->config->get('config_language_admin');

		// Total Languages
		$language_total = $this->model_localisation_language->getTotalLanguages();

		// Pagination
		$data['total'] = $language_total;
		$data['page'] = $page;
		$data['limit'] = $this->config->get('config_pagination_admin');
		$data['pagination'] = $this->url->link('localisation/language.list', 'user_token=' . $this->session->data['user_token'] . '&page={page}');

		$data['results'] = sprintf($this->language->get('text_pagination'), ($language_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($language_total - $this->config->get('config_pagination_admin'))) ? $language_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $language_total, ceil($language_total / $this->config->get('config_pagination_admin')));

		return $this->load->view('localisation/language_list', $data);
	}

	/**
	 * Form
	 *
	 * @return void
	 */
	public function form(): void {
		$this->load->language('localisation/language');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['language_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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
			'href' => $this->url->link('localisation/language', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('localisation/language.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('localisation/language', 'user_token=' . $this->session->data['user_token'] . $url);

		// Language
		if (isset($this->request->get['language_id'])) {
			$this->load->model('localisation/language');

			$language_info = $this->model_localisation_language->getLanguage((int)$this->request->get['language_id']);
		}

		if (!empty($language_info)) {
			$data['language_id'] = $language_info['language_id'];
		} else {
			$data['language_id'] = 0;
		}

		if (!empty($language_info)) {
			$data['name'] = $language_info['name'];
		} else {
			$data['name'] = '';
		}

		if (!empty($language_info)) {
			$data['code'] = $language_info['code'];
		} else {
			$data['code'] = '';
		}

		if (!empty($language_info)) {
			$data['extension'] = $language_info['extension'];
		} else {
			$data['extension'] = '';
		}

		if (!empty($language_info)) {
			$data['locale'] = $language_info['locale'];
		} else {
			$data['locale'] = '';
		}

		if (!empty($language_info)) {
			$data['status'] = $language_info['status'];
		} else {
			$data['status'] = 1;
		}

		if (!empty($language_info)) {
			$data['sort_order'] = $language_info['sort_order'];
		} else {
			$data['sort_order'] = 1;
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/language_form', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('localisation/language');

		$json = [];

		if (!$this->user->hasPermission('modify', 'localisation/language')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		$required = [
			'language_id' => 0,
			'name'        => '',
			'code'        => '',
			'locale'      => '',
			'extension'   => '',
			'sort_order'  => 0,
			'status'      => 0
		];

		$post_info = $this->request->post + $required;

		if (!oc_validate_length($post_info['name'], 1, 32)) {
			$json['error']['name'] = $this->language->get('error_name');
		}

		if (!oc_validate_length($post_info['code'], 2, 5)) {
			$json['error']['code'] = $this->language->get('error_code');
		}

		if (!oc_validate_length($post_info['locale'], 2, 255)) {
			$json['error']['locale'] = $this->language->get('error_locale');
		}

		// Language
		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguageByCode($post_info['code']);

		if ($language_info && (!$post_info['language_id'] || ($language_info['language_id'] != $post_info['language_id']))) {
			$json['error']['code'] = $this->language->get('error_exists');
		}

		if (!$json) {
			if (!$post_info['language_id']) {
				$json['language_id'] = $this->model_localisation_language->addLanguage($post_info);
			} else {
				$this->model_localisation_language->editLanguage($post_info['language_id'], $post_info);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Enable
	 *
	 * @return void
	 */
	public function enable(): void {
		$this->load->language('localisation/language');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'localisation/language')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('localisation/language');

			foreach ($selected as $language_id) {
				$this->model_localisation_language->editStatus((int)$language_id, true);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Disable
	 *
	 * @return void
	 */
	public function disable(): void {
		$this->load->language('localisation/language');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'localisation/language')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('localisation/language');

			foreach ($selected as $language_id) {
				$this->model_localisation_language->editStatus((int)$language_id, false);
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
		$this->load->language('localisation/language');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'localisation/language')) {
			$json['error'] = $this->language->get('error_permission');
		}

		// Setting
		$this->load->model('setting/store');

		// Language
		$this->load->model('localisation/language');

		// Orders
		$this->load->model('sale/order');

		foreach ($selected as $language_id) {
			$language_info = $this->model_localisation_language->getLanguage($language_id);

			if ($language_info) {
				if ($this->config->get('config_language') == $language_info['code']) {
					$json['error'] = $this->language->get('error_default');
				}

				if ($this->config->get('config_language_admin') == $language_info['code']) {
					$json['error'] = $this->language->get('error_admin');
				}

				$store_total = $this->model_setting_store->getTotalStoresByLanguage($language_info['code']);

				if ($store_total) {
					$json['error'] = sprintf($this->language->get('error_store'), $store_total);
				}
			}

			// Total Orders
			$order_total = $this->model_sale_order->getTotalOrdersByLanguageId($language_id);

			if ($order_total) {
				$json['error'] = sprintf($this->language->get('error_order'), $order_total);
			}
		}

		if (!$json) {
			foreach ($selected as $language_id) {
				$this->model_localisation_language->deleteLanguage($language_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Generate
	 *
	 * @return void
	 */
	public function generate(): void {
		$this->load->language('localisation/language');

		$json = [];

		if (!$this->user->hasPermission('modify', 'localisation/language')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$file = DIR_CATALOG . 'view/data/localisation/language.json';

			$this->load->model('localisation/language');

			$output = json_encode($this->model_localisation_language->getLanguages());

			if (file_put_contents($file, $output)) {
				$json['success'] = $this->language->get('text_success');
			} else {
				$json['error'] = $this->language->get('error_file');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
