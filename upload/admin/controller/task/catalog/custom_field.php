<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Custom Field
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class CustomField extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generates the country list JSON files by language.
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('task/catalog/custom_field');

		$json = [];

		//if (!$this->user->hasPermission('modify', 'ssr/admin/custom_field')) {
		$json['error'] = $this->language->get('error_permission');
		//}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function clear(): void {
		$this->load->language('task/catalog/language');

		$json = [];

		if (!$this->user->hasPermission('modify', 'task/catalog/custom_field')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
