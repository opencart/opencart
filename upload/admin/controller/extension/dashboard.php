<?php
namespace Opencart\Admin\Controller\Extension;
class Dashboard extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->response->setOutput($this->getList());
	}

	public function getList(): string {
		$this->load->language('extension/dashboard');

		$available = [];

		$this->load->model('setting/extension');

		$results = $this->model_setting_extension->getPaths('%/admin/controller/dashboard/%.php');

		foreach ($results as $result) {
			$available[] = basename($result['path'], '.php');
		}

		$installed = [];

		$extensions = $this->model_setting_extension->getExtensionsByType('dashboard');

		foreach ($extensions as $extension) {
			if (in_array($extension['code'], $available)) {
				$installed[] = $extension['code'];
			} else {
				$this->model_setting_extension->uninstall('dashboard', $extension['code']);
			}
		}

		$data['extensions'] = [];

		if ($results) {
			foreach ($results as $result) {
				$extension = substr($result['path'], 0, strpos($result['path'], '/'));

				$code = basename($result['path'], '.php');

				$this->load->language('extension/' . $extension . '/dashboard/' . $code, $code);

				$data['extensions'][] = [
					'name'       => $this->language->get($code . '_heading_title'),
					'width'      => $this->config->get('dashboard_' . $code . '_width'),
					'status'     => $this->config->get('dashboard_' . $code . '_status') ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
					'sort_order' => $this->config->get('dashboard_' . $code . '_sort_order'),
					'install'    => $this->url->link('extension/dashboard|install', 'user_token=' . $this->session->data['user_token'] . '&extension=' . $extension . '&code=' . $code),
					'uninstall'  => $this->url->link('extension/dashboard|uninstall', 'user_token=' . $this->session->data['user_token'] . '&extension=' . $extension . '&code=' . $code),
					'installed'  => in_array($code, $installed),
					'edit'       => $this->url->link('extension/' . $extension . '/dashboard/' . $code, 'user_token=' . $this->session->data['user_token'])
				];
			}
		}

		$data['promotion'] = $this->load->controller('marketplace/promotion');

		return $this->load->view('extension/dashboard', $data);
	}

	protected function validate(): bool {
		if (!$this->user->hasPermission('modify', 'extension/dashboard')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function install(): void {
		$this->load->language('extension/dashboard');

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

		if (!$this->user->hasPermission('modify', 'extension/dashboard')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!is_file(DIR_EXTENSION . $extension . '/admin/controller/dashboard/' . $code . '.php')) {
			$json['error'] = $this->language->get('error_extension');
		}

		if (!$json) {
			$this->load->model('setting/extension');

			$this->model_setting_extension->install('dashboard', $extension, $code);

			$this->load->model('user/user_group');

			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/' . $extension . '/dashboard/' . $code);
			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/' .$extension . '/dashboard/' . $code);

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
			$this->load->controller('extension/' . $extension . '/dashboard/' . $code . '|install');

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function uninstall(): void {
		$this->load->language('extension/dashboard');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/dashboard')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('setting/extension');

			$this->model_setting_extension->uninstall('dashboard', $this->request->get['code']);

			// Call uninstall method if it exists
			$this->load->controller('extension/' . $this->request->get['extension'] . '/dashboard/' . $this->request->get['code'] . '|uninstall');

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
