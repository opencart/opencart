<?php
namespace Opencart\Application\Controller\Extension;
class Module extends \Opencart\System\Engine\Controller {
	private $error = [];

	public function index() {
		$this->load->language('extension/module');

		$this->load->model('setting/extension');

		$this->load->model('setting/module');

		$this->getList();
	}

	public function install() {
		$this->load->language('extension/module');

		$this->load->model('setting/extension');

		$this->load->model('setting/module');

		if ($this->validate()) {
			$this->model_setting_extension->install('module', $this->request->get['extension'], $this->request->get['code']);

			$this->load->model('user/user_group');

			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/' . $this->request->get['extension'] . '/module/' . $this->request->get['code']);
			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/' . $this->request->get['extension'] . '/module/' . $this->request->get['code']);

			// Call install method if it exists
			$this->load->controller('extension/' . $this->request->get['extension'] . '/module/' . $this->request->get['code'] . '/install');

			$this->session->data['success'] = $this->language->get('text_success');
		} else {
			$this->session->data['error'] = $this->error['warning'];
		}
	
		$this->getList();
	}

	public function uninstall() {
		$this->load->language('extension/module');

		$this->load->model('setting/extension');

		$this->load->model('setting/module');

		if ($this->validate()) {
			$this->model_setting_extension->uninstall('module', $this->request->get['code']);

			$this->model_setting_module->deleteModulesByCode($this->request->get['code']);

			// Call uninstall method if it exists
			$this->load->controller('extension/' . $this->request->get['extension'] . '/module/' . $this->request->get['code'] . '/uninstall');

			$this->session->data['success'] = $this->language->get('text_success');
		}

		$this->getList();
	}
	
	public function add() {
		$this->load->language('extension/module');

		$this->load->model('setting/extension');

		$this->load->model('setting/module');

		if ($this->validate()) {
			$this->load->language('extension/' . $this->request->get['extension'] . '/module/' . $this->request->get['code']);
			
			$this->model_setting_module->addModule($this->request->get['extension'] . '.' . $this->request->get['code'], $this->language->get('heading_title'));

			$this->session->data['success'] = $this->language->get('text_success');
		}

		$this->getList();
	}

	public function delete() {
		$this->load->language('extension/module');

		$this->load->model('setting/extension');

		$this->load->model('setting/module');

		if (isset($this->request->get['module_id']) && $this->validate()) {
			$this->model_setting_module->deleteModule($this->request->get['module_id']);

			$this->session->data['success'] = $this->language->get('text_success');
		}
		
		$this->getList();
	}

	protected function getList() {
		$data['text_layout'] = sprintf($this->language->get('text_layout'), $this->url->link('design/layout', 'user_token=' . $this->session->data['user_token']));

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

		$available = [];

		$results = $this->model_setting_extension->getPaths('%/admin/controller/module/%.php');

		foreach ($results as $result) {
			$available[] = basename($result['path'], '.php');
		}

		$installed = [];

		$extensions = $this->model_setting_extension->getExtensionsByType('module');

		foreach ($extensions as $extension) {
			if (in_array($extension['code'], $available)) {
				$installed[] = $extension['code'];
			} else {
				$this->model_setting_extension->uninstall('module', $extension['code']);
			}
		}

		$data['extensions'] = [];

		if ($results) {
			foreach ($results as $result) {
				$extension = substr($result['path'], 0, strpos($result['path'], '/'));

				$code = basename($result['path'], '.php');

				$this->load->language('extension/' . $extension . '/module/' . $code, $code);

				$module_data = [];

				$modules = $this->model_setting_module->getModulesByCode($extension . '.' . $code);

				foreach ($modules as $module) {
					if ($module['setting']) {
						$setting_info = json_decode($module['setting'], true);
					} else {
						$setting_info = [];
					}
					
					$module_data[] = [
						'module_id' => $module['module_id'],
						'name'      => $module['name'],
						'status'    => !$module_data && !empty($setting_info['status']) ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
						'edit'      => $this->url->link('extension/' . $extension . '/module/' . $code, 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $module['module_id']),
						'delete'    => $this->url->link('extension/module/delete', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $module['module_id'])
					];
				}

				$data['extensions'][] = [
					'name'      => $this->language->get($code . '_heading_title'),
					'status'    => $this->config->get('module_' . $code . '_status') ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
					'module'    => $module_data,
					'install'   => $this->url->link('extension/module/install', 'user_token=' . $this->session->data['user_token'] . '&extension=' . $extension . '&code=' . $code),
					'uninstall' => $this->url->link('extension/module/uninstall', 'user_token=' . $this->session->data['user_token'] . '&extension=' . $extension . '&code=' . $code),
					'installed' => in_array($code, $installed),
					'edit'      => $this->url->link('extension/' . $extension . '/module/' . $code, 'user_token=' . $this->session->data['user_token'])
				];
			}
		}

		$sort_order = [];

		foreach ($data['extensions'] as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $data['extensions']);

		$data['promotion'] = $this->load->controller('extension/promotion');

		$this->response->setOutput($this->load->view('extension/module', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
