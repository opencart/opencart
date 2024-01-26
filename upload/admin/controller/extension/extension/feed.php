<?php
class ControllerExtensionExtensionFeed extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/extension/feed');

		$this->load->model('setting/extension');

		$this->getList();
	}

	public function install() {
		$this->load->language('extension/extension/feed');

		$this->load->model('setting/extension');

		if ($this->validate()) {
			$this->model_setting_extension->install('feed', $this->request->get['extension']);

			$this->load->model('user/user_group');

			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/feed/' . $this->request->get['extension']);
			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/feed/' . $this->request->get['extension']);

			// Call install method if it exsits
			$this->load->controller('extension/feed/' . $this->request->get['extension'] . '/install');

			$this->session->data['success'] = $this->language->get('text_success');
		}

		$this->getList();
	}

	public function uninstall() {
		$this->load->language('extension/extension/feed');

		$this->load->model('setting/extension');

		if ($this->validate()) {
			$this->model_setting_extension->uninstall('feed', $this->request->get['extension']);

			// Call uninstall method if it exsits
			$this->load->controller('extension/feed/' . $this->request->get['extension'] . '/uninstall');

			$this->load->model('user/user_group');
			$this->model_user_user_group->removePermissions('extension/feed/' . $this->request->get['extension']);

			$this->session->data['success'] = $this->language->get('text_success');
		}
		
		$this->getList();
	}

	protected function getList() {
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

		$extensions = $this->model_setting_extension->getInstalled('feed');

		foreach ($extensions as $key => $value) {
			if (!is_file(DIR_APPLICATION . 'controller/extension/feed/' . $value . '.php') && !is_file(DIR_APPLICATION . 'controller/feed/' . $value . '.php')) {
				$this->model_setting_extension->uninstall('feed', $value);

				unset($extensions[$key]);
			}
		}

		$data['extensions'] = array();
		
		// Compatibility code for old extension folders
		$files = glob(DIR_APPLICATION . 'controller/extension/feed/*.php');

		if ($files) {
			foreach ($files as $file) {
				$extension = basename($file, '.php');

				$this->load->language('extension/feed/' . $extension, 'extension');

				$data['extensions'][] = array(
					'name'      => $this->language->get('extension')->get('heading_title'),
					'status'    => $this->config->get('feed_' . $extension . '_status') ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
					'install'   => $this->url->link('extension/extension/feed/install', 'user_token=' . $this->session->data['user_token'] . '&extension=' . $extension, true),
					'uninstall' => $this->url->link('extension/extension/feed/uninstall', 'user_token=' . $this->session->data['user_token'] . '&extension=' . $extension, true),
					'installed' => in_array($extension, $extensions),
					'edit'      => $this->url->link('extension/feed/' . $extension, 'user_token=' . $this->session->data['user_token'], true)
				);
			}
		}

		$data['promotion'] = $this->load->controller('extension/extension/promotion');

		$this->response->setOutput($this->load->view('extension/extension/feed', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/extension/feed')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}