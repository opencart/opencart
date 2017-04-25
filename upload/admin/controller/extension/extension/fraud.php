<?php
class ControllerExtensionExtensionFraud extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/extension/fraud');

		$this->load->model('extension/extension');

		$this->getList();
	}

	public function install() {
		$this->load->language('extension/extension/fraud');

		$this->load->model('extension/extension');

		if ($this->validate()) {
			$this->model_extension_extension->install('fraud', $this->request->get['extension']);

			$this->load->model('user/user_group');

			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/fraud/' . $this->request->get['extension']);
			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/fraud/' . $this->request->get['extension']);

			// Call install method if it exsits
			$this->load->controller('extension/fraud/' . $this->request->get['extension'] . '/install');

			$this->session->data['success'] = $this->language->get('text_success');
		}

		$this->getList();
	}

	public function uninstall() {
		$this->load->language('extension/extension/fraud');

		$this->load->model('extension/extension');

		if ($this->validate()) {
			$this->model_extension_extension->uninstall('fraud', $this->request->get['extension']);

			// Call uninstall method if it exsits
			$this->load->controller('extension/fraud/' . $this->request->get['extension'] . '/uninstall');

			$this->session->data['success'] = $this->language->get('text_success');
		}
		
		$this->getList();
	}

	protected function getList() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_no_results'] = $this->language->get('text_no_results');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_install'] = $this->language->get('button_install');
		$data['button_uninstall'] = $this->language->get('button_uninstall');

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

		$extensions = $this->model_extension_extension->getInstalled('fraud');

		foreach ($extensions as $key => $value) {
			if (!is_file(DIR_APPLICATION . 'controller/extension/fraud/' . $value . '.php')) {
				$this->model_extension_extension->uninstall('fraud', $value);

				unset($extensions[$key]);
			}
		}

		$data['extensions'] = array();

		// Compatibility code for old extension folders
		$files = glob(DIR_APPLICATION . 'controller/extension/fraud/*.php', GLOB_BRACE);

		if ($files) {
			foreach ($files as $file) {
				$extension = basename($file, '.php');

				$this->load->language('extension/fraud/' . $extension);

				$data['extensions'][] = array(
					'name'      => $this->language->get('heading_title'),
					'status'    => $this->config->get($extension . '_status') ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
					'install'   => $this->url->link('extension/extension/fraud/install', 'token=' . $this->session->data['token'] . '&extension=' . $extension, true),
					'uninstall' => $this->url->link('extension/extension/fraud/uninstall', 'token=' . $this->session->data['token'] . '&extension=' . $extension, true),
					'installed' => in_array($extension, $extensions),
					'edit'      => $this->url->link('extension/fraud/' . $extension, 'token=' . $this->session->data['token'], true)
				);
			}
		}


		$this->response->setOutput($this->load->view('extension/extension/fraud', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/extension/fraud')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
