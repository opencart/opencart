<?php
namespace Opencart\Admin\Controller\Catalog;
class Attribute extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('catalog/attribute');

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
			'href' => $this->url->link('catalog/attribute', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('catalog/attribute.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('catalog/attribute.delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/attribute', $data));
	}

	public function list(): void {
		$this->load->language('catalog/attribute');

		$this->response->setOutput($this->getList());
	}

	public function getList(): string {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'ad.name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
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

		$data['action'] = $this->url->link('catalog/attribute.list', 'user_token=' . $this->session->data['user_token'] . $url);

		$data['attributes'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('catalog/attribute');

		$attribute_total = $this->model_catalog_attribute->getTotalAttributes();

		$results = $this->model_catalog_attribute->getAttributes($filter_data);

		foreach ($results as $result) {
			$data['attributes'][] = [
				'attribute_id'    => $result['attribute_id'],
				'name'            => $result['name'],
				'attribute_group' => $result['attribute_group'],
				'sort_order'      => $result['sort_order'],
				'edit'            => $this->url->link('catalog/attribute.form', 'user_token=' . $this->session->data['user_token'] . '&attribute_id=' . $result['attribute_id'] . $url)
			];
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		$data['sort_name'] = $this->url->link('catalog/attribute.list', 'user_token=' . $this->session->data['user_token'] . '&sort=ad.name' . $url);
		$data['sort_attribute_group'] = $this->url->link('catalog/attribute.list', 'user_token=' . $this->session->data['user_token'] . '&sort=attribute_group' . $url);
		$data['sort_sort_order'] = $this->url->link('catalog/attribute.list', 'user_token=' . $this->session->data['user_token'] . '&sort=a.sort_order' . $url);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $attribute_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('catalog/attribute.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($attribute_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($attribute_total - $this->config->get('config_pagination_admin'))) ? $attribute_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $attribute_total, ceil($attribute_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('catalog/attribute_list', $data);
	}

	public function form(): void {
		$this->load->language('catalog/attribute');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['attribute_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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
			'href' => $this->url->link('catalog/attribute', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('catalog/attribute.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('catalog/attribute', 'user_token=' . $this->session->data['user_token'] . $url);

		if (isset($this->request->get['attribute_id'])) {
			$this->load->model('catalog/attribute');

			$attribute_info = $this->model_catalog_attribute->getAttribute($this->request->get['attribute_id']);
		}

		if (isset($this->request->get['attribute_id'])) {
			$data['attribute_id'] = (int)$this->request->get['attribute_id'];
		} else {
			$data['attribute_id'] = 0;
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->get['attribute_id'])) {
			$data['attribute_description'] = $this->model_catalog_attribute->getDescriptions($this->request->get['attribute_id']);
		} else {
			$data['attribute_description'] = [];
		}

		$this->load->model('catalog/attribute_group');

		$data['attribute_groups'] = $this->model_catalog_attribute_group->getAttributeGroups();

		if (!empty($attribute_info)) {
			$data['attribute_group_id'] = $attribute_info['attribute_group_id'];
		} else {
			$data['attribute_group_id'] = 0;
		}

		if (!empty($attribute_info)) {
			$data['sort_order'] = $attribute_info['sort_order'];
		} else {
			$data['sort_order'] = 0;
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/attribute_form', $data));
	}

	public function save(): void {
		$this->load->language('catalog/attribute');

		$json = [];

		if (!$this->user->hasPermission('modify', 'catalog/attribute')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['attribute_group_id']) {
			$json['error']['attribute_group'] = $this->language->get('error_attribute_group');
		}

		foreach ($this->request->post['attribute_description'] as $language_id => $value) {
			if ((oc_strlen(trim($value['name'])) < 1) || (oc_strlen($value['name']) > 64)) {
				$json['error']['name_' . $language_id] = $this->language->get('error_name');
			}
		}

		if (isset($json['error']) && !isset($json['error']['warning'])) {
			$json['error']['warning'] = $this->language->get('error_warning');
		}

		if (!$json) {
			$this->load->model('catalog/attribute');

			if (!$this->request->post['attribute_id']) {
				$json['attribute_id'] = $this->model_catalog_attribute->addAttribute($this->request->post);
			} else {
				$this->model_catalog_attribute->editAttribute($this->request->post['attribute_id'], $this->request->post);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function delete(): void {
		$this->load->language('catalog/attribute');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'catalog/attribute')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->load->model('catalog/product');

		foreach ($selected as $attribute_id) {
			$product_total = $this->model_catalog_product->getTotalProductsByAttributeId($attribute_id);

			if ($product_total) {
				$json['error'] = sprintf($this->language->get('error_product'), $product_total);
			}
		}

		if (!$json) {
			$this->load->model('catalog/attribute');

			foreach ($selected as $attribute_id) {
				$this->model_catalog_attribute->deleteAttribute($attribute_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function autocomplete(): void {
		$json = [];

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('catalog/attribute');

			$filter_data = [
                'filter_name' => $this->request->get['filter_name'],
                'start'       => 0,
                'limit'       => 5
            ];

			$results = $this->model_catalog_attribute->getAttributes($filter_data);

			foreach ($results as $result) {
				$json[] = [
					'attribute_id'    => $result['attribute_id'],
					'name'            => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'attribute_group' => $result['attribute_group']
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
