<?php
class ControllerFeedOpenbaypro extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('feed/openbaypro');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL'),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('feed/openbay', 'token=' . $this->session->data['token'], 'SSL'),
		);

		$data['cancel'] = $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL');

		$data['heading_title'] = $this->language->get('heading_title');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['text_installed'] = $this->language->get('text_installed');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('feed/openbaypro.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/openbaypro')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function install() {
		$this->load->model('setting/setting');

		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/openbay');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/openbay');

		$settings = $this->model_setting_setting->getSetting('openbaypro');
		$settings['openbaypro_menu'] = 1;
		$settings['openbaypro_status'] = 1;
		$this->model_setting_setting->editSetting('openbaypro', $settings);

		// register the event triggers
		if (version_compare(VERSION, '2.0.1', '>=')) {
			$this->load->model('extension/event');
			$this->model_extension_event->addEvent('openbay', 'post.admin.product.delete', 'extension/openbay/eventDeleteProduct');
			$this->model_extension_event->addEvent('openbay', 'post.admin.product.edit', 'extension/openbay/eventEditProduct');
		} else {
			$this->load->model('tool/event');
			$this->model_tool_event->addEvent('openbay', 'post.product.delete', 'extension/openbay/eventDeleteProduct');
			$this->model_tool_event->addEvent('openbay', 'post.product.edit', 'extension/openbay/eventEditProduct');
		}
	}

	public function uninstall() {
		$this->load->model('setting/setting');

		$settings = $this->model_setting_setting->getSetting('openbaypro');
		$settings['openbaypro_menu'] = 0;
		$settings['openbaypro_status'] = 0;
		$this->model_setting_setting->editSetting('openbaypro', $settings);

		// delete the event triggers
		if (version_compare(VERSION, '2.0.1', '>=')) {
			$this->load->model('extension/event');

			$this->model_extension_event->deleteEvent('openbay');
		} else {
			$this->load->model('tool/event');

			$this->model_tool_event->deleteEvent('openbay');
		}
	}
}
