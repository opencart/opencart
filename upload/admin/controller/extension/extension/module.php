<?php
class ControllerExtensionExtensionModule extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/extension/module');

		$this->load->model('extension/extension');

		$this->load->model('extension/module');

		$this->getList();
	}

	public function install() {
		$this->load->language('extension/extension/module');

		$this->load->model('extension/extension');

		$this->load->model('extension/module');

		if ($this->validate()) {
			$this->model_extension_extension->install('module', $this->request->get['extension']);

			$this->load->model('user/user_group');

			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/module/' . $this->request->get['extension']);
			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/module/' . $this->request->get['extension']);

			// Call install method if it exsits
			$this->load->controller('extension/module/' . $this->request->get['extension'] . '/install');

			$this->session->data['success'] = $this->language->get('text_success');
		} else {
			$this->session->data['error'] = $this->error['warning'];
		}
	
		$this->getList();
	}

	public function uninstall() {
		$this->load->language('extension/extension/module');

		$this->load->model('extension/extension');

		$this->load->model('extension/module');

		if ($this->validate()) {
			$this->model_extension_extension->uninstall('module', $this->request->get['extension']);

			$this->model_extension_module->deleteModulesByCode($this->request->get['extension']);

			// Call uninstall method if it exsits
			$this->load->controller('extension/module/' . $this->request->get['extension'] . '/uninstall');

			$this->session->data['success'] = $this->language->get('text_success');
		}

		$this->getList();
	}
	
	public function add() {
		$this->load->language('extension/extension/module');

		$this->load->model('extension/extension');

		$this->load->model('extension/module');

		if ($this->validate()) {
			$this->load->language('module' . '/' . $this->request->get['extension']);
			
			$this->model_extension_module->addModule($this->request->get['extension'], $this->language->get('heading_title'));

			$this->session->data['success'] = $this->language->get('text_success');
		}

		$this->getList();
	}

	public function delete() {
		$this->load->language('extension/extension/module');

		$this->load->model('extension/extension');

		$this->load->model('extension/module');

		if (isset($this->request->get['module_id']) && $this->validate()) {
			$this->model_extension_module->deleteModule($this->request->get['module_id']);

			$this->session->data['success'] = $this->language->get('text_success');
		}
		
		$this->getList();
	}

	protected function getList() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_layout'] = sprintf($this->language->get('text_layout'), $this->url->link('design/layout', 'token=' . $this->session->data['token'], true));
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
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

		$extensions = $this->model_extension_extension->getInstalled('module');

		foreach ($extensions as $key => $value) {
			if (!is_file(DIR_APPLICATION . 'controller/extension/module/' . $value . '.php')) {
				$this->model_extension_extension->uninstall('module', $value);

				unset($extensions[$key]);
				
				$this->model_extension_module->deleteModulesByCode($value);
			}
		}

		$data['extensions'] = array();

		// Compatibility code for old extension folders
		$files = glob(DIR_APPLICATION . 'controller/extension/module/*.php', GLOB_BRACE);

		if ($files) {
			foreach ($files as $file) {
				$extension = basename($file, '.php');

				$this->load->language('extension/module/' . $extension);

				$module_data = array();

				$modules = $this->model_extension_module->getModulesByCode($extension);

				foreach ($modules as $module) {
					$module_data[] = array(
						'module_id' => $module['module_id'],
						'name'      => $module['name'],
						'edit'      => $this->url->link('extension/module/' . $extension, 'token=' . $this->session->data['token'] . '&module_id=' . $module['module_id'], true),
						'delete'    => $this->url->link('extension/extension/module/delete', 'token=' . $this->session->data['token'] . '&module_id=' . $module['module_id'], true)
					);
				}

				$data['extensions'][] = array(
					'name'      => $this->language->get('heading_title'),
					'module'    => $module_data,
					'install'   => $this->url->link('extension/extension/module/install', 'token=' . $this->session->data['token'] . '&extension=' . $extension, true),
					'uninstall' => $this->url->link('extension/extension/module/uninstall', 'token=' . $this->session->data['token'] . '&extension=' . $extension, true),
					'installed' => in_array($extension, $extensions),
					'edit'      => $this->url->link('extension/module/' . $extension, 'token=' . $this->session->data['token'], true)
				);
			}
		}

		$sort_order = array();

		foreach ($data['extensions'] as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $data['extensions']);

		$this->response->setOutput($this->load->view('extension/extension/module', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/extension/module')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
