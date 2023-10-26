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




		if (!$json) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('other_cloud', $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
