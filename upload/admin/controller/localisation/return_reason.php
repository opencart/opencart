<?php
namespace Opencart\Admin\Controller\Localisation;
class ReturnReason extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('localisation/return_reason');

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
			'href' => $this->url->link('localisation/return_reason', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('localisation/return_reason|form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('localisation/return_reason|delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/return_reason', $data));
	}

	public function list(): void {
		$this->load->language('localisation/return_reason');

		$this->response->setOutput($this->getList());
	}

	protected function getList(): string {
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

		$data['action'] = $this->url->link('localisation/return_reason|list', 'user_token=' . $this->session->data['user_token'] . $url);

		$data['return_reasons'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('localisation/return_reason');

		$return_reason_total = $this->model_localisation_return_reason->getTotalReturnReasons();

		$results = $this->model_localisation_return_reason->getReturnReasons($filter_data);

		foreach ($results as $result) {
			$data['return_reasons'][] = [
				'return_reason_id' => $result['return_reason_id'],
				'name'             => $result['name'],
				'edit'             => $this->url->link('localisation/return_reason|form', 'user_token=' . $this->session->data['user_token'] . '&return_reason_id=' . $result['return_reason_id'] . $url)
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

		$data['sort_name'] = $this->url->link('localisation/return_reason|list', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $return_reason_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('localisation/return_reason|list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($return_reason_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($return_reason_total - $this->config->get('config_pagination_admin'))) ? $return_reason_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $return_reason_total, ceil($return_reason_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('localisation/return_reason_list', $data);
	}

	public function form(): void {
		$this->load->language('localisation/return_reason');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['return_reason_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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
			'href' => $this->url->link('localisation/return_reason', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('localisation/return_reason|save', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['back'] = $this->url->link('localisation/return_reason', 'user_token=' . $this->session->data['user_token'] . $url);

		if (isset($this->request->get['return_reason_id'])) {
			$data['return_reason_id'] = (int)$this->request->get['return_reason_id'];
		} else {
			$data['return_reason_id'] = 0;
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->get['return_reason_id'])) {
			$this->load->model('localisation/return_reason');

			$data['return_reason'] = $this->model_localisation_return_reason->getDescriptions($this->request->get['return_reason_id']);
		} else {
			$data['return_reason'] = [];
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/return_reason_form', $data));
	}

	public function save(): void {
		$this->load->language('localisation/return_reason');

		$json = [];

		if (!$this->user->hasPermission('modify', 'localisation/return_reason')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['return_reason'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 128)) {
				$json['error']['name_' . $language_id] = $this->language->get('error_name');
			}
		}

		if (!$json) {
			$this->load->model('localisation/return_reason');

			if (!$this->request->post['return_reason_id']) {
				$json['return_reason_id'] = $this->model_localisation_return_reason->addReturnReason($this->request->post);
			} else {
				$this->model_localisation_return_reason->editReturnReason($this->request->post['return_reason_id'], $this->request->post);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function delete(): void {
		$this->load->language('localisation/return_reason');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'localisation/return_reason')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->load->model('sale/returns');

		foreach ($selected as $return_reason_id) {
			$return_total = $this->model_sale_returns->getTotalReturnsByReturnReasonId($return_reason_id);

			if ($return_total) {
				$json['error'] = sprintf($this->language->get('error_return'), $return_total);
			}
		}

		if (!$json) {
			$this->load->model('localisation/return_reason');

			foreach ($selected as $return_reason_id) {
				$this->model_localisation_return_reason->deleteReturnReason($return_reason_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
