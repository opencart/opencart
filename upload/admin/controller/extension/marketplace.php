<?php
namespace Opencart\Admin\Controller\Extension;
/**
 * Class Marketplace
 *
 * @package Opencart\Admin\Controller\Extension
 */
class Marketplace extends \Opencart\System\Engine\Controller {
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
		$this->load->language('extension/marketplace');

		$available = [];

		$results = $this->model_setting_extension->getPaths('%/admin/controller/marketplace/%.php');

		foreach ($results as $result) {
			$available[] = basename($result['path'], '.php');
		}

		$installed = [];

		$this->load->model('setting/extension');

		$extensions = $this->model_setting_extension->getExtensionsByType('marketplace');

		foreach ($extensions as $extension) {
			if (in_array($extension['code'], $available)) {
				$installed[] = $extension['code'];
			} else {
				$this->model_setting_extension->uninstall('marketplace', $extension['code']);
			}
		}

		$data['extensions'] = [];

		if ($results) {
			foreach ($results as $result) {
				$extension = substr($result['path'], 0, strpos($result['path'], '/'));

				$code = basename($result['path'], '.php');

				$this->load->language('extension/' . $extension . '/marketplace/' . $code, $code);

				$data['extensions'][] = [
					'name'      => $this->language->get($code . '_heading_title'),
					'status'    => $this->config->get('language_' . $code . '_status') ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
					'install'   => $this->url->link('extension/marketplace.install', 'user_token=' . $this->session->data['user_token'] . '&extension=' . $extension . '&code=' . $code),
					'uninstall' => $this->url->link('extension/marketplace.uninstall', 'user_token=' . $this->session->data['user_token'] . '&extension=' . $extension . '&code=' . $code),
					'installed' => in_array($code, $installed),
					'edit'      => $this->url->link('extension/' . $extension . '/marketplace/' . $code, 'user_token=' . $this->session->data['user_token'])
				];
			}
		}

		$data['promotion'] = $this->load->controller('marketplace/promotion');

		return $this->load->view('extension/marketplace', $data);
	}

	/**
	 * @return void
	 */
	public function install(): void {
		$this->load->language('extension/marketplace');

		$json = [];

		if (isset($this->request->get['extension'])) {
			$extension = basename((string)$this->request->get['extension']);
		} else {
			$extension = '';
		}

		if (isset($this->request->get['code'])) {
			$code = basename((string)$this->request->get['code']);
		} else {
			$code = '';
		}

		if (!$this->user->hasPermission('modify', 'extension/marketplace')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!is_file(DIR_EXTENSION . $extension . '/admin/controller/marketplace/' . $code . '.php')) {
			$json['error'] = $this->language->get('error_extension');
		}

		if (!$json) {
			$this->load->model('setting/extension');

			$this->model_setting_extension->install('marketplace', $extension, $code);

			$this->load->model('user/user_group');

			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/' . $extension . '/marketplace/' . $code);
			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/' . $extension . '/marketplace/' . $code);

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
			$this->load->controller('extension/' . $extension . '/marketplace/' . $code . '.install');

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * @return void
	 */
	public function uninstall(): void {
		$this->load->language('extension/marketplace');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/marketplace')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('setting/extension');

			$this->model_setting_extension->uninstall('marketplace', $this->request->get['code']);

			// Call uninstall method if it exists
			$this->load->controller('extension/' . $this->request->get['extension'] . '/marketplace/' . $this->request->get['code'] . '.uninstall');

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}