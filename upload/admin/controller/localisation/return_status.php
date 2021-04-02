<?php
namespace Opencart\Admin\Controller\Localisation;
class ReturnStatus extends \Opencart\System\Engine\Controller {
	private array $error = [];

	public function index(): void {
		$this->load->language('localisation/return_status');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/return_status');

		$this->getList();
	}

	public function add(): void {
		$this->load->language('localisation/return_status');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/return_status');

		$this->model_localisation_return_status->addReturnStatus($this->request->post);

		$this->getForm();
	}

	public function edit(): void {
		$this->load->language('localisation/return_status');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/return_status');

		$this->model_localisation_return_status->editReturnStatus($this->request->get['return_status_id'], $this->request->post);

		$this->getForm();
	}

	protected function getList(): void {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
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

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('localisation/return_status', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('localisation/return_status|add', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('localisation/return_status|delete', 'user_token=' . $this->session->data['user_token'] . $url);

		$data['return_statuses'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$return_status_total = $this->model_localisation_return_status->getTotalReturnStatuses();

		$results = $this->model_localisation_return_status->getReturnStatuses($filter_data);

		foreach ($results as $result) {
			$data['return_statuses'][] = [
				'return_status_id' => $result['return_status_id'],
				'name'             => $result['name'] . (($result['return_status_id'] == $this->config->get('config_return_status_id')) ? $this->language->get('text_default') : ''),
				'edit'             => $this->url->link('localisation/return_status|edit', 'user_token=' . $this->session->data['user_token'] . '&return_status_id=' . $result['return_status_id'] . $url)
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

		$data['sort_name'] = $this->url->link('localisation/return_status', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $return_status_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('localisation/return_status', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($return_status_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($return_status_total - $this->config->get('config_pagination_admin'))) ? $return_status_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $return_status_total, ceil($return_status_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/return_status_list', $data));
	}

	protected function getForm(): void {
		$data['text_form'] = !isset($this->request->get['return_status_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = [];
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
			'href' => $this->url->link('localisation/return_status', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		if (!isset($this->request->get['return_status_id'])) {
			$data['action'] = $this->url->link('localisation/return_status|add', 'user_token=' . $this->session->data['user_token'] . $url);
		} else {
			$data['action'] = $this->url->link('localisation/return_status|edit', 'user_token=' . $this->session->data['user_token'] . '&return_status_id=' . $this->request->get['return_status_id'] . $url);
		}

		$data['cancel'] = $this->url->link('localisation/return_status', 'user_token=' . $this->session->data['user_token'] . $url);

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['return_status'])) {
			$data['return_status'] = $this->request->post['return_status'];
		} elseif (isset($this->request->get['return_status_id'])) {
			$data['return_status'] = $this->model_localisation_return_status->getDescriptions($this->request->get['return_status_id']);
		} else {
			$data['return_status'] = [];
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/return_status_form', $data));
	}

	public function save(): void {
		if (!$this->user->hasPermission('modify', 'localisation/return_status')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['return_status'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 32)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function delete(): void {
		$this->load->language('localisation/return_status');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'localisation/return_status')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->load->model('sale/returns');

		foreach ($this->request->post['selected'] as $return_status_id) {
			if ($this->config->get('config_return_status_id') == $return_status_id) {
				$json['error'] = $this->language->get('error_default');
			}

			$return_total = $this->model_sale_returns->getTotalReturnsByReturnStatusId($return_status_id);

			if ($return_total) {
				$json['error'] = sprintf($this->language->get('error_return'), $return_total);
			}

			$return_total = $this->model_sale_returns->getTotalHistoriesByReturnStatusId($return_status_id);

			if ($return_total) {
				$json['error'] = sprintf($this->language->get('error_return'), $return_total);
			}
		}

		if (!$json) {
			$this->load->model('localisation/return_status');

			foreach ($selected as $return_status_id) {
				$this->model_localisation_return_status->deleteReturnStatus($return_status_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
