<?php
namespace Opencart\Admin\Controller\User;
class Api extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('user/api');

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
			'href' => $this->url->link('user/api', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('user/api|form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('user/api|delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('user/api', $data));
	}

	public function list(): void {
		$this->load->language('user/api');

		$this->response->setOutput($this->getList());
	}

	protected function getList(): string {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'username';
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

		$data['action'] = $this->url->link('user/api|list', 'user_token=' . $this->session->data['user_token'] . $url);

		$data['apis'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('user/api');

		$user_total = $this->model_user_api->getTotalApis();

		$results = $this->model_user_api->getApis($filter_data);

		foreach ($results as $result) {
			$data['apis'][] = [
				'api_id'        => $result['api_id'],
				'username'      => $result['username'],
				'status'        => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
				'edit'          => $this->url->link('user/api|form', 'user_token=' . $this->session->data['user_token'] . '&api_id=' . $result['api_id'] . $url)
			];
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		$data['sort_username'] = $this->url->link('user/api|list', 'user_token=' . $this->session->data['user_token'] . '&sort=username' . $url);
		$data['sort_status'] = $this->url->link('user/api|list', 'user_token=' . $this->session->data['user_token'] . '&sort=status' . $url);
		$data['sort_date_added'] = $this->url->link('user/api|list', 'user_token=' . $this->session->data['user_token'] . '&sort=date_added' . $url);
		$data['sort_date_modified'] = $this->url->link('user/api|list', 'user_token=' . $this->session->data['user_token'] . '&sort=date_modified' . $url);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $user_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('user/api|list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($user_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($user_total - $this->config->get('config_pagination_admin'))) ? $user_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $user_total, ceil($user_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('user/api_list', $data);
	}

	public function form(): void {
		$this->load->language('user/api');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['api_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_ip'] = sprintf($this->language->get('text_ip'), $this->request->server['REMOTE_ADDR']);

		if (isset($this->request->get['api_id'])) {
			$data['api_id'] = $this->request->get['api_id'];
		} else {
			$data['api_id'] = 0;
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
			'href' => $this->url->link('user/api', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('user/api|save', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['back'] = $this->url->link('user/api', 'user_token=' . $this->session->data['user_token'] . $url);

		if (isset($this->request->get['api_id'])) {
			$this->load->model('user/api');

			$api_info = $this->model_user_api->getApi($this->request->get['api_id']);
		}

		if (isset($this->request->get['api_id'])) {
			$data['api_id'] = (int)$this->request->get['api_id'];
		} else {
			$data['api_id'] = 0;
		}

		if (!empty($api_info)) {
			$data['username'] = $api_info['username'];
		} else {
			$data['username'] = '';
		}

		if (!empty($api_info)) {
			$data['key'] = $api_info['key'];
		} else {
			$data['key'] = '';
		}

		if (!empty($api_info)) {
			$data['status'] = $api_info['status'];
		} else {
			$data['status'] = 0;
		}

		// IP
		if (!empty($api_info)) {
			$data['api_ips'] = $this->model_user_api->getIps($this->request->get['api_id']);
		} else {
			$data['api_ips'] = [];
		}

		// Session
		$data['api_sessions'] = [];

		if (!empty($api_info)) {
			$results = $this->model_user_api->getSessions($this->request->get['api_id']);

			foreach ($results as $result) {
				$data['api_sessions'][] = [
					'api_session_id' => $result['api_session_id'],
					'session_id'     => $result['session_id'],
					'ip'             => $result['ip'],
					'date_added'     => date($this->language->get('datetime_format'), strtotime($result['date_added'])),
					'date_modified'  => date($this->language->get('datetime_format'), strtotime($result['date_modified']))
				];
			}
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('user/api_form', $data));
	}

	public function save(): void {
		$this->load->language('user/api');

		$json = [];

		if (!$this->user->hasPermission('modify', 'user/api')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen(trim($this->request->post['username'])) < 3) || (utf8_strlen(trim($this->request->post['username'])) > 64)) {
			$json['error']['username'] = $this->language->get('error_username');
		}

		if ((utf8_strlen($this->request->post['key']) < 64) || (utf8_strlen($this->request->post['key']) > 256)) {
			$json['error']['key'] = $this->language->get('error_key');
		}

		if (!isset($json['error']['warning']) && !isset($this->request->post['api_ip'])) {
			$json['error']['warning'] = $this->language->get('error_ip');
		}

		if (!$json) {
			$this->load->model('user/api');

			if (!$this->request->post['api_id']) {
				$json['api_id'] = $this->model_user_api->addApi($this->request->post);
			} else {
				$this->model_user_api->editApi($this->request->post['api_id'], $this->request->post);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function delete(): void {
		$this->load->language('user/api');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'user/api')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('user/api');

			foreach ($selected as $api_id) {
				$this->model_user_api->deleteApi($api_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function deleteSession(): void {
		$this->load->language('user/api');

		$json = [];

		if (!$this->user->hasPermission('modify', 'user/api')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('user/api');

			$this->model_user_api->deleteSession($this->request->get['api_session_id']);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
