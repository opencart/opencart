<?php
namespace Opencart\Admin\Controller\Extension;
/**
 * Class Theme
 *
 * @package Opencart\Admin\Controller\Extension
 */
class Theme extends \Opencart\System\Engine\Controller {
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
		$this->load->language('extension/theme');

		$available = [];

		$results = glob(DIR_EXTENSION . '*/admin/controller/theme/*.php');

		foreach ($results as $result) {
			$available[] = basename($result, '.php');
		}

		$installed = [];

		// Extensions
		$this->load->model('setting/extension');

		$extensions = $this->model_setting_extension->getExtensionsByType('theme');

		foreach ($extensions as $extension) {
			if (in_array($extension['code'], $available)) {
				$installed[] = $extension['code'];
			} else {
				$this->model_setting_extension->uninstall('theme', $extension['code']);
			}
		}

		// Setting
		$this->load->model('setting/store');

		$this->load->model('setting/setting');

		$stores = $this->model_setting_store->getStores();

		$data['extensions'] = [];

		if ($results) {
			foreach ($results as $result) {
				$path = substr($result, strlen(DIR_EXTENSION));

				$extension = substr($path, 0, strpos($path, '/'));

				$code = basename($result, '.php');

				$this->load->language('extension/' . $extension . '/theme/' . $code, $code);

				$store_data = [];

				$store_data[] = [
					'name'   => $this->config->get('config_name'),
					'edit'   => $this->url->link('extension/' . $extension . '/theme/' . $code, 'user_token=' . $this->session->data['user_token'] . '&store_id=0'),
					'status' => $this->config->get('theme_' . $code . '_status')
				];

				foreach ($stores as $store) {
					$store_data[] = [
						'name'   => $store['name'],
						'edit'   => $this->url->link('extension/' . $extension . '/theme/' . $code, 'user_token=' . $this->session->data['user_token'] . '&store_id=' . $store['store_id']),
						'status' => $this->model_setting_setting->getValue('theme_' . $code . '_status', $store['store_id'])
					];
				}

				$data['extensions'][] = [
					'name'      => $this->language->get($code . '_heading_title'),
					'install'   => $this->url->link('extension/theme.install', 'user_token=' . $this->session->data['user_token'] . '&extension=' . $extension . '&code=' . $code),
					'uninstall' => $this->url->link('extension/theme.uninstall', 'user_token=' . $this->session->data['user_token'] . '&extension=' . $extension . '&code=' . $code),
					'installed' => in_array($code, $installed),
					'store'     => $store_data
				];
			}
		}

		$data['promotion'] = $this->load->controller('marketplace/promotion');

		return $this->load->view('extension/theme', $data);
	}

	/**
	 * Install
	 *
	 * @return void
	 */
	public function install(): void {
		$this->load->language('extension/theme');

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

		if (!$this->user->hasPermission('modify', 'extension/theme')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!is_file(DIR_EXTENSION . $extension . '/admin/controller/theme/' . $code . '.php')) {
			$json['error'] = $this->language->get('error_extension');
		}

		if (!$json) {
			// Extension
			$this->load->model('setting/extension');

			$this->model_setting_extension->install('theme', $extension, $code);

			// User Group
			$this->load->model('user/user_group');

			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/' . $extension . '/theme/' . $code);
			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/' . $extension . '/theme/' . $code);

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
			$this->load->controller('extension/' . $extension . '/theme/' . $code . '.install');

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
		$this->load->language('extension/theme');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/theme')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Extension
			$this->load->model('setting/extension');

			$this->model_setting_extension->uninstall('theme', $this->request->get['code']);

			// Call uninstall method if it exists
			$this->load->controller('extension/' . $this->request->get['extension'] . '/theme/' . $this->request->get['code'] . '.uninstall');

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
