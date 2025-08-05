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
		$this->load->language('task/catalog/catalog/custom_field');


	}

	public function clear(array $args = []): array {
		$this->load->language('task/catalog/catalog/language');


	}
}
