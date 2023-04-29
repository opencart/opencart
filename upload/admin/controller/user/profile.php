<?php
namespace Opencart\Admin\Controller\User;
class Profile extends \Opencart\System\Engine\Controller {
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

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('user/profile', $data));
	}

	public function save(): void {
		$this->load->language('user/profile');

		$json = [];

		if (!$this->user->hasPermission('modify', 'user/profile')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		if ((oc_strlen($this->request->post['username']) < 3) || (oc_strlen($this->request->post['username']) > 20)) {
			$json['error']['username'] = $this->language->get('error_username');
		}

		$this->load->model('user/user');

		$user_info = $this->model_user_user->getUserByUsername($this->request->post['username']);

		if ($user_info && ($this->user->getId() != $user_info['user_id'])) {
			$json['error']['warning'] = $this->language->get('error_username_exists');
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

		if ($user_info && ($this->user->getId() != $user_info['user_id'])) {
			$json['error']['warning'] = $this->language->get('error_email_exists');
		}

		if ($this->request->post['password']) {
			if ((oc_strlen(html_entity_decode($this->request->post['password'], ENT_QUOTES, 'UTF-8')) < 4) || (oc_strlen(html_entity_decode($this->request->post['password'], ENT_QUOTES, 'UTF-8')) > 40)) {
				$json['error']['password'] = $this->language->get('error_password');
			}

			if ($this->request->post['password'] != $this->request->post['confirm']) {
				$json['error']['confirm'] = $this->language->get('error_confirm');
			}
		}

		if (!$json) {
			$user_data = array_merge($this->request->post, [
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