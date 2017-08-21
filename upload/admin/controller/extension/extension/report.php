<?php
class ControllerExtensionExtensionReport extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/extension/report');

		$this->load->model('setting/extension');

		$this->getList();
	}

	public function install() {
		$this->load->language('extension/extension/report');

		$this->load->model('setting/extension');

		if ($this->validate()) {
			$this->model_setting_extension->install('report', $this->request->get['extension']);

			$this->load->model('user/user_group');

			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/report/' . $this->request->get['extension']);
			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/report/' . $this->request->get['extension']);

			$this->load->controller('extension/report/' . $this->request->get['extension'] . '/install');

			$this->session->data['success'] = $this->language->get('text_success');
		}

		$this->getList();
	}

	public function uninstall() {
		$this->load->language('extension/extension/report');

		$this->load->model('setting/extension');

		if ($this->validate()) {
			$this->model_setting_extension->uninstall('report', $this->request->get['extension']);

			$this->load->controller('extension/report/' . $this->request->get['extension'] . '/uninstall');

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

		$this->load->model('setting/extension');

		$extensions = $this->model_setting_extension->getInstalled('report');

		foreach ($extensions as $key => $value) {
			if (!is_file(DIR_APPLICATION . 'controller/extension/report/' . $value . '.php') && !is_file(DIR_APPLICATION . 'controller/report/' . $value . '.php')) {
				$this->model_setting_extension->uninstall('report', $value);

				unset($extensions[$key]);
			}
		}

		$data['extensions'] = array();
		
		// Compatibility code for old extension folders
		$files = glob(DIR_APPLICATION . 'controller/extension/report/*.php');

		if ($files) {
			foreach ($files as $file) {
				$extension = basename($file, '.php');

				$this->load->language('extension/report/' . $extension, 'extension');

				$data['extensions'][] = array(
					'name'       => $this->language->get('extension')->get('heading_title'),
					'status'     => $this->config->get('report_' . $extension . '_status') ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
					'sort_order' => $this->config->get('report_' . $extension . '_sort_order'),
					'install'    => $this->url->link('extension/extension/report/install', 'user_token=' . $this->session->data['user_token'] . '&extension=' . $extension, true),
					'uninstall'  => $this->url->link('extension/extension/report/uninstall', 'user_token=' . $this->session->data['user_token'] . '&extension=' . $extension, true),
					'installed'  => in_array($extension, $extensions),
					'edit'       => $this->url->link('extension/report/' . $extension, 'user_token=' . $this->session->data['user_token'], true)
				);
			}
		}

		$this->response->setOutput($this->load->view('extension/extension/report', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/extension/report')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}