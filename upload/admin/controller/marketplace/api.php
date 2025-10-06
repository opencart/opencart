<?php
namespace Opencart\Admin\Controller\Marketplace;
/**
 * Class API
 *
 * @package Opencart\Admin\Controller\Marketplace
 */
class Api extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('marketplace/api');

		$data['user_token'] = $this->session->data['user_token'];

		$this->response->setOutput($this->load->view('marketplace/api', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('marketplace/api');

		$json = [];

		if (!$this->user->hasPermission('modify', 'marketplace/api')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		$required = [
			'opencart_username' => '',
			'opencart_secret'   => ''
		];

		$post_info = $this->request->post + $required;

		if (!$post_info['opencart_username']) {
			$json['error']['username'] = $this->language->get('error_username');
		}

		if (!$post_info['opencart_secret']) {
			$json['error']['secret'] = $this->language->get('error_secret');
		}

		if (!$json) {
			// Setting
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('opencart', $post_info);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
