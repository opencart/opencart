<?php
namespace Opencart\Admin\Controller\Task\Admin;
/**
 * Class Weight Class
 *
 * @package Opencart\Admin\Controller\Ssr
 */
class WeightClass extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generates the country list JSON files by language.
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('task/admin/custom_field');

		$json = [];

		//if (!$this->user->hasPermission('modify', 'admin/custom_field')) {
		$json['error'] = $this->language->get('error_permission');
		//}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function clear(): void {
		$this->load->language('task/admin/language');

		$json = [];

		if (!$this->user->hasPermission('modify', 'admin/custom_field')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
