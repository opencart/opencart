<?php
namespace Opencart\Application\Controller\Extension;
class Report extends \Opencart\System\Engine\Controller {
	private $error = [];

	public function index() {
		$this->load->language('extension/report');

		$this->load->model('setting/extension');

		$this->getList();
	}

	public function install() {
		$this->load->language('extension/report');

		$this->load->model('setting/extension');

		if ($this->validate()) {
			$this->model_setting_extension->install('report', $this->request->get['extension'], $this->request->get['code']);

			$this->load->model('user/user_group');

			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/' . $this->request->get['extension'] . '/report/' . $this->request->get['code']);
			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/' . $this->request->get['extension'] . '/report/' . $this->request->get['code']);

			$this->load->controller('extension/' . $this->request->get['extension'] . '/report/' . $this->request->get['code'] . '/install');

			$this->session->data['success'] = $this->language->get('text_success');
		}

		$this->getList();
	}

	public function uninstall() {
		$this->load->language('extension/report');

		$this->load->model('setting/extension');

		if ($this->validate()) {
			$this->model_setting_extension->uninstall('report', $this->request->get['code']);

			$this->load->controller('extension/' . $this->request->get['extension'] . '/report/' . $this->request->get['code'] . '/uninstall');

			$this->session->data['success'] = $this->language->get('text_success');
		}

		$this->getList();
	}

	protected function getList() {
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

		$results = $this->model_setting_extension->getPaths('%/admin/controller/report/%.php');

		foreach ($results as $result) {
			$available[] = basename($result['path'], '.php');
		}

		$installed = [];

		$extensions = $this->model_setting_extension->getExtensionsByType('report');

		foreach ($extensions as $extension) {
			if (in_array($extension['code'], $available)) {
				$installed[] = $extension['code'];
			} else {
				$this->model_setting_extension->uninstall('report', $extension['code']);
			}
		}

		$data['extensions'] = [];

		if ($results) {
			foreach ($results as $result) {
				$extension = substr($result['path'], 0, strpos($result['path'], '/'));

				$code = basename($result['path'], '.php');

				$this->load->language('extension/' . $extension . '/report/' . $code, $code);

				$data['extensions'][] = [
					'name'       => $this->language->get($code . '_heading_title'),
					'status'     => $this->config->get('report_' . $code . '_status') ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
					'sort_order' => $this->config->get('report_' . $code . '_sort_order'),
					'install'    => $this->url->link('extension/report/install', 'user_token=' . $this->session->data['user_token'] . '&extension=' . $extension . '&code=' . $code),
					'uninstall'  => $this->url->link('extension/report/uninstall', 'user_token=' . $this->session->data['user_token'] . '&extension=' . $extension . '&code=' . $code),
					'installed'  => in_array($code, $installed),
					'edit'       => $this->url->link('extension/' . $extension . '/report/' . $code, 'user_token=' . $this->session->data['user_token'])
				];
			}
		}

		$data['promotion'] = $this->load->controller('extension/promotion');

		$this->response->setOutput($this->load->view('extension/report', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/report')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}