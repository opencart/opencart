<?php
namespace Opencart\Admin\Controller\User;
class User extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('user/user');

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
			'href' => $this->url->link('user/user', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('user/user.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('user/user.delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('user/user', $data));
	}

	public function list(): void {
		$this->load->language('user/user');

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

		$data['action'] = $this->url->link('user/user.list', 'user_token=' . $this->session->data['user_token'] . $url);

		$data['users'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('user/user');

		$user_total = $this->model_user_user->getTotalUsers();

		$results = $this->model_user_user->getUsers($filter_data);

		foreach ($results as $result) {
			$data['users'][] = [
				'user_id'    => $result['user_id'],
				'username'   => $result['username'],
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'edit'       => $this->url->link('user/user.form', 'user_token=' . $this->session->data['user_token'] . '&user_id=' . $result['user_id'] . $url)
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

		$data['sort_username'] = $this->url->link('user/user.list', 'user_token=' . $this->session->data['user_token'] . '&sort=username' . $url);
		$data['sort_status'] = $this->url->link('user/user.list', 'user_token=' . $this->session->data['user_token'] . '&sort=status' . $url);
		$data['sort_date_added'] = $this->url->link('user/user.list', 'user_token=' . $this->session->data['user_token'] . '&sort=date_added' . $url);

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
			'url'   => $this->url->link('user/user.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($user_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($user_total - $this->config->get('config_pagination_admin'))) ? $user_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $user_total, ceil($user_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('user/user_list', $data);
	}

	public function form(): void {
		$this->load->language('user/user');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['user_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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

		$data['save'] = $this->url->link('user/user.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('user/user', 'user_token=' . $this->session->data['user_token'] . $url);

		if (isset($this->request->get['user_id'])) {
			$this->load->model('user/user');

			$user_info = $this->model_user_user->getUser($this->request->get['user_id']);
		}

		if (isset($this->request->get['user_id'])) {
			$data['user_id'] = (int)$this->request->get['user_id'];
		} else {
			$data['user_id'] = 0;
		}

		if (!empty($user_info)) {
			$data['username'] = $user_info['username'];
		} else {
			$data['username'] = '';
		}

		$this->load->model('user/user_group');

		$data['user_groups'] = $this->model_user_user_group->getUserGroups();

		if (!empty($user_info)) {
			$data['user_group_id'] = $user_info['user_group_id'];
		} else {
			$data['user_group_id'] = 0;
		}

		if (!empty($user_info)) {
			$data['firstname'] = $user_info['firstname'];
		} else {
			$data['firstname'] = '';
		}

		if (!empty($user_info)) {
			$data['lastname'] = $user_info['lastname'];
		} else {
			$data['lastname'] = '';
		}

		if (!empty($user_info)) {
			$data['email'] = $user_info['email'];
		} else {
			$data['email'] = '';
		}

		if (!empty($user_info)) {
			$data['image'] = $user_info['image'];
		} else {
			$data['image'] = '';
		}

		$this->load->model('tool/image');

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if (is_file(DIR_IMAGE . html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8'))) {
			$data['thumb'] = $this->model_tool_image->resize(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8'), 100, 100);
		} else {
			$data['thumb'] = $data['placeholder'];
		}

		if (!empty($user_info)) {
			$data['status'] = $user_info['status'];
		} else {
			$data['status'] = 0;
		}

		$data['authorize'] = $this->getAuthorize();
		$data['login'] = $this->getLogin();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('user/user_form', $data));
	}

	public function save(): void {
		$this->load->language('user/user');

		$json = [];

		if (!$this->user->hasPermission('modify', 'user/user')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		if ((oc_strlen($this->request->post['username']) < 3) || (oc_strlen($this->request->post['username']) > 20)) {
			$json['error']['username'] = $this->language->get('error_username');
		}

		$this->load->model('user/user');

		$user_info = $this->model_user_user->getUserByUsername($this->request->post['username']);

		if (!$this->request->post['user_id']) {
			if ($user_info) {
				$json['error']['warning'] = $this->language->get('error_username_exists');
			}
		} else {
			if ($user_info && ($this->request->post['user_id'] != $user_info['user_id'])) {
				$json['error']['warning'] = $this->language->get('error_username_exists');
			}
		}

		if ((oc_strlen($this->request->post['firstname']) < 1) || (oc_strlen($this->request->post['firstname']) > 32)) {
			$json['error']['firstname'] = $this->language->get('error_firstname');
		}

		if ((oc_strlen($this->request->post['lastname']) < 1) || (oc_strlen($this->request->post['lastname']) > 32)) {
			$json['error']['lastname'] = $this->language->get('error_lastname');
		}

		if ((oc_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
			$json['error']['email'] = $this->language->get('error_email');
		}

		$user_info = $this->model_user_user->getUserByEmail($this->request->post['email']);

		if (!$this->request->post['user_id']) {
			if ($user_info) {
				$json['error']['warning'] = $this->language->get('error_email_exists');
			}
		} else {
			if ($user_info && ($this->request->post['user_id'] != $user_info['user_id'])) {
				$json['error']['warning'] = $this->language->get('error_email_exists');
			}
		}

		if ($this->request->post['password'] || (!isset($this->request->post['user_id']))) {
			if ((oc_strlen(html_entity_decode($this->request->post['password'], ENT_QUOTES, 'UTF-8')) < 4) || (oc_strlen(html_entity_decode($this->request->post['password'], ENT_QUOTES, 'UTF-8')) > 40)) {
				$json['error']['password'] = $this->language->get('error_password');
			}

			if ($this->request->post['password'] != $this->request->post['confirm']) {
				$json['error']['confirm'] = $this->language->get('error_confirm');
			}
		}

		if (!$json) {
			if (!$this->request->post['user_id']) {
				$json['user_id'] = $this->model_user_user->addUser($this->request->post);
			} else {
				$this->model_user_user->editUser($this->request->post['user_id'], $this->request->post);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function delete(): void {
		$this->load->language('user/user');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'user/user')) {
			$json['error'] = $this->language->get('error_permission');
		}

		foreach ($selected as $user_id) {
			if ($this->user->getId() == $user_id) {
				$json['error']['warning'] = $this->language->get('error_account');
			}
		}

		if (!$json) {
			$this->load->model('user/user');

			foreach ($selected as $user_id) {
				$this->model_user_user->deleteUser($user_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function authorize(): void {
		$this->load->language('user/user');

		$this->response->setOutput($this->getAuthorize());
	}

	public function getAuthorize(): string {
		if (isset($this->request->get['user_id'])) {
			$user_id = (int)$this->request->get['user_id'];
		} else {
			$user_id = 0;
		}

		if (isset($this->request->get['page']) && $this->request->get['route'] == 'user/user.login') {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = 10;

		$data['authorizes'] = [];

		$this->load->model('user/user');

		$results = $this->model_user_user->getAuthorizes($user_id, ($page - 1) * $limit, $limit);

		foreach ($results as $result) {
			$data['authorizes'][] = [
				'token'      => $result['token'],
				'ip'         => $result['ip'],
				'user_agent' => $result['user_agent'],
				'status'     => $result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				'total'      => $result['total'],
				'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added'])),
				'delete'     => $this->url->link('user/user.deleteAuthorize', 'user_token=' . $this->session->data['user_token'] . '&user_authorize_id=' . $result['user_authorize_id'])
			];
		}

		$authorize_total = $this->model_user_user->getTotalAuthorizes($user_id);

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $authorize_total,
			'page'  => $page,
			'limit' => $limit,
			'url'   => $this->url->link('user/user.authorize', 'user_token=' . $this->session->data['user_token'] . '&user_id=' . $user_id . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($authorize_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($authorize_total - $limit)) ? $authorize_total : ((($page - 1) * $limit) + $limit), $authorize_total, ceil($authorize_total / $limit));

		return $this->load->view('user/user_authorize', $data);
	}

	public function deleteAuthorize(): void {
		$this->load->language('user/user');

		$json = [];

		if (isset($this->request->get['user_authorize_id'])) {
			$user_authorize_id = (int)$this->request->get['user_authorize_id'];
		} else {
			$user_authorize_id = 0;
		}

		if (isset($this->request->cookie['authorize'])) {
			$token = $this->request->cookie['authorize'];
		} else {
			$token = '';
		}

		if (!$this->user->hasPermission('modify', 'user/user')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->load->model('user/user');

		$login_info = $this->model_user_user->getAuthorize($user_authorize_id);

		if (!$login_info) {
			$json['error'] = $this->language->get('error_authorize');
		}

		if (!$json) {
			$this->model_user_user->deleteAuthorize($user_authorize_id);

			// If the token is still present, then we enforce the user to log out automatically.
			if ($login_info['token'] == $token) {
				$this->session->data['success'] = $this->language->get('text_success');

				$json['redirect'] = $this->url->link('common/login', '', true);
			} else {
				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function login(): void {
		$this->load->language('user/user');

		$this->response->setOutput($this->getLogin());
	}

	public function getLogin(): string {
		if (isset($this->request->get['user_id'])) {
			$user_id = (int)$this->request->get['user_id'];
		} else {
			$user_id = 0;
		}

		if (isset($this->request->get['page']) && $this->request->get['route'] == 'user/user.login') {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = 10;

		$data['logins'] = [];

		$this->load->model('user/user');

		$results = $this->model_user_user->getLogins($user_id, ($page - 1) * $limit, $limit);

		foreach ($results as $result) {
			$data['logins'][] = [
				'ip'         => $result['ip'],
				'user_agent' => $result['user_agent'],
				'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added']))
			];
		}

		$login_total = $this->model_user_user->getTotalLogins($user_id);

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $login_total,
			'page'  => $page,
			'limit' => $limit,
			'url'   => $this->url->link('user/user.login', 'user_token=' . $this->session->data['user_token'] . '&user_id=' . $user_id . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($login_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($login_total - $limit)) ? $login_total : ((($page - 1) * $limit) + $limit), $login_total, ceil($login_total / $limit));

		return $this->load->view('user/user_login', $data);
	}
}
