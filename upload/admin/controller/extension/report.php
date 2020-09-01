<?php
namespace Application\Controller\Extension;
class Report extends \System\Engine\Controller {
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
			$this->model_setting_extension->install('report', $this->request->get['extension']);

			$this->load->model('user/user_group');

			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/report/' . $this->request->get['extension']);
			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/report/' . $this->request->get['extension']);

			$this->load->controller('extension/report/' . $this->request->get['extension'] . '/install');

			$this->session->data['success'] = $this->language->get('text_success');
		}

		$this->getList();
	}

	public function uninstall() {
		$this->load->language('extension/report');

		$this->load->model('setting/extension');

		if ($this->validate()) {
			$this->model_setting_extension->uninstall('report', $this->request->get['extension']);

			$this->load->controller('extension/report/' . $this->request->get['extension'] . '/uninstall');

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

		$installed = [];

		$results = $this->model_setting_extension->getPaths('%/admin/controller/report/%.php');

		foreach ($results as $result) {
			$installed[] = basename($result['path'], '.php');
		}

		$extensions = $this->model_setting_extension->getInstalled('report');

		foreach ($extensions as $key => $value) {
			if (!in_array($value, $extensions)) {
				$this->model_setting_extension->uninstall('report', $value);

				unset($extensions[$key]);
			}
		}

		$data['extensions'] = [];

		if ($results) {
			foreach ($results as $result) {
				$code = substr($result['path'], 0, strpos('/'));

				$extension = basename($result['path'], '.php');

				$this->load->language('extension/report/' . $extension, $extension);

				$data['extensions'][] = [
					'name'       => $this->language->get($extension . '_heading_title'),
					'status'     => $this->config->get('report_' . $extension . '_status') ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
					'sort_order' => $this->config->get('report_' . $extension . '_sort_order'),
					'install'    => $this->url->link('extension/report/install', 'user_token=' . $this->session->data['user_token'] . '&extension=' . $extension),
					'uninstall'  => $this->url->link('extension/report/uninstall', 'user_token=' . $this->session->data['user_token'] . '&extension=' . $extension),
					'installed'  => in_array($extension, $extensions),
					'edit'       => $this->url->link('extension/report/' . $extension, 'user_token=' . $this->session->data['user_token'])
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