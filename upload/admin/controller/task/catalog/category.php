<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Category
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Category extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generates information task list.
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/category');

		$this->load->model('setting/task');

		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();


	}

	public function clear(array $args = []): array {
		$this->load->language('task/catalog/category');


	}
}
