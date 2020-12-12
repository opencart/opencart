<?php
namespace Opencart\Application\Controller\Extension;
class Other extends \Opencart\System\Engine\Controller {
	private $error = [];

	public function index() {
		$this->load->language('extension/other');

		$this->load->model('setting/extension');

		$this->response->setOutput($this->getList());
	}

	public function install() {
		$this->load->language('extension/other');

		$this->load->model('setting/extension');

		if ($this->validate()) {
			$this->model_setting_extension->install('other', $this->request->get['extension'], $this->request->get['code']);

			$this->load->model('user/user_group');

			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/' . $this->request->get['extension'] . '/other/' . $this->request->get['code']);
			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/' . $this->request->get['extension'] . '/other/' . $this->request->get['code']);

			$this->load->controller('extension/' . $this->request->get['extension'] . '/other/' . $this->request->get['code'] . '|install');

			$this->session->data['success'] = $this->language->get('text_success');
		}

		$this->response->setOutput($this->getList());
	}

	public function uninstall() {
		$this->load->language('extension/other');

		$this->load->model('setting/extension');

		if ($this->validate()) {
			$this->model_setting_extension->uninstall('other', $this->request->get['code']);

			$this->load->controller('extension/' . $this->request->get['extension'] . '/other/' . $this->request->get['code'] . '|uninstall');

			$this->session->data['success'] = $this->language->get('text_success');
		}

		$this->response->setOutput($this->getList());
	}

	public function getList() {
		// Had top load again because the method is called directly.
		$this->load->language('extension/other');

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

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/other')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}