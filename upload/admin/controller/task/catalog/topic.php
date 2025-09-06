<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Topic
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Topic extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generates topic task list.
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/topic');

		// Store
		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		// Language
		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		// Topics
		$topics = [];

		$topics[] = 0;

		$this->load->model('cms/topic');

		$results = $this->model_cms_topic->getTopics();

		foreach ($results as $result) {
			$topics[] = $result['topic_id'];
		}

		// Sort Order
		$sorts = [];

		$sorts[] = [
			'sort'  => '',
			'order' => ''
		];

		$sorts[] = [
			'sort'  => 'date_added',
			'order' => 'ASC'
		];

		$sorts[] = [
			'sort'  => 'date_added',
			'order' => 'DESC'
		];

		$sorts[] = [
			'sort'  => 'rating',
			'order' => 'ASC'
		];

		$sorts[] = [
			'sort'  => 'rating',
			'order' => 'DESC'
		];

		$this->load->model('setting/task');

		$this->load->model('cms/article');

		foreach ($stores as $store) {
			foreach ($languages as $language) {



				$task_data = [
					'code'   => 'ssr',
					'action' => 'task/catalog/ssr',
					'args'   => [
						'route'       => 'cms/topic',
						'store_id'    => $store['store_id'],
						'language_id' => $language['language_id'],
						'sort'        => $sort['sort'],
						'order'       => $sort['order'],
						'page'        => $i
					]
				];

				$this->model_setting_task->addTask($task_data);




				foreach ($topics as $topic_id) {







					$filter_data = [
						'filter_topic_id'    => $topic_id,
						'filter_store_id'    => $store['store_id'],
						'filter_language_id' => $language['language_id'],
						'filter_status'      => true
					];

					$article_total = $this->model_cms_article->getTotalArticles($filter_data);

					$page_total = ceil($article_total / (int)$this->config->get('config_pagination'));

					foreach ($sorts as $sort) {
						for ($i = 1; $i <= $page_total; $i++) {
							$task_data = [
								'code'   => 'ssr',
								'action' => 'task/catalog/ssr',
								'args'   => [
									'route'       => 'cms/topic',
									'topic_id'    => $topic_id,
									'store_id'    => $store['store_id'],
									'language_id' => $language['language_id'],
									'sort'        => $sort['sort'],
									'order'       => $sort['order'],
									'page'        => $i
								]
							];

							$this->model_setting_task->addTask($task_data);
						}
					}
				}
			}
		}

		return ['success' => $this->language->get('text_success')];
	}
}
