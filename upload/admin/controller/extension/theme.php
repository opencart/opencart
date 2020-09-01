<?php
namespace Application\Controller\Extension;
class Theme extends \System\Engine\Controller {
	private $error = [];

	public function index() {
		$this->load->language('extension/theme');

		$this->load->model('setting/extension');

		$this->getList();
	}

	public function install() {
		$this->load->language('extension/theme');

		$this->load->model('setting/extension');

		if ($this->validate()) {
			$this->model_setting_extension->install('theme', $this->request->get['extension']);

			$this->load->model('user/user_group');

			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/theme/' . $this->request->get['extension']);
			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/theme/' . $this->request->get['extension']);

			// Call install method if it exsits
			$this->load->controller('extension/theme/' . $this->request->get['extension'] . '/install');

			$this->session->data['success'] = $this->language->get('text_success');
		}

		$this->getList();
	}

	public function uninstall() {
		$this->load->language('extension/theme');

		$this->load->model('setting/extension');

		if ($this->validate()) {
			$this->model_setting_extension->uninstall('theme', $this->request->get['extension']);

			// Call uninstall method if it exsits
			$this->load->controller('extension/theme/' . $this->request->get['extension'] . '/uninstall');

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

		$results = $this->model_setting_extension->getPaths('%/admin/controller/theme/%.php');

		foreach ($results as $result) {
			$installed[] = basename($result['path'], '.php');
		}

		$extensions = $this->model_setting_extension->getInstalled('theme');

		foreach ($extensions as $key => $value) {
			if (!in_array($value, $extensions)) {
				$this->model_setting_extension->uninstall('theme', $value);

				unset($extensions[$key]);
			}
		}

		$this->load->model('setting/store');
		$this->load->model('setting/setting');

		$stores = $this->model_setting_store->getStores();

		$data['extensions'] = [];

		if ($results) {
			foreach ($results as $result) {
				$code = substr($result['path'], 0, strpos('/'));

				$extension = basename($result['path'], '.php');

				$this->load->language('extension/theme/' . $extension, $extension);

				$store_data = [];

				$store_data[] = [
					'name'   => $this->config->get('config_name'),
					'edit'   => $this->url->link('extension/theme/' . $extension, 'user_token=' . $this->session->data['user_token'] . '&store_id=0'),
					'status' => $this->config->get('theme_' . $extension . '_status') ? $this->language->get('text_enabled') : $this->language->get('text_disabled')
				];

				foreach ($stores as $store) {
					$store_data[] = [
						'name'   => $store['name'],
						'edit'   => $this->url->link('extension/theme/' . $extension, 'user_token=' . $this->session->data['user_token'] . '&store_id=' . $store['store_id']),
						'status' => $this->model_setting_setting->getValue('theme_' . $extension . '_status', $store['store_id']) ? $this->language->get('text_enabled') : $this->language->get('text_disabled')
					];
				}

				$data['extensions'][] = [
					'name'      => $this->language->get($extension . '_heading_title'),
					'install'   => $this->url->link('extension/theme/install', 'user_token=' . $this->session->data['user_token'] . '&extension=' . $extension),
					'uninstall' => $this->url->link('extension/theme/uninstall', 'user_token=' . $this->session->data['user_token'] . '&extension=' . $extension),
					'installed' => in_array($extension, $extensions),
					'store'     => $store_data
				];
			}
		}

		$data['promotion'] = $this->load->controller('extension/promotion');

		$this->response->setOutput($this->load->view('extension/theme', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/theme')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}