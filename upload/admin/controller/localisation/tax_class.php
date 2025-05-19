<?php
namespace Opencart\Admin\Controller\Localisation;
/**
 * Class Tax Class
 *
 * @package Opencart\Admin\Controller\Localisation
 */
class TaxClass extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('localisation/tax_class');

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
			'href' => $this->url->link('localisation/tax_class', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('localisation/tax_class.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('localisation/tax_class.delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/tax_class', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('localisation/tax_class');

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

		$data['action'] = $this->url->link('localisation/tax_class.list', 'user_token=' . $this->session->data['user_token'] . $url);

		// Tax Classes
		$data['tax_classes'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('localisation/tax_class');

		$results = $this->model_localisation_tax_class->getTaxClasses($filter_data);

		foreach ($results as $result) {
			$data['tax_classes'][] = ['edit' => $this->url->link('localisation/tax_class.form', 'user_token=' . $this->session->data['user_token'] . '&tax_class_id=' . $result['tax_class_id'] . $url)] + $result;
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		// Sort
		$data['sort_title'] = $this->url->link('localisation/tax_class.list', 'user_token=' . $this->session->data['user_token'] . '&sort=title' . $url);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		// Tax Classes
		$tax_class_total = $this->model_localisation_tax_class->getTotalTaxClasses();

		// Pagination
		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $tax_class_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('localisation/tax_class.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($tax_class_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($tax_class_total - $this->config->get('config_pagination_admin'))) ? $tax_class_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $tax_class_total, ceil($tax_class_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('localisation/tax_class_list', $data);
	}

	/**
	 * Form
	 *
	 * @return void
	 */
	public function form(): void {
		$this->load->language('localisation/tax_class');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['tax_class_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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
			'href' => $this->url->link('localisation/tax_class', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('localisation/tax_class.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('localisation/tax_class', 'user_token=' . $this->session->data['user_token'] . $url);

		// Tax Class
		if (isset($this->request->get['tax_class_id'])) {
			$this->load->model('localisation/tax_class');

			$tax_class_info = $this->model_localisation_tax_class->getTaxClass((int)$this->request->get['tax_class_id']);
		}

		if (!empty($tax_class_info)) {
			$data['tax_class_id'] = $tax_class_info['tax_class_id'];
		} else {
			$data['tax_class_id'] = 0;
		}

		if (!empty($tax_class_info)) {
			$data['title'] = $tax_class_info['title'];
		} else {
			$data['title'] = '';
		}

		if (!empty($tax_class_info)) {
			$data['description'] = $tax_class_info['description'];
		} else {
			$data['description'] = '';
		}

		// Tax Rates
		$this->load->model('localisation/tax_rate');

		$data['tax_rates'] = $this->model_localisation_tax_rate->getTaxRates();

		if (!empty($tax_class_info)) {
			$data['tax_rules'] = $this->model_localisation_tax_class->getTaxRules($tax_class_info['tax_class_id']);
		} else {
			$data['tax_rules'] = [];
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/tax_class_form', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('localisation/tax_class');

		$json = [];

		if (!$this->user->hasPermission('modify', 'localisation/tax_class')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		$required = [
			'tax_class_id' => 0,
			'title'        => '',
			'description'  => ''
		];

		$post_info = $this->request->post + $required;

		if (!oc_validate_length($post_info['title'], 3, 32)) {
			$json['error']['title'] = $this->language->get('error_title');
		}

		if (!oc_validate_length($post_info['description'], 3, 255)) {
			$json['error']['description'] = $this->language->get('error_description');
		}

		if (!$json) {
			// Tax Class
			$this->load->model('localisation/tax_class');

			if (!$post_info['tax_class_id']) {
				$json['tax_class_id'] = $this->model_localisation_tax_class->addTaxClass($post_info);
			} else {
				$this->model_localisation_tax_class->editTaxClass($post_info['tax_class_id'], $post_info);
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
		$this->load->language('localisation/tax_class');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'localisation/tax_class')) {
			$json['error'] = $this->language->get('error_permission');
		}

		// Products
		$this->load->model('catalog/product');

		foreach ($selected as $tax_class_id) {
			// Total Products
			$product_total = $this->model_catalog_product->getTotalProductsByTaxClassId($tax_class_id);

			if ($product_total) {
				$json['error'] = sprintf($this->language->get('error_product'), $product_total);
			}
		}

		if (!$json) {
			// Tax Class
			$this->load->model('localisation/tax_class');

			foreach ($selected as $tax_class_id) {
				$this->model_localisation_tax_class->deleteTaxClass($tax_class_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
