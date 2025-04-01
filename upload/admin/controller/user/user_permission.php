<?php
namespace Opencart\Admin\Controller\User;
/**
 * Class User Permission
 *
 * @package Opencart\Admin\Controller\User
 */
class UserPermission extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('user/user_group');

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
			'href' => $this->url->link('user/user_permission', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('user/user_permission.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('user/user_permission.delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('user/user_group', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('user/user_group');

		$this->response->setOutput($this->getList());
	}

	/**
	 * Get List
	 *
	 * @return string
	 */
	public function getList(): string {
		if (isset($this->request->get['sort'])) {
			$sort = (string)$this->request->get['sort'];
		} else {
			$sort = 'name';
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

		$data['action'] = $this->url->link('user/user_permission.list', 'user_token=' . $this->session->data['user_token'] . $url);

		// User Groups
		$data['user_groups'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('user/user_group');

		$results = $this->model_user_user_group->getUserGroups($filter_data);

		foreach ($results as $result) {
			$data['user_groups'][] = ['edit' => $this->url->link('user/user_permission.form', 'user_token=' . $this->session->data['user_token'] . '&user_group_id=' . $result['user_group_id'] . $url)] + $result;
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		// Sort
		$data['sort_name'] = $this->url->link('user/user_permission.list', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		// Total User Groups
		$user_group_total = $this->model_user_user_group->getTotalUserGroups();

		// Pagination
		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $user_group_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('user/user_permission.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($user_group_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($user_group_total - $this->config->get('config_pagination_admin'))) ? $user_group_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $user_group_total, ceil($user_group_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('user/user_group_list', $data);
	}

	/**
	 * Form
	 *
	 * @return void
	 */
	public function form(): void {
		$this->load->language('user/user_group');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['user_group_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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
			'href' => $this->url->link('user/user_permission', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('user/user_permission.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('user/user_permission', 'user_token=' . $this->session->data['user_token'] . $url);

		// User Group
		if (isset($this->request->get['user_group_id'])) {
			$this->load->model('user/user_group');

			$user_group_info = $this->model_user_user_group->getUserGroup((int)$this->request->get['user_group_id']);
		}

		if (!empty($user_group_info)) {
			$data['user_group_id'] = $user_group_info['user_group_id'];
		} else {
			$data['user_group_id'] = 0;
		}

		if (!empty($user_group_info)) {
			$data['name'] = $user_group_info['name'];
		} else {
			$data['name'] = '';
		}

		// Routes to ignore
		$ignore = [
			'common/dashboard',
			'common/startup',
			'common/login',
			'common/logout',
			'common/forgotten',
			'common/authorize',
			'common/footer',
			'common/header',
			'common/column_left',
			'common/language',
			'common/pagination',
			'error/not_found',
			'error/permission',
			'event/currency',
			'event/debug',
			'event/language',
			'event/statistics',
			'startup/application',
			'startup/authorize',
			'startup/error',
			'startup/event',
			'startup/extension',
			'startup/language',
			'startup/login',
			'startup/notification',
			'startup/permission',
			'startup/sass',
			'startup/session',
			'startup/setting',
			'startup/startup'
		];

		$files = [];

		// Make path into an array
		$path = [DIR_APPLICATION . 'controller/*'];

		// While the path array is still populated keep looping through
		while (count($path) != 0) {
			$next = array_shift($path);

			foreach (glob($next . '/*') as $file) {
				// If directory add to path array
				if (is_dir($file)) {
					$path[] = $file;
				}

				// Add the file to the files to be deleted array
				if (is_file($file) && substr($file, strrpos($file, '.')) == '.php') {
					$files[] = $file;
				}
			}
		}

		// Sort the file array
		sort($files);

		$data['permissions'] = [];

		foreach ($files as $file) {
			$controller = substr($file, strlen(DIR_APPLICATION . 'controller/'));

			$permission = substr($controller, 0, strrpos($controller, '.'));

			if (!in_array($permission, $ignore)) {
				$data['permissions'][] = $permission;
			}
		}

		$data['extensions'] = [];

		// Extension permissions
		$results = glob(DIR_EXTENSION . '*/admin/controller/{,*/,*/*/,*/*/*/}*.php', GLOB_BRACE);

		foreach ($results as $result) {
			$path = substr($result, strlen(DIR_EXTENSION));

			$data['extensions'][] = 'extension/' . str_replace('admin/controller/', '', substr($path, 0, strrpos($path, '.')));
		}

		if (isset($user_group_info['permission']['access'])) {
			$data['access'] = $user_group_info['permission']['access'];
		} else {
			$data['access'] = [];
		}

		if (isset($user_group_info['permission']['modify'])) {
			$data['modify'] = $user_group_info['permission']['modify'];
		} else {
			$data['modify'] = [];
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('user/user_group_form', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('user/user_group');

		$json = [];

		if (!$this->user->hasPermission('modify', 'user/user_permission')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		$required = [
			'user_group_id' => 0,
			'name'          => '',
			'permission'    => [],
			'sort_order'    => 0
		];

		$post_info = $this->request->post + $required;

		if (!oc_validate_length($post_info['name'], 3, 64)) {
			$json['error']['name'] = $this->language->get('error_name');
		}

		if (!$json) {
			// User Group
			$this->load->model('user/user_group');

			if (!$post_info['user_group_id']) {
				$json['user_group_id'] = $this->model_user_user_group->addUserGroup($post_info);
			} else {
				$this->model_user_user_group->editUserGroup((int)$post_info['user_group_id'], $post_info);
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
		$this->load->language('user/user_group');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'user/user_permission')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->load->model('user/user');

		foreach ($selected as $user_group_id) {
			// Total Users
			$user_total = $this->model_user_user->getTotalUsersByGroupId($user_group_id);

			if ($user_total) {
				$json['error'] = sprintf($this->language->get('error_user'), $user_total);
			}
		}

		if (!$json) {
			// User Group
			$this->load->model('user/user_group');

			foreach ($selected as $user_group_id) {
				$this->model_user_user_group->deleteUserGroup($user_group_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
