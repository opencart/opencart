<?php
namespace Opencart\Application\Controller\Extension\Opencart\Other;
class Cookie extends \Opencart\System\Engine\Controller {
	private $error = [];

	public function index() {
		$this->load->language('extension/opencart/other/cookie');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('other_cookie', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=other'));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=captcha')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/opencart/other/cookie', 'user_token=' . $this->session->data['user_token'])
		];

		$data['action'] = $this->url->link('extension/opencart/other/cookie', 'user_token=' . $this->session->data['user_token']);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=other');

		if (isset($this->request->post['other_cookie_status'])) {
			$data['other_cookie_status'] = $this->request->post['other_cookie_status'];
		} else {
			$data['other_cookie_status'] = $this->config->get('other_cookie_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/opencart/other/cookie', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/opencart/other/cookie')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function install() {
		if ($this->user->hasPermission('modify', 'extension/opencart/other/cookie')) {
			$code = 'cookie';
			$trigger = 'catalog/view/common/footer/after';
			$action = 'extension/opencart/other/cookie';
			$status = 1;
			$sort_order = 0;

			$this->load->model('setting/event');

			$this->model_setting_event->addEvent($code, $trigger, $action, $status, $sort_order);
		}
	}

	public function uninstall() {
		if ($this->user->hasPermission('modify', 'extension/opencart/other/cookie')) {
			$this->load->model('setting/event');

			$this->model_setting_event->deleteEventByCode('cookie');
		}
	}
}
