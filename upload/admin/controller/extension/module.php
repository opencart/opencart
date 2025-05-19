<?php
namespace Opencart\Admin\Controller\Extension;
/**
 * Class Module
 *
 * @package Opencart\Admin\Controller\Extension
 */
class Module extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->response->setOutput($this->getList());
	}

	/**
	 * Get List
	 *
	 * @return string
	 */
	public function getList(): string {
		$this->load->language('extension/module');

		$data['text_layout'] = sprintf($this->language->get('text_layout'), $this->url->link('design/layout', 'user_token=' . $this->session->data['user_token']));

		$available = [];

		$results = glob(DIR_EXTENSION . '*/admin/controller/module/*.php');

		foreach ($results as $result) {
			$available[] = basename($result, '.php');
		}

		$installed = [];

		// Extensions
		$this->load->model('setting/extension');

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
			// Extension
			$this->load->model('setting/module');

			foreach ($results as $result) {
				$path = substr($result, strlen(DIR_EXTENSION));

				$extension = substr($path, 0, strpos($path, '/'));

				$code = basename($result, '.php');

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
						'name'   => $module['name'],
						'status' => (bool)$setting_info['status'],
						'edit'   => $this->url->link('extension/' . $extension . '/module/' . $code, 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $module['module_id']),
						'delete' => $this->url->link('extension/module.delete', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $module['module_id'])
					] + $module;
				}

				if ($module_data) {
					$status = false;
				} else {
					$status = $this->config->get('module_' . $code . '_status');
				}

				$data['extensions'][] = [
					'name'      => $this->language->get($code . '_heading_title'),
					'status'    => $status,
					'module'    => $module_data,
					'install'   => $this->url->link('extension/module.install', 'user_token=' . $this->session->data['user_token'] . '&extension=' . $extension . '&code=' . $code),
					'uninstall' => $this->url->link('extension/module.uninstall', 'user_token=' . $this->session->data['user_token'] . '&extension=' . $extension . '&code=' . $code),
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

		$data['promotion'] = $this->load->controller('marketplace/promotion');

		return $this->load->view('extension/module', $data);
	}

	/**
	 * Install
	 *
	 * @return void
	 */
	public function install(): void {
		$this->load->language('extension/module');

		$json = [];

		if (isset($this->request->get['extension'])) {
			$extension = basename($this->request->get['extension']);
		} else {
			$extension = '';
		}

		if (isset($this->request->get['code'])) {
			$code = basename($this->request->get['code']);
		} else {
			$code = '';
		}

		if (!$this->user->hasPermission('modify', 'extension/module')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!is_file(DIR_EXTENSION . $extension . '/admin/controller/module/' . $code . '.php')) {
			$json['error'] = $this->language->get('error_extension');
		}

		if (!$json) {
			// Extension
			$this->load->model('setting/extension');

			$this->model_setting_extension->install('module', $extension, $code);

			// User Group
			$this->load->model('user/user_group');

			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/' . $extension . '/module/' . $code);
			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/' . $extension . '/module/' . $code);

			$namespace = str_replace(['_', '/'], ['', '\\'], ucwords($extension, '_/'));

			// Register controllers, models and system extension folders
			$this->autoloader->register('Opencart\Admin\Controller\Extension\\' . $namespace, DIR_EXTENSION . $extension . '/admin/controller/');
			$this->autoloader->register('Opencart\Admin\Model\Extension\\' . $namespace, DIR_EXTENSION . $extension . '/admin/model/');
			$this->autoloader->register('Opencart\System\Extension\\' . $namespace, DIR_EXTENSION . $extension . '/system/');

			// Template directory
			$this->template->addPath('extension/' . $extension, DIR_EXTENSION . $extension . '/admin/view/template/');

			// Language directory
			$this->language->addPath('extension/' . $extension, DIR_EXTENSION . $extension . '/admin/language/');

			// Config directory
			$this->config->addPath('extension/' . $extension, DIR_EXTENSION . $extension . '/system/config/');

			// Call install method if it exists
			$this->load->controller('extension/' . $extension . '/module/' . $code . '.install');

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Uninstall
	 *
	 * @return void
	 */
	public function uninstall(): void {
		$this->load->language('extension/module');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/module')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Extension
			$this->load->model('setting/extension');

			$this->model_setting_extension->uninstall('module', $this->request->get['code']);

			$this->load->model('setting/module');

			$this->model_setting_module->deleteModulesByCode($this->request->get['extension'] . '.' . $this->request->get['code']);

			// Call uninstall method if it exists
			$this->load->controller('extension/' . $this->request->get['extension'] . '/module/' . $this->request->get['code'] . '.uninstall');

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Add
	 *
	 * @return void
	 */
	public function add(): void {
		$this->load->language('extension/module');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/module')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->language('extension/' . $this->request->get['extension'] . '/module/' . $this->request->get['code'], 'extension');

			// Extension
			$this->load->model('setting/module');

			$this->model_setting_module->addModule($this->request->get['extension'] . '.' . $this->request->get['code'], ['name' => $this->language->get('extension_heading_title')]);

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
		$this->load->language('extension/module');

		$json = [];

		if (isset($this->request->get['module_id'])) {
			$module_id = $this->request->get['module_id'];
		} else {
			$module_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'extension/module')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Extension
			$this->load->model('setting/module');

			$this->model_setting_module->deleteModule($module_id);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
