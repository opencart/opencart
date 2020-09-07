<?php
namespace Opencart\Application\Controller\Catalog;
class Filter extends \Opencart\System\Engine\Controller {
	private $error = [];

	public function index() {
		$this->load->language('catalog/filter');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/filter');

		$this->getList();
	}

	public function add() {
		$this->load->language('catalog/filter');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/filter');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_filter->addFilter($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

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

			$this->response->redirect($this->url->link('catalog/filter', 'user_token=' . $this->session->data['user_token'] . $url));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('catalog/filter');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/filter');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_filter->editFilter($this->request->get['filter_group_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

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

			$this->response->redirect($this->url->link('catalog/filter', 'user_token=' . $this->session->data['user_token'] . $url));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/filter');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/filter');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $filter_group_id) {
				$this->model_catalog_filter->deleteFilter($filter_group_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

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

			$this->response->redirect($this->url->link('catalog/filter', 'user_token=' . $this->session->data['user_token'] . $url));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'fgd.name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
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

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/filter', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('catalog/filter/add', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('catalog/filter/delete', 'user_token=' . $this->session->data['user_token'] . $url);

		$data['filters'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination'),
			'limit' => $this->config->get('config_pagination')
		];

		$filter_total = $this->model_catalog_filter->getTotalGroups();

		$results = $this->model_catalog_filter->getGroups($filter_data);

		foreach ($results as $result) {
			$data['filters'][] = [
				'filter_group_id' => $result['filter_group_id'],
				'name'            => $result['name'],
				'sort_order'      => $result['sort_order'],
				'edit'            => $this->url->link('catalog/filter/edit', 'user_token=' . $this->session->data['user_token'] . '&filter_group_id=' . $result['filter_group_id'] . $url)
			];
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = [];
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

		$data['sort_name'] = $this->url->link('catalog/filter', 'user_token=' . $this->session->data['user_token'] . '&sort=fgd.name' . $url);
		$data['sort_sort_order'] = $this->url->link('catalog/filter', 'user_token=' . $this->session->data['user_token'] . '&sort=fg.sort_order' . $url);

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
			'limit' => $this->config->get('config_pagination'),
			'url'   => $this->url->link('catalog/filter', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($filter_total) ? (($page - 1) * $this->config->get('config_pagination')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination')) > ($filter_total - $this->config->get('config_pagination'))) ? $filter_total : ((($page - 1) * $this->config->get('config_pagination')) + $this->config->get('config_pagination')), $filter_total, ceil($filter_total / $this->config->get('config_pagination')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/filter_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['filter_group_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['group'])) {
			$data['error_group'] = $this->error['group'];
		} else {
			$data['error_group'] = [];
		}

		if (isset($this->error['filter'])) {
			$data['error_filter'] = $this->error['filter'];
		} else {
			$data['error_filter'] = [];
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

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/filter', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		if (!isset($this->request->get['filter_group_id'])) {
			$data['action'] = $this->url->link('catalog/filter/add', 'user_token=' . $this->session->data['user_token'] . $url);
		} else {
			$data['action'] = $this->url->link('catalog/filter/edit', 'user_token=' . $this->session->data['user_token'] . '&filter_group_id=' . $this->request->get['filter_group_id'] . $url);
		}

		$data['cancel'] = $this->url->link('catalog/filter', 'user_token=' . $this->session->data['user_token'] . $url);

		if (isset($this->request->get['filter_group_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$filter_group_info = $this->model_catalog_filter->getGroup($this->request->get['filter_group_id']);
		}

		$data['user_token'] = $this->session->data['user_token'];

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['filter_group_description'])) {
			$data['filter_group_description'] = $this->request->post['filter_group_description'];
		} elseif (!empty($filter_group_info)) {
			$data['filter_group_description'] = $this->model_catalog_filter->getGroupDescriptions($this->request->get['filter_group_id']);
		} else {
			$data['filter_group_description'] = [];
		}

		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($filter_group_info)) {
			$data['sort_order'] = $filter_group_info['sort_order'];
		} else {
			$data['sort_order'] = '';
		}

		if (isset($this->request->post['filter'])) {
			$data['filters'] = $this->request->post['filter'];
		} elseif (!empty($filter_group_info)) {
			$data['filters'] = $this->model_catalog_filter->getDescriptions($this->request->get['filter_group_id']);
		} else {
			$data['filters'] = [];
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/filter_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/filter')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['filter_group_description'] as $language_id => $value) {
			if ((utf8_strlen(trim($value['name'])) < 1) || (utf8_strlen($value['name']) > 64)) {
				$this->error['group'][$language_id] = $this->language->get('error_group');
			}
		}

		if (isset($this->request->post['filter'])) {
			foreach ($this->request->post['filter'] as $key => $filter) {
				foreach ($filter['filter_description'] as $language_id => $filter_description) {
					if ((utf8_strlen(trim($filter_description['name'])) < 1) || (utf8_strlen($filter_description['name']) > 64)) {
						$this->error['filter'][$key][$language_id] = $this->language->get('error_name');
					}
				}
			}
		} else {
			$this->error['warning']  = $this->language->get('error_values');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/filter')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function autocomplete() {
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