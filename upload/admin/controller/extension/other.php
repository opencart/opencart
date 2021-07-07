<?php
namespace Opencart\Admin\Controller\Extension;
class Other extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->response->setOutput($this->getList());
	}

	public function getList(): string {
		// Had top load again because the method is called directly.
		$this->load->language('extension/other');

		$available = [];

		$this->load->model('setting/extension');

		$results = $this->model_setting_extension->getPaths('%/admin/controller/other/%.php');

		foreach ($results as $result) {
			$available[] = basename($result['path'], '.php');
		}

		$installed = [];

		$extensions = $this->model_setting_extension->getExtensionsByType('other');

		foreach ($extensions as $extension) {
			if (in_array($extension['code'], $available)) {
				$installed[] = $extension['code'];
			} else {
				$this->model_setting_extension->uninstall('other', $extension['code']);
			}
		}

		$data['extensions'] = [];

		if ($results) {
			foreach ($results as $result) {
				$extension = substr($result['path'], 0, strpos($result['path'], '/'));

				$code = basename($result['path'], '.php');

				$this->load->language('extension/' . $extension . '/other/' . $code, $code);

				$data['extensions'][] = [
					'name'      => $this->language->get($code . '_heading_title'),
					'status'    => $this->config->get('other_' . $code . '_status') ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
					'install'   => $this->url->link('extension/other|install', 'user_token=' . $this->session->data['user_token'] . '&extension=' . $extension . '&code=' . $code),
					'uninstall' => $this->url->link('extension/other|uninstall', 'user_token=' . $this->session->data['user_token'] . '&extension=' . $extension . '&code=' . $code),
					'installed' => in_array($code, $installed),
					'edit'      => $this->url->link('extension/' . $extension . '/other/' . $code, 'user_token=' . $this->session->data['user_token'])
				];
			}
		}

		$data['promotion'] = $this->load->controller('marketplace/promotion');

		return $this->load->view('extension/other', $data);
	}

	public function install(): void {
		$this->load->language('extension/other');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/other')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('setting/extension');

			$this->model_setting_extension->install('other', $this->request->get['extension'], $this->request->get['code']);

			$this->load->model('user/user_group');

			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/' . $this->request->get['extension'] . '/other/' . $this->request->get['code']);
			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/' . $this->request->get['extension'] . '/other/' . $this->request->get['code']);

			// Call install method if it exists
			$this->load->controller('extension/' . $this->request->get['extension'] . '/other/' . $this->request->get['code'] . '|install');

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function uninstall(): void {
		$this->load->language('extension/other');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/other')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('setting/extension');

			$this->model_setting_extension->uninstall('other', $this->request->get['code']);

			// Call uninstall method if it exists
			$this->load->controller('extension/' . $this->request->get['extension'] . '/other/' . $this->request->get['code'] . '|uninstall');

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}