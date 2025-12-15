<?php
namespace Opencart\Admin\Controller\Extension;
/**
 * Class Analytics
 *
 * @package Opencart\Admin\Controller\Extension
 */
class Analytics extends \Opencart\System\Engine\Controller {
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
		$this->load->language('extension/analytics');

		// Promotion
		$data['promotion'] = $this->load->controller('marketplace/promotion');

		$available = [];

		$results = oc_directory_read(DIR_EXTENSION, true, '/admin\/controller\/analytics\/.+\.php$/');

		foreach ($results as $result) {
			$available[] = basename($result, '.php');
		}

		$installed = [];

		// Extensions
		$this->load->model('setting/extension');

		$extensions = $this->model_setting_extension->getExtensionsByType('analytics');

		foreach ($extensions as $extension) {
			if (in_array($extension['code'], $available)) {
				$installed[] = $extension['code'];
			} else {
				// Uninstall any missing extensions
				$this->model_setting_extension->uninstall('analytics', $extension['code']);
			}
		}

		// Setting
		$this->load->model('setting/setting');

		$stores = [];

		$stores[] = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name')
		];

		$this->load->model('setting/store');

		$stores = array_merge($stores, $this->model_setting_store->getStores());

		// Extension
		$data['extensions'] = [];

		$this->load->model('setting/extension');

		foreach ($results as $result) {
			$path = substr($result, strlen(DIR_EXTENSION));

			$extension = substr($path, 0, strpos($path, '/'));

			$code = basename($result, '.php');

			$this->load->language('extension/' . $extension . '/analytics/' . $code, $code);

			$store_data = [];

			$store_data[] = [
				'name'   => $this->config->get('config_name'),
				'edit'   => $this->url->link('extension/' . $extension . '/analytics/' . $code, 'user_token=' . $this->session->data['user_token'] . '&store_id=0'),
				'status' => $this->config->get('analytics_' . $code . '_status')
			];

			foreach ($stores as $store) {
				$store_data[] = [
					'edit'   => $this->url->link('extension/' . $extension . '/analytics/' . $code, 'user_token=' . $this->session->data['user_token'] . '&store_id=' . $store['store_id']),
					'status' => $this->model_setting_setting->getValue('analytics_' . $code . '_status', $store['store_id'])
				] + $store;
			}

			$data['extensions'][] = [
				'name'      => $this->language->get($code . '_heading_title'),
				'install'   => $this->url->link('extension/analytics.install', 'user_token=' . $this->session->data['user_token'] . '&extension=' . $extension . '&code=' . $code),
				'uninstall' => $this->url->link('extension/analytics.uninstall', 'user_token=' . $this->session->data['user_token'] . '&extension=' . $extension . '&code=' . $code),
				'installed' => in_array($code, $installed),
				'store'     => $store_data
			];
		}

		return $this->load->view('extension/analytics', $data);
	}

	/**
	 * Install
	 *
	 * @return void
	 */
	public function install(): void {
		$this->load->language('extension/analytics');

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

		if (!$this->user->hasPermission('modify', 'extension/analytics')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!is_file(DIR_EXTENSION . $extension . '/admin/controller/analytics/' . $code . '.php')) {
			$json['error'] = $this->language->get('error_extension');
		}

		if (!$json) {
			// Extension
			$this->load->model('setting/extension');

			$this->model_setting_extension->install('analytics', $extension, $code);

			// User Group
			$this->load->model('user/user_group');

			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/' . $extension . '/analytics/' . $code);
			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/' . $extension . '/analytics/' . $code);

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
			$this->load->controller('extension/' . $extension . '/analytics/' . $code . '.install');

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
		$this->load->language('extension/analytics');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/analytics')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Extension
			$this->load->model('setting/extension');

			$this->model_setting_extension->uninstall('analytics', $this->request->get['code']);

			// Call uninstall method if it exists
			$this->load->controller('extension/' . $this->request->get['extension'] . '/analytics/' . $this->request->get['code'] . '.uninstall');

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
