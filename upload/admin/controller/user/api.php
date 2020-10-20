<?php
class ControllerUserApi extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('user/api');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('user/api');

		$this->getList();
	}

	public function add() {
		$this->load->language('user/api');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('user/api');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_user_api->addApi($this->request->post);

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

			$this->response->redirect($this->url->link('user/api', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('user/api');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('user/api');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_user_api->editApi($this->request->get['api_id'], $this->request->post);

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

			$this->response->redirect($this->url->link('user/api', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('user/api');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('user/api');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $api_id) {
				$this->model_user_api->deleteApi($api_id);
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

			$this->response->redirect($this->url->link('user/api', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
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

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('user/api', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['add'] = $this->url->link('user/api/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['delete'] = $this->url->link('user/api/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$data['apis'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$user_total = $this->model_user_api->getTotalApis();

		$results = $this->model_user_api->getApis($filter_data);

		foreach ($results as $result) {
			$data['apis'][] = array(
				'api_id'        => $result['api_id'],
				'username'      => $result['username'],
				'status'        => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
				'edit'          => $this->url->link('user/api/edit', 'user_token=' . $this->session->data['user_token'] . '&api_id=' . $result['api_id'] . $url, true)
			);
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
			$data['selected'] = array();
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

		$data['sort_username'] = $this->url->link('user/api', 'user_token=' . $this->session->data['user_token'] . '&sort=username' . $url, true);
		$data['sort_status'] = $this->url->link('user/api', 'user_token=' . $this->session->data['user_token'] . '&sort=status' . $url, true);
		$data['sort_date_added'] = $this->url->link('user/api', 'user_token=' . $this->session->data['user_token'] . '&sort=date_added' . $url, true);
		$data['sort_date_modified'] = $this->url->link('user/api', 'user_token=' . $this->session->data['user_token'] . '&sort=date_modified' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $user_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('user/api', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($user_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($user_total - $this->config->get('config_limit_admin'))) ? $user_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $user_total, ceil($user_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('user/api_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['api_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_ip'] = sprintf($this->language->get('text_ip'), $this->request->server['REMOTE_ADDR']);
		
		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['username'])) {
			$data['error_username'] = $this->error['username'];
		} else {
			$data['error_username'] = '';
		}

		if (isset($this->error['key'])) {
			$data['error_key'] = $this->error['key'];
		} else {
			$data['error_key'] = '';
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

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('user/api', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		if (!isset($this->request->get['api_id'])) {
			$data['action'] = $this->url->link('user/api/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('user/api/edit', 'user_token=' . $this->session->data['user_token'] . '&api_id=' . $this->request->get['api_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('user/api', 'user_token=' . $this->session->data['user_token'] . $url, true);

		if (isset($this->request->get['api_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$api_info = $this->model_user_api->getApi($this->request->get['api_id']);
		}

		if (isset($this->request->post['username'])) {
			$data['username'] = $this->request->post['username'];
		} elseif (!empty($api_info)) {
			$data['username'] = $api_info['username'];
		} else {
			$data['username'] = '';
		}

		if (isset($this->request->post['key'])) {
			$data['key'] = $this->request->post['key'];
		} elseif (!empty($api_info)) {
			$data['key'] = $api_info['key'];
		} else {
			$data['key'] = '';
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($api_info)) {
			$data['status'] = $api_info['status'];
		} else {
			$data['status'] = 0;
		}

		// IP
		if (isset($this->request->post['api_ip'])) {
			$data['api_ips'] = $this->request->post['api_ip'];
		} elseif (isset($this->request->get['api_id'])) {
			$data['api_ips'] = $this->model_user_api->getApiIps($this->request->get['api_id']);
		} else {
			$data['api_ips'] = array();
		}
		
		// Session
		$data['api_sessions'] = array();
		
		if (isset($this->request->get['api_id'])) {
			$results = $this->model_user_api->getApiSessions($this->request->get['api_id']);
			
			foreach ($results as $result) {
				$data['api_sessions'][] = array(
					'api_session_id' => $result['api_session_id'],
					'session_id'     => $result['session_id'],
					'ip'             => $result['ip'],
					'date_added'     => date($this->language->get('datetime_format'), strtotime($result['date_added'])),
					'date_modified'  => date($this->language->get('datetime_format'), strtotime($result['date_modified']))
				);
			}
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('user/api_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'user/user')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen(trim($this->request->post['username'])) < 3) || (utf8_strlen(trim($this->request->post['username'])) > 64)) {
			$this->error['username'] = $this->language->get('error_username');
		}

		if ((utf8_strlen($this->request->post['key']) < 64) || (utf8_strlen($this->request->post['key']) > 256)) {
			$this->error['key'] = $this->language->get('error_key');
		}
		
		if (!isset($this->error['warning']) && !isset($this->request->post['api_ip'])) {
			$this->error['warning'] = $this->language->get('error_ip');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'user/api')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function deleteSession() {
		$this->load->language('user/api');

		$json = array();

		if (!$this->user->hasPermission('modify', 'user/api')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('user/api');

			$this->model_user_api->deleteApiSession($this->request->get['api_session_id']);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}	
}
