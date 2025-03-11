<?php
namespace Opencart\Admin\Controller\Catalog;
/**
 * Class Option
 *
 * Can be loaded using $this->load->controller('catalog/option');
 *
 * @package Opencart\Admin\Controller\Catalog
 */
class Option extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('catalog/option');

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
			'href' => $this->url->link('catalog/option', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('catalog/option.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('catalog/option.delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->load->controller('catalog/option.getList');

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/option', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('catalog/option');

		$this->response->setOutput($this->load->controller('catalog/option.getList'));
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
			$sort = 'od.name';
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

		$data['action'] = $this->url->link('catalog/option.list', 'user_token=' . $this->session->data['user_token'] . $url);

		// Option
		$data['options'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('catalog/option');

		$results = $this->model_catalog_option->getOptions($filter_data);

		foreach ($results as $result) {
			$data['options'][] = ['edit' => $this->url->link('catalog/option.form', 'user_token=' . $this->session->data['user_token'] . '&option_id=' . $result['option_id'] . $url)] + $result;
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		$data['sort_name'] = $this->url->link('catalog/option.list', 'user_token=' . $this->session->data['user_token'] . '&sort=od.name' . $url);
		$data['sort_sort_order'] = $this->url->link('catalog/option.list', 'user_token=' . $this->session->data['user_token'] . '&sort=o.sort_order' . $url);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$option_total = $this->model_catalog_option->getTotalOptions();

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $option_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('catalog/option.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($option_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($option_total - $this->config->get('config_pagination_admin'))) ? $option_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $option_total, ceil($option_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('catalog/option_list', $data);
	}

	/**
	 * Form
	 *
	 * @return void
	 */
	public function form(): void {
		$this->load->language('catalog/option');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['option_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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
			'href' => $this->url->link('catalog/option', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('catalog/option.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('catalog/option', 'user_token=' . $this->session->data['user_token'] . $url);

		if (isset($this->request->get['option_id'])) {
			$this->load->model('catalog/option');

			$option_info = $this->model_catalog_option->getOption($this->request->get['option_id']);
		}

		if (isset($this->request->get['option_id'])) {
			$data['option_id'] = (int)$this->request->get['option_id'];
		} else {
			$data['option_id'] = 0;
		}

		// Language
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->get['option_id'])) {
			$data['option_description'] = $this->model_catalog_option->getDescriptions($this->request->get['option_id']);
		} else {
			$data['option_description'] = [];
		}

		if (!empty($option_info)) {
			$data['type'] = $option_info['type'];
		} else {
			$data['type'] = '';
		}

		if (!empty($option_info)) {
			$data['validation'] = $option_info['validation'];
		} else {
			$data['validation'] = '';
		}

		if (!empty($option_info)) {
			$data['sort_order'] = $option_info['sort_order'];
		} else {
			$data['sort_order'] = '';
		}

		if (isset($this->request->get['option_id'])) {
			$option_values = $this->model_catalog_option->getValueDescriptions($this->request->get['option_id']);
		} else {
			$option_values = [];
		}

		// Image
		$this->load->model('tool/image');

		$data['option_values'] = [];

		foreach ($option_values as $option_value) {
			if ($option_value['image'] && is_file(DIR_IMAGE . html_entity_decode($option_value['image'], ENT_QUOTES, 'UTF-8'))) {
				$image = $option_value['image'];
				$thumb = $option_value['image'];
			} else {
				$image = '';
				$thumb = 'no_image.png';
			}

			$data['option_values'][] = [
				'option_value_id'          => $option_value['option_value_id'],
				'option_value_description' => $option_value['option_value_description'],
				'image'                    => $image,
				'thumb'                    => $this->model_tool_image->resize($thumb, $this->config->get('config_image_default_width'), $this->config->get('config_image_default_height')),
				'sort_order'               => $option_value['sort_order']
			];
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', $this->config->get('config_image_default_width'), $this->config->get('config_image_default_height'));

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/option_form', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('catalog/option');

		$json = [];

		if (!$this->user->hasPermission('modify', 'catalog/option')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		$filter_data = [
			'option_id'          => 0,
			'type'               => '',
			'sort_order'         => 0,
			'option_description' => [],
			'option_value'       => []
		];

		$post_info = oc_filter_data($filter_data, $this->request->post);

		foreach ($post_info['option_description'] as $language_id => $value) {
			if (!oc_validate_length($value['name'], 1, 128)) {
				$json['error']['name_' . $language_id] = $this->language->get('error_name');
			}
		}

		if (($post_info['type'] == 'select' || $post_info['type'] == 'radio' || $post_info['type'] == 'checkbox') && !isset($post_info['option_value'])) {
			$json['error']['warning'] = $this->language->get('error_type');
		}

		if (isset($post_info['option_value'])) {
			if (isset($post_info['option_id'])) {
				$this->load->model('catalog/product');

				$option_value_data = [];

				foreach ($post_info['option_value'] as $option_value) {
					if ($option_value['option_value_id']) {
						$option_value_data[] = $option_value['option_value_id'];
					}
				}

				$product_option_values = $this->model_catalog_product->getOptionValuesByOptionId($post_info['option_id']);

				foreach ($product_option_values as $product_option_value) {
					if (!in_array($product_option_value['option_value_id'], $option_value_data)) {
						$json['error']['warning'] = sprintf($this->language->get('error_value'), $this->model_catalog_product->getTotalOptionValuesByOptionValueId($product_option_value['option_value_id']));
					}
				}
			}
		}

		if (isset($post_info['option_value'])) {
			foreach ($post_info['option_value'] as $option_value_id => $option_value) {
				foreach ($option_value['option_value_description'] as $language_id => $option_value_description) {
					if (!oc_validate_length($option_value_description['name'], 1, 128)) {
						$json['error']['option_value_' . $option_value_id . '_' . $language_id] = $this->language->get('error_option_value');
					}
				}
			}
		}

		if (isset($json['error']) && !isset($json['error']['warning'])) {
			$json['error']['warning'] = $this->language->get('error_warning');
		}

		if (!$json) {
			$this->load->model('catalog/option');

			if (!$post_info['option_id']) {
				$json['option_id'] = $this->model_catalog_option->addOption($post_info);
			} else {
				$this->model_catalog_option->editOption($post_info['option_id'], $post_info);
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
		$this->load->language('catalog/option');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'catalog/option')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->load->model('catalog/product');

		foreach ($selected as $option_id) {
			$product_total = $this->model_catalog_product->getTotalOptionsByOptionId($option_id);

			if ($product_total) {
				$json['error'] = sprintf($this->language->get('error_product'), $product_total);
			}
		}

		if (!$json) {
			$this->load->model('catalog/option');

			foreach ($selected as $option_id) {
				$this->model_catalog_option->deleteOption($option_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Autocomplete
	 *
	 * @return void
	 */
	public function autocomplete(): void {
		$json = [];

		if (isset($this->request->get['filter_name'])) {
			$this->load->language('catalog/option');

			// Option
			$this->load->model('catalog/option');

			// Image
			$this->load->model('tool/image');

			$filter_data = [
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => $this->config->get('config_autocomplete_limit')
			];

			$options = $this->model_catalog_option->getOptions($filter_data);

			foreach ($options as $option) {
				$option_value_data = [];

				if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') {
					$option_values = $this->model_catalog_option->getValues($option['option_id']);

					foreach ($option_values as $option_value) {
						if ($option_value['image'] && is_file(DIR_IMAGE . html_entity_decode($option_value['image'], ENT_QUOTES, 'UTF-8'))) {
							$image = $option_value['image'];
						} else {
							$image = 'no_image.png';
						}

						$option_value_data[] = [
							'option_value_id' => $option_value['option_value_id'],
							'name'            => strip_tags(html_entity_decode($option_value['name'], ENT_QUOTES, 'UTF-8')),
							'image'           => $this->model_tool_image->resize($image, 50, 50)
						];
					}

					$sort_order = [];

					foreach ($option_value_data as $key => $value) {
						$sort_order[$key] = $value['name'];
					}

					array_multisort($sort_order, SORT_ASC, $option_value_data);
				}

				$type = '';

				if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox') {
					$type = $this->language->get('text_choose');
				}

				if ($option['type'] == 'text' || $option['type'] == 'textarea') {
					$type = $this->language->get('text_input');
				}

				if ($option['type'] == 'file') {
					$type = $this->language->get('text_file');
				}

				if ($option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {
					$type = $this->language->get('text_date');
				}

				$json[] = [
					'option_id'    => $option['option_id'],
					'name'         => strip_tags(html_entity_decode($option['name'], ENT_QUOTES, 'UTF-8')),
					'category'     => $type,
					'type'         => $option['type'],
					'option_value' => $option_value_data
				];
			}
		}

		$sort_order = [];

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
