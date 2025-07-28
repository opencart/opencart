<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Custom Field
 *
 * @package Opencart\Admin\Controller\Ssr
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
		$this->load->language('ssr/catalog/custom_field');

		$json = [];

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function clear(): void {
		$this->load->language('ssr/catalog/language');

		$json = [];

		if (!$json) {
			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
