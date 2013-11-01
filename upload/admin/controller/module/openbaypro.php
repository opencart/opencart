<?php
class ControllerModuleOpenbaypro extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('module/openbaypro');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/openbaypro', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['text_installed'] = $this->language->get('text_installed');

		$this->template = 'module/openbaypro.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
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

		$settings = $this->model_setting_setting->getSetting('openbaymanager');
		$settings['openbaymanager_show_menu'] = 1;
		$this->model_setting_setting->editSetting('openbaymanager', $settings);
	}

	public function uninstall() {
		$this->load->model('setting/setting');

		$settings = $this->model_setting_setting->getSetting('openbaymanager');
		$settings['openbaymanager_show_menu'] = 0;
		$this->model_setting_setting->editSetting('openbaymanager', $settings);
	}
}
?>