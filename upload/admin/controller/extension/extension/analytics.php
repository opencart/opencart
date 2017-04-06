<?php
class ControllerExtensionExtensionAnalytics extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/extension/analytics');

		$this->load->model('extension/extension');

		$this->getList();
	}

	public function install() {
		$this->load->language('extension/extension/analytics');

		$this->load->model('extension/extension');

		if ($this->validate()) {
			$this->model_extension_extension->install('analytics', $this->request->get['extension']);

			$this->load->model('user/user_group');

			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/analytics/' . $this->request->get['extension']);
			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/analytics/' . $this->request->get['extension']);
			
			// Call install method if it exsits
			$this->load->controller('extension/analytics/' . $this->request->get['extension'] . '/install');

			$this->session->data['success'] = $this->language->get('text_success');
		}

		$this->getList();
	}

	public function uninstall() {
		$this->load->language('extension/extension/analytics');

		$this->load->model('extension/extension');

		if ($this->validate()) {
			$this->model_extension_extension->uninstall('analytics', $this->request->get['extension']);

			// Call uninstall method if it exsits
			$this->load->controller('extension/analytics/' . $this->request->get['extension'] . '/uninstall');

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

		$extensions = $this->model_extension_extension->getInstalled('analytics');

		foreach ($extensions as $key => $value) {
			if (!is_file(DIR_APPLICATION . 'controller/extension/analytics/' . $value . '.php')) {
				$this->model_extension_extension->uninstall('analytics', $value);

				unset($extensions[$key]);
			}
		}

		$this->load->model('setting/store');
		$this->load->model('setting/setting');

		$stores = $this->model_setting_store->getStores();

		$data['extensions'] = array();

		// Compatibility code for old extension folders
		$files = glob(DIR_APPLICATION . 'controller/extension/analytics/*.php', GLOB_BRACE);

		if ($files) {
			foreach ($files as $file) {
				$extension = basename($file, '.php');
				
				// Compatibility code for old extension folders
				$this->load->language('extension/analytics/' . $extension);
				
				$store_data = array();

				$store_data[] = array(
					'name'   => $this->config->get('config_name'),
					'edit'   => $this->url->link('extension/analytics/' . $extension, 'token=' . $this->session->data['token'] . '&store_id=0', true),
					'status' => $this->config->get($extension . '_status') ? $this->language->get('text_enabled') : $this->language->get('text_disabled')
				);
				
				foreach ($stores as $store) {
					$store_data[] = array(
						'name'   => $store['name'],
						'edit'   => $this->url->link('extension/analytics/' . $extension, 'token=' . $this->session->data['token'] . '&store_id=' . $store['store_id'], true),
						'status' => $this->model_setting_setting->getSettingValue($extension . '_status', $store['store_id']) ? $this->language->get('text_enabled') : $this->language->get('text_disabled')
					);
				}

				$data['extensions'][] = array(
					'name'      => $this->language->get('heading_title'),
					'install'   => $this->url->link('extension/extension/analytics/install', 'token=' . $this->session->data['token'] . '&extension=' . $extension, true),
					'uninstall' => $this->url->link('extension/extension/analytics/uninstall', 'token=' . $this->session->data['token'] . '&extension=' . $extension, true),
					'installed' => in_array($extension, $extensions),
					'store'     => $store_data
				);
			}
		}

		$this->response->setOutput($this->load->view('extension/extension/analytics', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/extension/analytics')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
