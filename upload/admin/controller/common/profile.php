<?php
class ControllerCommonProfile extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('common/profile');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('user/user');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$user_data = array_merge($this->request->post, array(
				'user_group_id' => $this->user->getGroupId(),
				'status'        => 1,
			));
			
			$this->model_user_user->editUser($this->user->getId(), $user_data);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('common/profile', 'user_token=' . $this->session->data['user_token']));
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$errors = array('warning', 'username', 'password', 'confirm', 'firstname', 'lastname', 'email');

		foreach ($errors as $index) {
			$data['error_' . $index] = isset($this->error[$index]) ? $this->error[$index] : '';
		}

		$this->breadcrumbs->setDefaults();

		$data['action'] = $this->url->link('common/profile', 'user_token=' . $this->session->data['user_token']);

		$data['cancel'] = $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token']);

		if ($this->request->server['REQUEST_METHOD'] != 'POST') {
			$user_info = $this->model_user_user->getUser($this->user->getId());
		}

		$data['password'] = $this->request->hasPost('password') ? $this->request->post['password'] : '';

		$data['confirm'] = $this->request->hasPost('confirm') ? $this->request->post['confirm'] : '';

		$variables = array('username', 'firstname', 'lastname', 'email', 'image');

		foreach ($variables as $index) {
			if ($this->request->hasPost($index)) {
				$data[$index] = $this->request->post[$index];
			} elseif (!empty($user_info)) {
				$data[$index] = $user_info[$index];
			} else {
				$data[$index] = '';
			}
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
		$data['breadcrumbs'] = $this->breadcrumbs->render();
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('common/profile', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'common/profile')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['username']) < 3) || (utf8_strlen($this->request->post['username']) > 20)) {
			$this->error['username'] = $this->language->get('error_username');
		}

		$user_info = $this->model_user_user->getUserByUsername($this->request->post['username']);

		if ($user_info && ($this->user->getId() != $user_info['user_id'])) {
			$this->error['warning'] = $this->language->get('error_username_exists');
		}

		if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
			$this->error['firstname'] = $this->language->get('error_firstname');
		}

		if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
			$this->error['lastname'] = $this->language->get('error_lastname');
		}

		if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
			$this->error['email'] = $this->language->get('error_email');
		}

		$user_info = $this->model_user_user->getUserByEmail($this->request->post['email']);

		if ($user_info && ($this->user->getId() != $user_info['user_id'])) {
			$this->error['warning'] = $this->language->get('error_email_exists');
		}

		if ($this->request->post['password']) {
			if ((utf8_strlen(html_entity_decode($this->request->post['password'], ENT_QUOTES, 'UTF-8')) < 4) || (utf8_strlen(html_entity_decode($this->request->post['password'], ENT_QUOTES, 'UTF-8')) > 40)) {
				$this->error['password'] = $this->language->get('error_password');
			}

			if ($this->request->post['password'] != $this->request->post['confirm']) {
				$this->error['confirm'] = $this->language->get('error_confirm');
			}
		}

		return !$this->error;
	}
}