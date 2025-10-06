<?php
namespace Opencart\Admin\Controller\User;
/**
 * Class Profile
 *
 * @package Opencart\Admin\Controller\User
 */
class Profile extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('user/profile');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('user/profile', 'user_token=' . $this->session->data['user_token'])
		];

		$data['save'] = $this->url->link('user/profile.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token']);

		// User
		$this->load->model('user/user');

		$user_info = $this->model_user_user->getUser($this->user->getId());

		if (!empty($user_info)) {
			$data['username'] = $user_info['username'];
		} else {
			$data['username'] = '';
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

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('user/profile', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('user/profile');

		$json = [];

		if (!$this->user->hasPermission('modify', 'user/profile')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		$required = [
			'username'      => '',
			'user_group_id' => 0,
			'password'      => '',
			'firstname'     => '',
			'lastname'      => '',
			'email'         => '',
			'image'         => '',
			'status'        => 0
		];

		$post_info = $this->request->post + $required;

		if (!oc_validate_length($post_info['username'], 3, 20)) {
			$json['error']['username'] = $this->language->get('error_username');
		}

		// User
		$this->load->model('user/user');

		$user_info = $this->model_user_user->getUserByUsername($post_info['username']);

		if ($user_info && ($this->user->getId() != $user_info['user_id'])) {
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

		if ($user_info && ($this->user->getId() != $user_info['user_id'])) {
			$json['error']['warning'] = $this->language->get('error_email_exists');
		}

		if ($post_info['password']) {
			$password = html_entity_decode($post_info['password'], ENT_QUOTES, 'UTF-8');

			if (!oc_validate_length($password, (int)$this->config->get('config_user_password_length'), 40)) {
				$json['error']['password'] = sprintf($this->language->get('error_password_length'), (int)$this->config->get('config_user_password_length'));
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
			$user_data = array_merge($post_info, [
				'user_group_id' => $this->user->getGroupId(),
				'status'        => 1,
			]);

			$this->model_user_user->editUser($this->user->getId(), $user_data);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
