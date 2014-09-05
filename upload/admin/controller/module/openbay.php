<?php
class ControllerModuleOpenbay extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('module/openbay');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/openbaypro', 'token=' . $this->session->data['token'], 'SSL'),
		);

		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		$data['heading_title'] = $this->language->get('heading_title');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['text_installed'] = $this->language->get('text_installed');

		$data['header'] = $this->load->controller('common/header');
		$data['menu'] = $this->load->controller('common/menu');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/openbay.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/openbaypro')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	public function install() {
		$this->load->model('setting/setting');
		$this->load->model('tool/event');

		$this->model_user_user_group->addPermission($this->user->getId(), 'access', 'extension/openbay');
		$this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'extension/openbay');

		$settings = $this->model_setting_setting->getSetting('openbaymanager');
		$settings['openbay_menu'] = 1;
		$this->model_setting_setting->editSetting('openbaymanager', $settings);

		// register the event triggers
		$this->model_tool_event->addEvent('post.admin.delete.product', array(
				'type' => 'extension',
				'code' => 'openbay',
				'method' => 'eventDeleteProduct')
		);
		$this->model_tool_event->addEvent('post.admin.edit.product', array(
				'type' => 'extension',
				'code' => 'openbay',
				'method' => 'eventEditProduct')
		);
	}

	public function uninstall() {
		$this->load->model('setting/setting');
		$this->load->model('tool/event');

		$settings = $this->model_setting_setting->getSetting('openbaymanager');
		$settings['openbay_menu'] = 0;
		$this->model_setting_setting->editSetting('openbaymanager', $settings);

		// register the event triggers
		$this->model_tool_event->deleteEvent('post.admin.delete.product', array(
				'type' => 'extension',
				'code' => 'openbay',
				'method' => 'eventDeleteProduct')
		);
		$this->model_tool_event->deleteEvent('post.admin.edit.product', array(
				'type' => 'extension',
				'code' => 'openbay',
				'method' => 'eventEditProduct')
		);
	}
}