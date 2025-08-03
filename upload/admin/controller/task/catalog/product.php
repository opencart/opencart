<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Manufacturer
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Manufacturer extends \Opencart\System\Engine\Controller {
	/**
	 * Generate
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('task/catalog/manufacturer');

		$json = [];

		//if (!$this->user->hasPermission('modify', 'catalog/manufacturer')) {
			$json['error'] = $this->language->get('error_permission');
		//}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function clear(): void {
		$this->load->language('catalog/manufacturer');

		$json = [];

		if (!$this->user->hasPermission('modify', 'task/catalog/manufacturer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
