<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Image
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Image extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generates customer group task list.
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/image');

		$this->load->model('setting/task');

		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($stores as $store) {
			foreach ($languages as $language) {
				$task_data = [
					'code'   => 'image',
					'action' => 'task/catalog/image.list',
					'args'   => [
						'store_id'    => $store['store_id'],
						'language_id' => $language['language_id']
					]
				];

				$this->model_setting_task->addTask($task_data);
			}
		}

		return ['success' => $this->language->get('text_success')];
	}
}
