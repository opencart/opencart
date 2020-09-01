<?php
namespace Application\Controller\Extension;
class Payment extends \System\Engine\Controller {
	private $error = [];

	public function index() {
		$this->load->language('extension/payment');
		
		$this->load->model('setting/extension');

		$this->getList();
	}

	public function install() {
		$this->load->language('extension/payment');

		$this->load->model('setting/extension');

		if ($this->validate()) {
			$this->model_setting_extension->install('payment', $this->request->get['extension']);

			$this->load->model('user/user_group');

			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/payment/' . $this->request->get['extension']);
			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/payment/' . $this->request->get['extension']);

			// Call install method if it exsits
			$this->load->controller('extension/payment/' . $this->request->get['extension'] . '/install');

			$this->session->data['success'] = $this->language->get('text_success');
		}

		$this->getList();
	}

	public function uninstall() {
		$this->load->language('extension/payment');

		$this->load->model('setting/extension');

		if ($this->validate()) {
			$this->model_setting_extension->uninstall('payment', $this->request->get['extension']);

			// Call uninstall method if it exsits
			$this->load->controller('extension/payment/' . $this->request->get['extension'] . '/uninstall');

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

		$results = $this->model_setting_extension->getPaths('%/admin/controller/payment/%.php');

		foreach ($results as $result) {
			$installed[] = basename($result['path'], '.php');
		}

		$extensions = $this->model_setting_extension->getInstalled('payment');

		foreach ($extensions as $key => $value) {
			if (!in_array($value, $extensions)) {
				$this->model_setting_extension->uninstall('payment', $value);

				unset($extensions[$key]);
			}
		}

		$data['extensions'] = [];

		if ($results) {
			foreach ($results as $result) {
				$code = substr($result['path'], 0, strpos('/'));

				$extension = basename($result['path'], '.php');

				$this->load->language('extension/payment/' . $extension, $extension);

				$text_link = $this->language->get($extension . '_text_' . $extension);

				if ($text_link != $extension . '_text_' . $extension) {
					$link = $text_link;
				} else {
					$link = '';
				}

				$data['extensions'][] = [
					'name'       => $this->language->get($extension . '_heading_title'),
					'link'       => $link,
					'status'     => $this->config->get('payment_' . $extension . '_status') ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
					'sort_order' => $this->config->get('payment_' . $extension . '_sort_order'),
					'install'    => $this->url->link('extension/payment/install', 'user_token=' . $this->session->data['user_token'] . '&extension=' . $extension),
					'uninstall'  => $this->url->link('extension/payment/uninstall', 'user_token=' . $this->session->data['user_token'] . '&extension=' . $extension),
					'installed'  => in_array($extension, $extensions),
					'edit'       => $this->url->link('extension/payment/' . $extension, 'user_token=' . $this->session->data['user_token'])
				];
			}
		}

		$data['promotion'] = $this->load->controller('extension/promotion');

		$this->response->setOutput($this->load->view('extension/payment', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}