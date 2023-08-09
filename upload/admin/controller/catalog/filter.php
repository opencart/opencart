<?php
namespace Opencart\Admin\Controller\Catalog;
/**
 * Class Filter
 *
 * @package Opencart\Admin\Controller\Catalog
 */
class Filter extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
	public function index(): void {
		$this->load->language('catalog/filter');

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
			'href' => $this->url->link('catalog/filter', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('catalog/filter.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('catalog/filter.delete', 'user_token=' . $this->session->data['user_token']);

		$data['user_token'] = $this->session->data['user_token'];

		$data['list'] = $this->getList();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/filter', $data));
	}

	/**
	 * @return void
	 */
	public function list(): void {
		$this->load->language('catalog/filter');

		$this->response->setOutput($this->getList());
	}

	/**
	 * @return string
	 */
	protected function getList(): string {
		if (isset($this->request->get['sort'])) {
			$sort = (string)$this->request->get['sort'];
		} else {
			$sort = 'fgd.name';
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

		$data['action'] = $this->url->link('catalog/filter.list', 'user_token=' . $this->session->data['user_token'] . $url);

		$data['filters'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('catalog/filter');

		$filter_total = $this->model_catalog_filter->getTotalGroups();

		$results = $this->model_catalog_filter->getGroups($filter_data);

		foreach ($results as $result) {
			$data['filters'][] = [
				'filter_group_id' => $result['filter_group_id'],
				'name'            => $result['name'],
				'sort_order'      => $result['sort_order'],
				'edit'            => $this->url->link('catalog/filter.form', 'user_token=' . $this->session->data['user_token'] . '&filter_group_id=' . $result['filter_group_id'] . $url)
			];
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('catalog/filter.list', 'user_token=' . $this->session->data['user_token'] . '&sort=fgd.name' . $url);
		$data['sort_sort_order'] = $this->url->link('catalog/filter.list', 'user_token=' . $this->session->data['user_token'] . '&sort=fg.sort_order' . $url);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $filter_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('catalog/filter.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($filter_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($filter_total - $this->config->get('config_pagination_admin'))) ? $filter_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $filter_total, ceil($filter_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('catalog/filter_list', $data);
	}

	/**
	 * @return void
	 */
	public function form(): void {
		$this->load->language('catalog/filter');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['filter_group_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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
			'href' => $this->url->link('catalog/filter', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('catalog/filter.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('catalog/filter', 'user_token=' . $this->session->data['user_token'] . $url);

		if (isset($this->request->get['filter_group_id'])) {
			$this->load->model('catalog/filter');

			$filter_group_info = $this->model_catalog_filter->getGroup($this->request->get['filter_group_id']);
		}

		if (isset($this->request->get['filter_group_id'])) {
			$data['filter_group_id'] = (int)$this->request->get['filter_group_id'];
		} else {
			$data['filter_group_id'] = 0;
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->get['filter_group_id'])) {
			$data['filter_group_description'] = $this->model_catalog_filter->getGroupDescriptions($this->request->get['filter_group_id']);
		} else {
			$data['filter_group_description'] = [];
		}

		if (!empty($filter_group_info)) {
			$data['sort_order'] = $filter_group_info['sort_order'];
		} else {
			$data['sort_order'] = '';
		}

		if (!empty($filter_group_info)) {
			$data['filters'] = $this->model_catalog_filter->getDescriptions($this->request->get['filter_group_id']);
		} else {
			$data['filters'] = [];
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/filter_form', $data));
	}

	/**
	 * @return void
	 */
	public function save(): void {
		$this->load->language('catalog/filter');

		$json = [];

		if (!$this->user->hasPermission('modify', 'catalog/filter')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['filter_group_description'] as $language_id => $value) {
			if ((oc_strlen(trim($value['name'])) < 1) || (oc_strlen($value['name']) > 64)) {
				$json['error']['group_' . $language_id] = $this->language->get('error_group');
			}
		}

		if (isset($this->request->post['filter'])) {
			foreach ($this->request->post['filter'] as $key => $filter) {
				foreach ($filter['filter_description'] as $language_id => $filter_description) {
					if ((oc_strlen(trim($filter_description['name'])) < 1) || (oc_strlen($filter_description['name']) > 64)) {
						$json['error']['filter_' . $key . '_' . $language_id] = $this->language->get('error_name');
					}
				}
			}
		} else {
			$json['error']['warning']  = $this->language->get('error_values');
		}

		if (isset($json['error']) && !isset($json['error']['warning'])) {
			$json['error']['warning'] = $this->language->get('error_warning');
		}

		if (!$json) {
			$this->load->model('catalog/filter');

			if (!$this->request->post['filter_group_id']) {
				$json['filter_group_id'] = $this->model_catalog_filter->addFilter($this->request->post);
			} else {
				$this->model_catalog_filter->editFilter($this->request->post['filter_group_id'], $this->request->post);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * @return void
	 */
	public function delete(): void {
		$this->load->language('catalog/filter');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'catalog/filter')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('catalog/filter');

			foreach ($selected as $filter_id) {
				$this->model_catalog_filter->deleteFilter($filter_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * @return void
	 */
	public function autocomplete(): void {
		$json = [];

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('catalog/filter');

			$filter_data = [
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 5
			];

			$filters = $this->model_catalog_filter->getFilters($filter_data);

			foreach ($filters as $filter) {
				$json[] = [
					'filter_id' => $filter['filter_id'],
					'name'      => strip_tags(html_entity_decode($filter['group'] . ' &gt; ' . $filter['name'], ENT_QUOTES, 'UTF-8'))
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
