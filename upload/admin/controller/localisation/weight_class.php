<?php
namespace Opencart\Admin\Controller\Localisation;
/**
 * Class Weight Class
 *
 * @package Opencart\Admin\Controller\Localisation
 */
class WeightClass extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('localisation/weight_class');

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
			'href' => $this->url->link('localisation/weight_class', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('localisation/weight_class.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('localisation/weight_class.delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/weight_class', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('localisation/weight_class');

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

		$data['action'] = $this->url->link('localisation/weight_class.list', 'user_token=' . $this->session->data['user_token'] . $url);

		// Weight Classes
		$data['weight_classes'] = [];

		$filter_data = [
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('localisation/weight_class');

		$results = $this->model_localisation_weight_class->getWeightClasses($filter_data);

		foreach ($results as $result) {
			$data['weight_classes'][] = ['edit'  => $this->url->link('localisation/weight_class.form', 'user_token=' . $this->session->data['user_token'] . '&weight_class_id=' . $result['weight_class_id'] . $url)] + $result;
		}

		// Default
		$data['weight_class_id'] = $this->config->get('config_weight_class_id');

		// Total Weight Classes
		$weight_class_total = $this->model_localisation_weight_class->getTotalWeightClasses();

		// Pagination
		$data['total'] = $weight_class_total;
		$data['page'] = $page;
		$data['limit'] = $this->config->get('config_pagination_admin');
		$data['pagination'] = $this->url->link('localisation/weight_class.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}');

		$data['results'] = sprintf($this->language->get('text_pagination'), ($weight_class_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($weight_class_total - $this->config->get('config_pagination_admin'))) ? $weight_class_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $weight_class_total, ceil($weight_class_total / $this->config->get('config_pagination_admin')));

		return $this->load->view('localisation/weight_class_list', $data);
	}

	/**
	 * Form
	 *
	 * @return void
	 */
	public function form(): void {
		$this->load->language('localisation/weight_class');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['weight_class_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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
			'href' => $this->url->link('localisation/weight_class', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('localisation/weight_class.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('localisation/weight_class', 'user_token=' . $this->session->data['user_token'] . $url);

		// Weight Class
		if (isset($this->request->get['weight_class_id'])) {
			$this->load->model('localisation/weight_class');

			$weight_class_info = $this->model_localisation_weight_class->getWeightClass($this->request->get['weight_class_id']);
		}

		if (!empty($weight_class_info)) {
			$data['weight_class_id'] = $weight_class_info['weight_class_id'];
		} else {
			$data['weight_class_id'] = 0;
		}

		// Languages
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (!empty($weight_class_info)) {
			$data['weight_class_description'] = $this->model_localisation_weight_class->getDescriptions($weight_class_info['weight_class_id']);
		} else {
			$data['weight_class_description'] = [];
		}

		if (!empty($weight_class_info)) {
			$data['value'] = $weight_class_info['value'];
		} else {
			$data['value'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/weight_class_form', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('localisation/weight_class');

		$json = [];

		if (!$this->user->hasPermission('modify', 'localisation/weight_class')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		$required = [
			'weight_class_id'          => 0,
			'weight_class_description' => [],
			'value'                    => 0.0
		];

		$post_info = $this->request->post + $required;

		foreach ($post_info['weight_class_description'] as $language_id => $value) {
			if (!oc_validate_length($value['title'], 3, 32)) {
				$json['error']['title_' . $language_id] = $this->language->get('error_title');
			}

			if (!$value['unit'] || (oc_strlen($value['unit']) > 4)) {
				$json['error']['unit_' . $language_id] = $this->language->get('error_unit');
			}
		}

		if (!$json) {
			// Weight Class
			$this->load->model('localisation/weight_class');

			if (!$post_info['weight_class_id']) {
				$json['weight_class_id'] = $this->model_localisation_weight_class->addWeightClass($post_info);
			} else {
				$this->model_localisation_weight_class->editWeightClass($post_info['weight_class_id'], $post_info);
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
		$this->load->language('localisation/weight_class');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'localisation/weight_class')) {
			$json['error'] = $this->language->get('error_permission');
		}

		// Products
		$this->load->model('catalog/product');

		foreach ($selected as $weight_class_id) {
			if ($this->config->get('config_weight_class_id') == $weight_class_id) {
				$json['error'] = $this->language->get('error_default');
			}

			// Total Products
			$product_total = $this->model_catalog_product->getTotalProductsByWeightClassId($weight_class_id);

			if ($product_total) {
				$json['error'] = sprintf($this->language->get('error_product'), $product_total);
			}
		}

		if (!$json) {
			// Weight Class
			$this->load->model('localisation/weight_class');

			foreach ($selected as $weight_class_id) {
				$this->model_localisation_weight_class->deleteWeightClass($weight_class_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
