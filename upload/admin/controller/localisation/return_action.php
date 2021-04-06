<?php
namespace Opencart\Admin\Controller\Localisation;
class ReturnAction extends \Opencart\System\Engine\Controller {

	public function index(): void {
		$this->load->language('localisation/return_action');

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
			'href' => $this->url->link('localisation/return_action', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('localisation/return_action|form', 'user_token=' . $this->session->data['user_token'] . $url);

		$data['user_token'] = $this->session->data['user_token'];

		$data['list'] = $this->getList();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/return_action', $data));
	}

	public function list(): void {
		$this->load->language('localisation/return_action');

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

		$data['return_actions'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('localisation/return_action');

		$return_action_total = $this->model_localisation_return_action->getTotalReturnActions();

		$results = $this->model_localisation_return_action->getReturnActions($filter_data);

		foreach ($results as $result) {
			$data['return_actions'][] = [
				'return_action_id' => $result['return_action_id'],
				'name'             => $result['name'],
				'edit'             => $this->url->link('localisation/return_action|form', 'user_token=' . $this->session->data['user_token'] . '&return_action_id=' . $result['return_action_id'] . $url)
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

		$data['sort_name'] = $this->url->link('localisation/return_action|list', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $return_action_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('localisation/return_action|list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($return_action_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($return_action_total - $this->config->get('config_pagination_admin'))) ? $return_action_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $return_action_total, ceil($return_action_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('localisation/return_action_list', $data);
	}

	protected function getForm(): void {
		$data['text_form'] = !isset($this->request->get['return_action_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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
			'href' => $this->url->link('localisation/return_action', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['back'] = $this->url->link('localisation/return_action', 'user_token=' . $this->session->data['user_token'] . $url);

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->get['return_action_id'])) {
			$data['return_action'] = $this->model_localisation_return_action->getDescriptions($this->request->get['return_action_id']);
		} else {
			$data['return_action'] = [];
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/return_action_form', $data));
	}

	public function save(): void {
		$this->load->language('localisation/return_action');

		$json = [];

		if (!$this->user->hasPermission('modify', 'localisation/return_action')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['return_action'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 64)) {
				$json['error']['name'][$language_id] = $this->language->get('error_name');
			}
		}

		$this->load->model('localisation/return_action');

		$this->model_localisation_return_action->addReturnAction($this->request->post);
		$this->model_localisation_return_action->editReturnAction($this->request->get['return_action_id'], $this->request->post);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function delete(): void {
		$this->load->language('localisation/return_action');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'localisation/return_action')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->load->model('sale/returns');

		foreach ($selected as $return_action_id) {
			$return_total = $this->model_sale_returns->getTotalReturnsByReturnActionId($return_action_id);

			if ($return_total) {
				$json['error'] = sprintf($this->language->get('error_return'), $return_total);
			}
		}

		if (!$json) {
			$this->load->model('localisation/return_action');

			foreach ($selected as $return_action_id) {
				$this->model_localisation_return_action->deleteReturnAction($return_action_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
