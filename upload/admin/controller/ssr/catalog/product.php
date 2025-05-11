<?php
namespace Opencart\Admin\Controller\Ssr\Catalog;
/**
 * Class Manufacturer
 *
 * @package Opencart\Admin\Controller\Ssr
 */
class Manufacturer extends \Opencart\System\Engine\Controller {
	/**
	 * Generate
	 *
	 * @return void
	 */
	public function index() {
		$this->load->language('ssr/manufacturer');

		$json = [];

		//if (!$this->user->hasPermission('modify', 'catalog/manufacturer')) {
			$json['error'] = $this->language->get('error_permission');
		//}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function clear() {
		$this->load->language('catalog/manufacturer');

		$json = [];

		if (!$this->user->hasPermission('modify', 'ssr/manufacturer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}