<?php
namespace Opencart\Admin\Controller\Extension\Opencart\Other;
/**
 * Class Cloud
 *
 * @package Opencart\Admin\Controller\Extension\Opencart\Other
 */
class Cloud extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
	public function index(): void {
		$this->load->language('extension/opencart/other/cloud');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=other')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/opencart/other/cloud', 'user_token=' . $this->session->data['user_token'])
		];

		$data['save'] = $this->url->link('extension/opencart/other/cloud.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=other');

		$data['other_cloud_key'] = $this->config->get('other_cloud_key');
		$data['other_cloud_secret'] = $this->config->get('other_cloud_secret');
		$data['other_cloud_status'] = $this->config->get('other_cloud_status');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/opencart/captcha/cloud', $data));
	}

	/**
	 * @return void
	 */
	public function save(): void {
		$this->load->language('extension/opencart/other/cloud');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/opencart/other/cloud')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if ((oc_strlen($this->request->post['other_cloud_key']) < 3) || (oc_strlen($this->request->post['other_cloud_key']) > 64)) {
			$json['error']['key'] = $this->language->get('error_key');
		}

		if ((oc_strlen($this->request->post['other_cloud_secret']) < 3) || (oc_strlen($this->request->post['other_cloud_secret']) > 64)) {
			$json['error']['secret'] = $this->language->get('error_secret');
		}

		if (!$json) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('other_cloud', $this->request->post);

			$this->load->model('setting/event');

			$this->model_setting_event->editStatusByCode('other_cloud', $this->request->post['other_cloud_status']);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * @return void
	 */
	public function install(): void {
		if ($this->user->hasPermission('modify', 'extension/opencart/other/cloud')) {
			$event_data = [
				'code'        => 'opencart_cloud',
				'description' => '',
				'trigger'     => 'extension/opencart/other/cloud',
				'action'      => 'opencart/opencart/other/cloud',
				'status'      => 0,
				'sort_order'  => 1
			];

			$this->load->model('setting/event');

			$this->model_setting_event->addEvent($event_data);
		}
	}

	/**
	 * @return void
	 */
	public function uninstall(): void {
		if ($this->user->hasPermission('modify', 'extension/opencart/other/cloud')) {
			$this->load->model('setting/event');

			$this->model_setting_event->deleteEventByCode('opencart_cloud');
		}
	}
}
