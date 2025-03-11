<?php
namespace Opencart\Admin\Controller\Catalog;
/**
 * Class Information
 *
 * Can be loaded using $this->load->controller('catalog/information');
 *
 * @package Opencart\Admin\Controller\Catalog
 */
class Information extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('catalog/information');

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
			'href' => $this->url->link('catalog/information', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('catalog/information.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('catalog/information.delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->load->controller('catalog/information.getList');

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/information', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('catalog/information');

		$this->response->setOutput($this->load->controller('catalog/information.getList'));
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
			$sort = 'id.title';
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

		$data['action'] = $this->url->link('catalog/information.list', 'user_token=' . $this->session->data['user_token'] . $url);

		// Information
		$data['informations'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('catalog/information');

		$results = $this->model_catalog_information->getInformations($filter_data);

		foreach ($results as $result) {
			$data['informations'][] = ['edit' => $this->url->link('catalog/information.form', 'user_token=' . $this->session->data['user_token'] . '&information_id=' . $result['information_id'] . $url)] + $result;
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		$data['sort_title'] = $this->url->link('catalog/information.list', 'user_token=' . $this->session->data['user_token'] . '&sort=id.title' . $url);
		$data['sort_sort_order'] = $this->url->link('catalog/information.list', 'user_token=' . $this->session->data['user_token'] . '&sort=i.sort_order' . $url);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$information_total = $this->model_catalog_information->getTotalInformations();

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $information_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('catalog/information.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($information_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($information_total - $this->config->get('config_pagination_admin'))) ? $information_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $information_total, ceil($information_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('catalog/information_list', $data);
	}

	/**
	 * Form
	 *
	 * @return void
	 */
	public function form(): void {
		$this->load->language('catalog/information');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->document->addScript('view/javascript/ckeditor/ckeditor.js');
		$this->document->addScript('view/javascript/ckeditor/adapters/jquery.js');

		$data['text_form'] = !isset($this->request->get['information_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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
			'href' => $this->url->link('catalog/information', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('catalog/information.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('catalog/information', 'user_token=' . $this->session->data['user_token'] . $url);

		if (isset($this->request->get['information_id'])) {
			$this->load->model('catalog/information');

			$information_info = $this->model_catalog_information->getInformation($this->request->get['information_id']);
		}

		if (isset($this->request->get['information_id'])) {
			$data['information_id'] = (int)$this->request->get['information_id'];
		} else {
			$data['information_id'] = 0;
		}

		// Language
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->get['information_id'])) {
			$data['information_description'] = $this->model_catalog_information->getDescriptions($this->request->get['information_id']);
		} else {
			$data['information_description'] = [];
		}

		// Store
		$data['stores'] = [];

		$data['stores'][] = [
			'store_id' => 0,
			'name'     => $this->language->get('text_default')
		];

		$this->load->model('setting/store');

		$results = $this->model_setting_store->getStores();

		foreach ($results as $result) {
			$data['stores'][] = $result;
		}

		if (isset($this->request->get['information_id'])) {
			$data['information_store'] = $this->model_catalog_information->getStores($this->request->get['information_id']);
		} else {
			$data['information_store'] = [0];
		}

		if (!empty($information_info)) {
			$data['status'] = $information_info['status'];
		} else {
			$data['status'] = true;
		}

		if (!empty($information_info)) {
			$data['sort_order'] = $information_info['sort_order'];
		} else {
			$data['sort_order'] = '';
		}

		if (isset($this->request->get['information_id'])) {
			$this->load->model('design/seo_url');

			$data['information_seo_url'] = $this->model_design_seo_url->getSeoUrlsByKeyValue('information_id', $this->request->get['information_id']);
		} else {
			$data['information_seo_url'] = [];
		}

		// Layout
		$this->load->model('design/layout');

		$data['layouts'] = $this->model_design_layout->getLayouts();

		if (isset($this->request->get['information_id'])) {
			$data['information_layout'] = $this->model_catalog_information->getLayouts($this->request->get['information_id']);
		} else {
			$data['information_layout'] = [];
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/information_form', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('catalog/information');

		$json = [];

		if (!$this->user->hasPermission('modify', 'catalog/information')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		$filter_data = [
			'information_id'          => 0,
			'sort_order'              => 0,
			'status'                  => 0,
			'information_description' => [],
			'information_store'       => [],
			'information_layout'      => [],
			'information_seo_url'     => []
		];

		$post_info = oc_filter_data($filter_data, $this->request->post);

		foreach ($post_info['information_description'] as $language_id => $value) {
			if (!oc_validate_length($value['title'], 1, 64)) {
				$json['error']['title_' . $language_id] = $this->language->get('error_title');
			}

			if (!oc_validate_length($value['meta_title'], 1, 255)) {
				$json['error']['meta_title_' . $language_id] = $this->language->get('error_meta_title');
			}
		}

		if ($post_info['information_seo_url']) {
			$this->load->model('design/seo_url');

			foreach ($post_info['information_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					if (!oc_validate_length($keyword, 1, 64)) {
						$json['error']['keyword_' . $store_id . '_' . $language_id] = $this->language->get('error_keyword');
					}

					if (!oc_validate_path($keyword)) {
						$json['error']['keyword_' . $store_id . '_' . $language_id] = $this->language->get('error_keyword_character');
					}

					$seo_url_info = $this->model_design_seo_url->getSeoUrlByKeyword($keyword, $store_id);

					if ($seo_url_info && (!isset($post_info['information_id']) || $seo_url_info['key'] != 'information_id' || $seo_url_info['value'] != (int)$post_info['information_id'])) {
						$json['error']['keyword_' . $store_id . '_' . $language_id] = $this->language->get('error_keyword_exists');
					}
				}
			}
		}

		if (isset($json['error']) && !isset($json['error']['warning'])) {
			$json['error']['warning'] = $this->language->get('error_warning');
		}

		if (!$json) {
			$this->load->model('catalog/information');

			if (!$post_info['information_id']) {
				$json['information_id'] = $this->model_catalog_information->addInformation($post_info);
			} else {
				$this->model_catalog_information->editInformation($post_info['information_id'], $post_info);
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
		$this->load->language('catalog/information');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'catalog/information')) {
			$json['error'] = $this->language->get('error_permission');
		}

		// Store
		$this->load->model('setting/store');

		foreach ($selected as $information_id) {
			if ($this->config->get('config_account_id') == $information_id) {
				$json['error'] = $this->language->get('error_account');
			}

			if ($this->config->get('config_checkout_id') == $information_id) {
				$json['error'] = $this->language->get('error_checkout');
			}

			if ($this->config->get('config_affiliate_id') == $information_id) {
				$json['error'] = $this->language->get('error_affiliate');
			}

			if ($this->config->get('config_return_id') == $information_id) {
				$json['error'] = $this->language->get('error_return');
			}

			$store_total = $this->model_setting_store->getTotalStoresByInformationId($information_id);

			if ($store_total) {
				$json['error'] = sprintf($this->language->get('error_store'), $store_total);
			}
		}

		if (!$json) {
			$this->load->model('catalog/information');

			foreach ($selected as $information_id) {
				$this->model_catalog_information->deleteInformation($information_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
