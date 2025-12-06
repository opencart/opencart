<?php
namespace Opencart\Admin\Controller\User;
/**
 * Class User
 *
 * @package Opencart\Admin\Controller\User
 */
class User extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('user/user');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->document->addScript('assets/ckeditor/user.js');

		if (isset($this->request->get['filter_username'])) {
			$filter_username = (string)$this->request->get['filter_username'];
		} else {
			$filter_username = '';
		}

		if (isset($this->request->get['filter_name'])) {
			$filter_name = (string)$this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}

		if (isset($this->request->get['filter_email'])) {
			$filter_email = (string)$this->request->get['filter_email'];
		} else {
			$filter_email = '';
		}

		if (isset($this->request->get['filter_user_group_id'])) {
			$filter_user_group_id = (int)$this->request->get['filter_user_group_id'];
		} else {
			$filter_user_group_id = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = (bool)$this->request->get['filter_status'];
		} else {
			$filter_status = '';
		}

		if (isset($this->request->get['filter_ip'])) {
			$filter_ip = (string)$this->request->get['filter_ip'];
		} else {
			$filter_ip = '';
		}

		$allowed = [
			'filter_username',
			'filter_name',
			'filter_email',
			'filter_user_group_id',
			'filter_status',
			'filter_ip',
			'page'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

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
		$data['enable']	= $this->url->link('user/user.enable', 'user_token=' . $this->session->data['user_token']);
		$data['disable'] = $this->url->link('user/user.disable', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		// User Groups
		$this->load->model('user/user_group');

		$data['user_groups'] = $this->model_user_user_group->getUserGroups();

		$data['filter_username'] = $filter_name;
		$data['filter_name'] = $filter_name;
		$data['filter_email'] = $filter_email;
		$data['filter_user_group_id'] = $filter_user_group_id;
		$data['filter_status'] = $filter_status;
		$data['filter_ip'] = $filter_ip;

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('user/user', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('user/user');

		$this->response->setOutput($this->getList());
	}

	/**
	 * Get List
	 *
	 * @return string
	 */
	public function getList(): string {
		if (isset($this->request->get['filter_username'])) {
			$filter_username = $this->request->get['filter_username'];
		} else {
			$filter_username = '';
		}

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}

		if (isset($this->request->get['filter_email'])) {
			$filter_email = $this->request->get['filter_email'];
		} else {
			$filter_email = '';
		}

		if (isset($this->request->get['filter_user_group_id'])) {
			$filter_user_group_id = (int)$this->request->get['filter_user_group_id'];
		} else {
			$filter_user_group_id = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = (bool)$this->request->get['filter_status'];
		} else {
			$filter_status = '';
		}

		if (isset($this->request->get['filter_ip'])) {
			$filter_ip = (string)$this->request->get['filter_ip'];
		} else {
			$filter_ip = '';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$allowed = [
			'filter_username',
			'filter_name',
			'filter_email',
			'filter_user_group_id',
			'filter_status',
			'filter_ip',
			'page'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		$data['action'] = $this->url->link('user/user.list', 'user_token=' . $this->session->data['user_token'] . $url);

		// Users
		$data['users'] = [];

		$filter_data = [
			'filter_username'      => $filter_username,
			'filter_name'          => $filter_name,
			'filter_email'         => $filter_email,
			'filter_user_group_id' => $filter_user_group_id,
			'filter_status'        => $filter_status,
			'filter_ip'            => $filter_ip,
			'start'                => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit'                => $this->config->get('config_pagination_admin')
		];

		$this->load->model('user/user');

		$results = $this->model_user_user->getUsers($filter_data);

		foreach ($results as $result) {
			$data['users'][] = ['edit' => $this->url->link('user/user.form', 'user_token=' . $this->session->data['user_token'] . '&user_id=' . $result['user_id'] . $url)] + $result;
		}

		// Total Users
		$user_total = $this->model_user_user->getTotalUsers();

		// Pagination
		$data['total'] = $user_total;
		$data['page'] = $page;
		$data['limit'] = $this->config->get('config_pagination_admin');
		$data['pagination'] = $this->url->link('user/user.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}');

		$data['results'] = sprintf($this->language->get('text_pagination'), ($user_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($user_total - $this->config->get('config_pagination_admin'))) ? $user_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $user_total, ceil($user_total / $this->config->get('config_pagination_admin')));

		return $this->load->view('user/user_list', $data);
	}

	/**
	 * Form
	 *
	 * @return void
	 */
	public function form(): void {
		$this->load->language('user/user');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->document->addScript('assets/ckeditor/user.js');

		$data['text_form'] = !isset($this->request->get['user_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$allowed = [
			'filter_username',
			'filter_name',
			'filter_email',
			'filter_user_group_id',
			'filter_status',
			'filter_ip',
			'page'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('user/user', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('user/user.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('user/user', 'user_token=' . $this->session->data['user_token'] . $url);

		// User
		if (isset($this->request->get['user_id'])) {
			$this->load->model('user/user');

			$user_info = $this->model_user_user->getUser($this->request->get['user_id']);
		}

		if (!empty($user_info)) {
			$data['user_id'] = $user_info['user_id'];
		} else {
			$data['user_id'] = 0;
		}

		if (!empty($user_info)) {
			$data['username'] = $user_info['username'];
		} else {
			$data['username'] = '';
		}

		// User Groups
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

		// Image
		if (!empty($user_info)) {
			$data['image'] = $user_info['image'];
		} else {
			$data['image'] = '';
		}

		$this->load->model('tool/image');

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', $this->config->get('config_image_default_width'), $this->config->get('config_image_default_height'));

		if ($data['image'] && is_file(DIR_IMAGE . html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8'))) {
			$data['thumb'] = $this->model_tool_image->resize($data['image'], $this->config->get('config_image_default_width'), $this->config->get('config_image_default_height'));
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

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('user/user');

		$json = [];

		if (!$this->user->hasPermission('modify', 'user/user')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		$required = [
			'user_id'   => 0,
			'username'  => '',
			'firstname' => '',
			'lastname'  => '',
			'email'     => '',
			'password'  => '',
		];

		$post_info = $this->request->post + $required;

		if (!oc_validate_length($post_info['username'], 3, 20)) {
			$json['error']['username'] = $this->language->get('error_username');
		}

		// User
		$this->load->model('user/user');

		$user_info = $this->model_user_user->getUserByUsername($post_info['username']);

		if ($user_info && (!$post_info['user_id'] || ($post_info['user_id'] != $user_info['user_id']))) {
			$json['error']['warning'] = $this->language->get('error_username_exists');
		}

		if (!oc_validate_length($post_info['firstname'], 1, 32)) {
			$json['error']['firstname'] = $this->language->get('error_firstname');
		}

		if (!oc_validate_length($post_info['lastname'], 1, 32)) {
			$json['error']['lastname'] = $this->language->get('error_lastname');
		}

		if (!oc_validate_email($post_info['email'])) {
			$json['error']['email'] = $this->language->get('error_email');
		}

		$user_info = $this->model_user_user->getUserByEmail($post_info['email']);

		if ($user_info && (!$post_info['user_id'] || ($post_info['user_id'] != $user_info['user_id']))) {
			$json['error']['warning'] = $this->language->get('error_email_exists');
		}

		if ($post_info['password'] || (!isset($post_info['user_id']))) {
			$password = html_entity_decode($post_info['password'], ENT_QUOTES, 'UTF-8');

			if (!oc_validate_length($password, (int)$this->config->get('config_user_password_length'), 40)) {
				$json['error']['password'] = $this->language->get('error_password');
			}

			$required = [];

			if ($this->config->get('config_user_password_uppercase') && !preg_match('/[A-Z]/', $password)) {
				$required[] = $this->language->get('error_password_uppercase');
			}

			if ($this->config->get('config_user_password_lowercase') && !preg_match('/[a-z]/', $password)) {
				$required[] = $this->language->get('error_password_lowercase');
			}

			if ($this->config->get('config_user_password_number') && !preg_match('/[0-9]/', $password)) {
				$required[] = $this->language->get('error_password_number');
			}

			if ($this->config->get('config_user_password_symbol') && !preg_match('/[^a-zA-Z0-9]/', $password)) {
				$required[] = $this->language->get('error_password_symbol');
			}

			if ($required) {
				$json['error']['password'] = sprintf($this->language->get('error_password'), implode(', ', $required), (int)$this->config->get('config_user_password_length'));
			}

			if ($post_info['password'] != $post_info['confirm']) {
				$json['error']['confirm'] = $this->language->get('error_confirm');
			}
		}

		if (!$json) {
			if (!$post_info['user_id']) {
				$json['user_id'] = $this->model_user_user->addUser($post_info);
			} else {
				$this->model_user_user->editUser($post_info['user_id'], $post_info);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Enable
	 *
	 * @return void
	 */
	public function enable(): void {
		$this->load->language('user/user');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'user/user')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('user/user');

			foreach ($selected as $user_id) {
				$this->model_user_user->editStatus((int)$user_id, true);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Disable
	 *
	 * @return void
	 */
	public function disable(): void {
		$this->load->language('user/user');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'user/user')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('user/user');

			foreach ($selected as $user_id) {
				$this->model_user_user->editStatus((int)$user_id, false);
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
		$this->load->language('user/user');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
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
			// User
			$this->load->model('user/user');

			foreach ($selected as $user_id) {
				$this->model_user_user->deleteUser($user_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Authorize
	 *
	 * @return void
	 */
	public function authorize(): void {
		$this->load->language('user/user');

		$this->response->setOutput($this->getAuthorize());
	}

	/**
	 * Get Authorize
	 *
	 * @return string
	 */
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

		// Authorizes
		$data['authorizes'] = [];

		$this->load->model('user/user');

		$results = $this->model_user_user->getAuthorizes($user_id, ($page - 1) * $limit, $limit);

		foreach ($results as $result) {
			$data['authorizes'][] = [
				'status'      => $result['status'],
				'date_added'  => date($this->language->get('datetime_format'), strtotime($result['date_added'])),
				'date_expire' => $result['date_expire'] ? date($this->language->get('date_format_short'), strtotime($result['date_expire'])) : '',
				'delete'      => $this->url->link('user/user.deleteAuthorize', 'user_token=' . $this->session->data['user_token'] . '&user_authorize_id=' . $result['user_authorize_id'])
			] + $result;
		}

		// Total Authorizes
		$authorize_total = $this->model_user_user->getTotalAuthorizes($user_id);

		// Pagination
		$data['total'] = $authorize_total;
		$data['page'] = $page;
		$data['limit'] = $this->config->get('config_pagination_admin');
		$data['pagination'] = $this->url->link('user/user.authorize', 'user_token=' . $this->session->data['user_token'] . '&user_id=' . $user_id . '&page={page}');

		$data['results'] = sprintf($this->language->get('text_pagination'), ($authorize_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($authorize_total - $limit)) ? $authorize_total : ((($page - 1) * $limit) + $limit), $authorize_total, ceil($authorize_total / $limit));

		return $this->load->view('user/user_authorize', $data);
	}

	/**
	 * Delete Authorize
	 *
	 * @return void
	 */
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

		// User
		$this->load->model('user/user');

		$authorize_info = $this->model_user_user->getAuthorize($user_authorize_id);

		if (!$authorize_info) {
			$json['error'] = $this->language->get('error_authorize');
		}

		if (!$json) {
			$this->model_user_user->deleteAuthorizes($authorize_info['user_id'], $user_authorize_id);

			// If the token is still present, then we enforce the user to log out automatically.
			if ($authorize_info['token'] == $token) {
				$this->session->data['success'] = $this->language->get('text_success');

				$json['redirect'] = $this->url->link('common/login', '', true);
			} else {
				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Login
	 *
	 * @return void
	 */
	public function login(): void {
		$this->load->language('user/user');

		$this->response->setOutput($this->getLogin());
	}

	/**
	 * Get Login
	 *
	 * @return string
	 */
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

		// User
		$data['logins'] = [];

		$this->load->model('user/user');

		$results = $this->model_user_user->getLogins($user_id, ($page - 1) * $limit, $limit);

		foreach ($results as $result) {
			$data['logins'][] = ['date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added']))] + $result;
		}

		// Total Logins
		$login_total = $this->model_user_user->getTotalLogins($user_id);

		// Pagination
		$data['total'] = $login_total;
		$data['page'] = $page;
		$data['limit'] = $limit;
		$data['pagination'] = $this->url->link('user/user.login', 'user_token=' . $this->session->data['user_token'] . '&user_id=' . $user_id . '&page={page}');

		$data['results'] = sprintf($this->language->get('text_pagination'), ($login_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($login_total - $limit)) ? $login_total : ((($page - 1) * $limit) + $limit), $login_total, ceil($login_total / $limit));

		return $this->load->view('user/user_login', $data);
	}

	/**
	 * Autocomplete
	 *
	 * @return void
	 */
	public function autocomplete(): void {
		$json = [];

		if (isset($this->request->get['filter_username']) || isset($this->request->get['filter_name']) || isset($this->request->get['filter_email'])) {
			if (isset($this->request->get['filter_username'])) {
				$filter_username = $this->request->get['filter_username'];
			} else {
				$filter_username = '';
			}

			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			if (isset($this->request->get['filter_email'])) {
				$filter_email = $this->request->get['filter_email'];
			} else {
				$filter_email = '';
			}

			// User
			$filter_data = [
				'filter_username' => $filter_username,
				'filter_name'     => $filter_name,
				'filter_email'    => $filter_email,
				'start'           => 0,
				'limit'           => $this->config->get('config_autocomplete_limit')
			];

			$this->load->model('user/user');

			$results = $this->model_user_user->getUsers($filter_data);

			foreach ($results as $result) {
				$json[] = ['name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))] + $result;
			}
		}

		$sort_order = [];

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['username'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
