<?php
namespace Opencart\Admin\Controller\Localisation;
/**
 * Class Length Class
 *
 * @package Opencart\Admin\Controller\Localisation
 */
class LengthClass extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('localisation/length_class');

		$this->document->setTitle($this->language->get('heading_title'));

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

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
			'href' => $this->url->link('localisation/length_class', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('localisation/length_class.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('localisation/length_class.delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/length_class', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('localisation/length_class');

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
			$sort = 'title';
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

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['action'] = $this->url->link('localisation/length_class.list', 'user_token=' . $this->session->data['user_token'] . $url);

		// Length Class
		$data['length_classes'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('localisation/length_class');

		$results = $this->model_localisation_length_class->getLengthClasses($filter_data);

		foreach ($results as $result) {
			$data['length_classes'][] = [
				'title' => $result['title'] . (($result['length_class_id'] == $this->config->get('config_length_class_id')) ? $this->language->get('text_default') : ''),
				'edit'  => $this->url->link('localisation/length_class.form', 'user_token=' . $this->session->data['user_token'] . '&length_class_id=' . $result['length_class_id'] . $url)
			] + $result;
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		$data['sort_title'] = $this->url->link('localisation/length_class.list', 'user_token=' . $this->session->data['user_token'] . '&sort=title' . $url);
		$data['sort_unit'] = $this->url->link('localisation/length_class.list', 'user_token=' . $this->session->data['user_token'] . '&sort=unit' . $url);
		$data['sort_value'] = $this->url->link('localisation/length_class.list', 'user_token=' . $this->session->data['user_token'] . '&sort=value' . $url);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$length_class_total = $this->model_localisation_length_class->getTotalLengthClasses();

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $length_class_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('localisation/length_class.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($length_class_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($length_class_total - $this->config->get('config_pagination_admin'))) ? $length_class_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $length_class_total, ceil($length_class_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('localisation/length_class_list', $data);
	}

	/**
	 * @return void
	 */
	public function form(): void {
		$this->load->language('localisation/length_class');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['length_class_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

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
			'href' => $this->url->link('localisation/length_class', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('localisation/length_class.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('localisation/length_class', 'user_token=' . $this->session->data['user_token'] . $url);

		if (isset($this->request->get['length_class_id'])) {
			$this->load->model('localisation/length_class');

			$length_class_info = $this->model_localisation_length_class->getLengthClass($this->request->get['length_class_id']);
		}

		if (isset($this->request->get['length_class_id'])) {
			$data['length_class_id'] = (int)$this->request->get['length_class_id'];
		} else {
			$data['length_class_id'] = 0;
		}

		// Language
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (!empty($length_class_info)) {
			$data['length_class_description'] = $this->model_localisation_length_class->getDescriptions($this->request->get['length_class_id']);
		} else {
			$data['length_class_description'] = [];
		}

		if (!empty($length_class_info)) {
			$data['value'] = $length_class_info['value'];
		} else {
			$data['value'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/length_class_form', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('localisation/length_class');

		$json = [];

		if (!$this->user->hasPermission('modify', 'localisation/length_class')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['length_class_description'] as $language_id => $value) {
			if (!oc_validate_length($value['title'], 3, 32)) {
				$json['error']['title_' . $language_id] = $this->language->get('error_title');
			}

			if (!$value['unit'] || (oc_strlen($value['unit']) > 4)) {
				$json['error']['unit_' . $language_id] = $this->language->get('error_unit');
			}
		}

		if (!$json) {
			$this->load->model('localisation/length_class');

			if (!$this->request->post['length_class_id']) {
				$json['length_class_id'] = $this->model_localisation_length_class->addLengthClass($this->request->post);
			} else {
				$this->model_localisation_length_class->editLengthClass($this->request->post['length_class_id'], $this->request->post);
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
		$this->load->language('localisation/length_class');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'localisation/length_class')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->load->model('catalog/product');

		foreach ($selected as $length_class_id) {
			if ($this->config->get('config_length_class_id') == $length_class_id) {
				$json['error'] = $this->language->get('error_default');
			}

			$product_total = $this->model_catalog_product->getTotalProductsByLengthClassId($length_class_id);

			if ($product_total) {
				$json['error'] = sprintf($this->language->get('error_product'), $product_total);
			}
		}

		if (!$json) {
			$this->load->model('localisation/length_class');

			foreach ($selected as $length_class_id) {
				$this->model_localisation_length_class->deleteLengthClass($length_class_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
