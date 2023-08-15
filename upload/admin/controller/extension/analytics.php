<?php
namespace Opencart\Admin\Controller\Extension;
/**
 * Class Analytics
 *
 * @package Opencart\Admin\Controller\Extension
 */
class Analytics extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
	public function index(): void {
		$this->response->setOutput($this->getList());
	}

	/**
	 * @return string
	 */
	public function getList(): string {
		$this->load->language('extension/analytics');

		// Promotion
		$data['promotion'] = $this->load->controller('marketplace/promotion');

		$available = [];

		$this->load->model('setting/extension');

		$results = $this->model_setting_extension->getPaths('%/admin/controller/analytics/%.php');

		foreach ($results as $result) {
			$available[] = basename($result['path'], '.php');
		}

		$installed = [];

		$extensions = $this->model_setting_extension->getExtensionsByType('analytics');

		foreach ($extensions as $extension) {
			if (in_array($extension['code'], $available)) {
				$installed[] = $extension['code'];
			} else {
				// Uninstall any missing extensions
				$this->model_setting_extension->uninstall('analytics', $extension['code']);
			}
		}

		$this->load->model('setting/store');
		$this->load->model('setting/setting');

		$stores = $this->model_setting_store->getStores();

		$data['extensions'] = [];

		$this->load->model('setting/extension');

		if ($results) {
			foreach ($results as $result) {
				$extension = substr($result['path'], 0, strpos($result['path'], '/'));

				$code = basename($result['path'], '.php');

				$this->load->language('extension/' . $extension . '/analytics/' . $code, $code);

				$store_data = [];

				$store_data[] = [
					'name'   => $this->config->get('config_name'),
					'edit'   => $this->url->link('extension/' . $extension . '/analytics/' . $code, 'user_token=' . $this->session->data['user_token'] . '&store_id=0'),
					'status' => $this->config->get('analytics_' . $code . '_status') ? $this->language->get('text_enabled') : $this->language->get('text_disabled')
				];

				foreach ($stores as $store) {
					$store_data[] = [
						'name'   => $store['name'],
						'edit'   => $this->url->link('extension/' . $extension . '/analytics/' . $code, 'user_token=' . $this->session->data['user_token'] . '&store_id=' . $store['store_id']),
						'status' => $this->model_setting_setting->getValue('analytics_' . $code . '_status', $store['store_id']) ? $this->language->get('text_enabled') : $this->language->get('text_disabled')
					];
				}

				$data['extensions'][] = [
					'name' => $this->language->get($code . '_heading_title'),
					'install' => $this->url->link('extension/analytics.install', 'user_token=' . $this->session->data['user_token'] . '&extension=' . $extension . '&code=' . $code),
					'uninstall' => $this->url->link('extension/analytics.uninstall', 'user_token=' . $this->session->data['user_token'] . '&extension=' . $extension . '&code=' . $code),
					'installed' => in_array($code, $installed),
					'store' => $store_data
				];
			}
		}

		return $this->load->view('extension/analytics', $data);
	}

	/**
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
			$this->load->model('setting/extension');

			$this->model_setting_extension->install('analytics', $extension, $code);

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
	 * @return void
	 */
	public function uninstall(): void {
		$this->load->language('extension/analytics');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/analytics')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
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