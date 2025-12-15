<?php
namespace Opencart\Admin\Controller\Catalog;
/**
 * Class Manufacturer
 *
 * Can be loaded using $this->load->controller('catalog/manufacturer');
 *
 * @package Opencart\Admin\Controller\Catalog
 */
class Manufacturer extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('catalog/manufacturer');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['filter_name'])) {
			$filter_name = (string)$this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}

		if (isset($this->request->get['filter_store_id'])) {
			$filter_store_id = (int)$this->request->get['filter_store_id'];
		} else {
			$filter_store_id = '';
		}

		if (isset($this->request->get['filter_language_id'])) {
			$filter_language_id = $this->request->get['filter_language_id'];
		} else {
			$filter_language_id = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = '';
		}

		$allowed = [
			'filter_name',
			'filter_store_id',
			'filter_language_id',
			'filter_status',
			'sort',
			'order',
			'page'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/manufacturer', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('catalog/manufacturer.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('catalog/manufacturer.delete', 'user_token=' . $this->session->data['user_token']);
		$data['enable']	= $this->url->link('catalog/manufacturer.enable', 'user_token=' . $this->session->data['user_token']);
		$data['disable'] = $this->url->link('catalog/manufacturer.disable', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->load->controller('catalog/manufacturer.getList');

		// Stores
		$data['stores'] = [];

		$data['stores'][] = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name')
		];

		$this->load->model('setting/store');

		$data['stores'] = array_merge($data['stores'], $this->model_setting_store->getStores());

		// Languages
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['filter_name'] = $filter_name;
		$data['filter_store_id'] = $filter_store_id;
		$data['filter_language_id'] = $filter_language_id;
		$data['filter_status'] = $filter_status;

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/manufacturer', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('catalog/manufacturer');

		$this->response->setOutput($this->load->controller('catalog/manufacturer.getList'));
	}

	/**
	 * Get List
	 *
	 * @return string
	 */
	public function getList(): string {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}

		if (isset($this->request->get['filter_store_id'])) {
			$filter_store_id = $this->request->get['filter_store_id'];
		} else {
			$filter_store_id = '';
		}

		if (isset($this->request->get['filter_language_id'])) {
			$filter_language_id = $this->request->get['filter_language_id'];
		} else {
			$filter_language_id = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = '';
		}

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

		$allowed = [
			'filter_name',
			'filter_store_id',
			'filter_language_id',
			'filter_status',
			'sort',
			'order',
			'page'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		$data['action'] = $this->url->link('catalog/manufacturer.list', 'user_token=' . $this->session->data['user_token'] . $url);

		// Manufacturers
		$data['manufacturers'] = [];

		$filter_data = [
			'filter_name'        => $filter_name,
			'filter_store_id'    => $filter_store_id,
			'filter_language_id' => $filter_language_id,
			'filter_status'      => $filter_status,
			'sort'               => $sort,
			'order'              => $order,
			'start'              => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit'              => $this->config->get('config_pagination_admin')
		];

		// Image
		$this->load->model('tool/image');

		$this->load->model('catalog/manufacturer');

		$results = $this->model_catalog_manufacturer->getManufacturers($filter_data);

		foreach ($results as $result) {
			if ($result['image'] && is_file(DIR_IMAGE . html_entity_decode($result['image'], ENT_QUOTES, 'UTF-8'))) {
				$image = $result['image'];
			} else {
				$image = 'no_image.png';
			}

			$data['manufacturers'][] = [
				'image' => $this->model_tool_image->resize($image, 40, 40),
				'edit'  => $this->url->link('catalog/manufacturer.form', 'user_token=' . $this->session->data['user_token'] . '&manufacturer_id=' . $result['manufacturer_id'] . $url),
			] + $result;
		}

		$allowed = [
			'filter_name',
			'filter_store_id',
			'filter_language_id',
			'filter_status'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		// Sorts
		$data['sort_name'] = $this->url->link('catalog/manufacturer.list', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url);
		$data['sort_store'] = $this->url->link('catalog/manufacturer.list', 'user_token=' . $this->session->data['user_token'] . '&sort=store' . $url);
		$data['sort_status'] = $this->url->link('catalog/manufacturer.list', 'user_token=' . $this->session->data['user_token'] . '&sort=status' . $url);
		$data['sort_sort_order'] = $this->url->link('catalog/manufacturer.list', 'user_token=' . $this->session->data['user_token'] . '&sort=sort_order' . $url);

		$allowed = [
			'filter_name',
			'filter_store_id',
			'filter_language_id',
			'filter_status',
			'sort',
			'order'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		// Total Manufacturers
		$manufacturer_total = $this->model_catalog_manufacturer->getTotalManufacturers($filter_data);

		// Pagination
		$data['total'] = $manufacturer_total;
		$data['page'] = $page;
		$data['limit'] = $this->config->get('config_pagination_admin');
		$data['pagination'] = $this->url->link('catalog/manufacturer.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}');

		$data['results'] = sprintf($this->language->get('text_pagination'), ($manufacturer_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($manufacturer_total - $this->config->get('config_pagination_admin'))) ? $manufacturer_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $manufacturer_total, ceil($manufacturer_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('catalog/manufacturer_list', $data);
	}

	/**
	 * Form
	 *
	 * @return void
	 */
	public function form(): void {
		$this->load->language('catalog/manufacturer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->document->addScript('view/javascript/ckeditor/ckeditor.js');
		$this->document->addScript('view/javascript/ckeditor/adapters/jquery.js');

		$data['text_form'] = !isset($this->request->get['manufacturer_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$allowed = [
			'filter_name',
			'filter_store_id',
			'filter_language_id',
			'filter_status',
			'sort',
			'order',
			'page'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/manufacturer', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('catalog/manufacturer.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('catalog/manufacturer', 'user_token=' . $this->session->data['user_token'] . $url);

		// Manufacturer
		if (isset($this->request->get['manufacturer_id'])) {
			$this->load->model('catalog/manufacturer');

			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer((int)$this->request->get['manufacturer_id']);
		}

		if (!empty($manufacturer_info)) {
			$data['manufacturer_id'] = $manufacturer_info['manufacturer_id'];
		} else {
			$data['manufacturer_id'] = 0;
		}

		// Languages
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (!empty($manufacturer_info)) {
			$data['manufacturer_description'] = $this->model_catalog_manufacturer->getDescriptions($manufacturer_info['manufacturer_id']);
		} else {
			$data['manufacturer_description'] = [];
		}

		// Stores
		$data['stores'] = [];

		$data['stores'][] = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name')
		];

		$this->load->model('setting/store');

		$data['stores'] = array_merge($data['stores'], $this->model_setting_store->getStores());

		if (!empty($manufacturer_info)) {
			$data['manufacturer_store'] = $this->model_catalog_manufacturer->getStores($manufacturer_info['manufacturer_id']);
		} else {
			$data['manufacturer_store'] = [0];
		}

		// Image
		if (!empty($manufacturer_info)) {
			$data['image'] = $manufacturer_info['image'];
		} else {
			$data['image'] = '';
		}

		$this->load->model('tool/image');

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', $this->config->get('config_image_default_width'), $this->config->get('config_image_default_height'));

		if ($data['image'] && is_file(DIR_IMAGE . html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8'))) {
			$data['thumb'] = $this->model_tool_image->resize($data['image'], $this->config->get('config_image_default_width'), $this->config->get('config_image_default_height'));
		} else {
			$data['thumb'] = $data['placeholder'];
		}

		if (!empty($manufacturer_info)) {
			$data['status'] = $manufacturer_info['status'];
		} else {
			$data['status'] = true;
		}

		if (!empty($manufacturer_info)) {
			$data['sort_order'] = $manufacturer_info['sort_order'];
		} else {
			$data['sort_order'] = '';
		}

		// Languages
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		// SEO
		if (!empty($manufacturer_info)) {
			$this->load->model('design/seo_url');

			$data['manufacturer_seo_url'] = $this->model_design_seo_url->getSeoUrlsByKeyValue('manufacturer_id', $manufacturer_info['manufacturer_id']);
		} else {
			$data['manufacturer_seo_url'] = [];
		}

		// Layouts
		$this->load->model('design/layout');

		$data['layouts'] = $this->model_design_layout->getLayouts();

		if (!empty($manufacturer_info)) {
			$data['manufacturer_layout'] = $this->model_catalog_manufacturer->getLayouts($manufacturer_info['manufacturer_id']);
		} else {
			$data['manufacturer_layout'] = [];
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/manufacturer_form', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('catalog/manufacturer');

		$json = [];

		if (!$this->user->hasPermission('modify', 'catalog/manufacturer')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		$required = [
			'manufacturer_id'          => 0,
			'manufacturer_description' => [],
			'manufacturer_seo_url'     => []
		];

		$post_info = $this->request->post + $required;

		foreach ($post_info['manufacturer_description'] as $language_id => $value) {
			if (!oc_validate_length($value['name'], 1, 64)) {
				$json['error']['name_' . $language_id] = $this->language->get('error_name');
			}

			if (!oc_validate_length($value['meta_title'], 1, 255)) {
				$json['error']['meta_title_' . $language_id] = $this->language->get('error_meta_title');
			}
		}

		// SEO
		if ($post_info['manufacturer_seo_url']) {
			$this->load->model('design/seo_url');

			foreach ($post_info['manufacturer_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					if (!oc_validate_length($keyword, 1, 64)) {
						$json['error']['keyword_' . $store_id . '_' . $language_id] = $this->language->get('error_keyword');
					}

					if (!oc_validate_path($keyword)) {
						$json['error']['keyword_' . $store_id . '_' . $language_id] = $this->language->get('error_keyword_character');
					}

					$seo_url_info = $this->model_design_seo_url->getSeoUrlByKeyword($keyword, $store_id);

					if ($seo_url_info && ($seo_url_info['key'] != 'manufacturer_id' || !isset($post_info['manufacturer_id']) || $seo_url_info['value'] != (int)$post_info['manufacturer_id'])) {
						$json['error']['keyword_' . $store_id . '_' . $language_id] = $this->language->get('error_keyword_exists');
					}
				}
			}
		}

		if (isset($json['error']) && !isset($json['error']['warning'])) {
			$json['error']['warning'] = $this->language->get('error_warning');
		}

		if (!$json) {
			// Manufacturer
			$this->load->model('catalog/manufacturer');

			if (!$post_info['manufacturer_id']) {
				$json['manufacturer_id'] = $this->model_catalog_manufacturer->addManufacturer($post_info);
			} else {
				$this->model_catalog_manufacturer->editManufacturer($post_info['manufacturer_id'], $post_info);
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
		$this->load->language('catalog/manufacturer');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'catalog/manufacturer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// information
			$this->load->model('catalog/manufacturer');

			foreach ($selected as $manufacturer_id) {
				$this->model_catalog_manufacturer->editStatus((int)$manufacturer_id, true);
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
		$this->load->language('catalog/manufacturer');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'catalog/information')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// information
			$this->load->model('catalog/manufacturer');

			foreach ($selected as $manufacturer_id) {
				$this->model_catalog_manufacturer->editStatus((int)$manufacturer_id, false);
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
		$this->load->language('catalog/manufacturer');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'catalog/manufacturer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		// Product
		$this->load->model('catalog/product');

		foreach ($selected as $manufacturer_id) {
			// Total Products
			$product_total = $this->model_catalog_product->getTotalProductsByManufacturerId($manufacturer_id);

			if ($product_total) {
				$json['error'] = sprintf($this->language->get('error_product'), $product_total);
			}
		}

		if (!$json) {
			// Manufacturer
			$this->load->model('catalog/manufacturer');

			foreach ($selected as $manufacturer_id) {
				$this->model_catalog_manufacturer->deleteManufacturer($manufacturer_id);
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

		// Manufacturers
		if (isset($this->request->get['filter_name'])) {
			$this->load->model('catalog/manufacturer');

			$filter_data = [
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => $this->config->get('config_autocomplete_limit')
			];

			$results = $this->model_catalog_manufacturer->getManufacturers($filter_data);

			foreach ($results as $result) {
				$json[] = [
					'manufacturer_id' => $result['manufacturer_id'],
					'name'            => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
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
