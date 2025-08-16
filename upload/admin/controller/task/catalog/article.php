<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Article
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Article extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generates article task list.
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/article');

		$this->load->model('setting/task');

		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		$sorts = [];

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

		foreach ($stores as $store) {
			foreach ($languages as $language) {
				foreach ($sorts as $sort) {
					$task_data = [
						'code'   => 'article',
						'action' => 'task/catalog/article.list',
						'args'   => [
							'store_id'    => $store['store_id'],
							'language_id' => $language['language_id'],
							'sort'        => $sort['sort'],
							'order'       => $sort['order'],
							'limit'       => $this->config->get('config_pagination')
						]
					];

					$this->model_setting_task->addTask($task_data);
				}

				$task_data = [
					'code'   => 'article',
					'action' => 'task/catalog/article.info',
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

	/**
	 * List
	 *
	 * Generates article list file.
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/catalog/article');

		$this->load->model('setting/task');

		// Store
		$this->load->model('setting/store');

		$store_info = $this->model_setting_store->getStore((int)$args['store_id']);

		if (!$store_info) {
			return ['error' => $this->language->get('error_store')];
		}

		// Language
		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage((int)$args['language_id']);

		if (!$language_info) {
			return ['error' => $this->language->get('error_language')];
		}

		// Currency
		$this->load->model('localisation/currency');

		$currency_info = $this->model_localisation_currency->getCurrency((int)$args['currency_id']);

		if (!$currency_info) {
			return ['error' => $this->language->get('error_currency')];
		}

		// 1. Create a store instance using loader class to call controllers, models, views, libraries.
		$this->load->model('setting/store');

		$store = $this->model_setting_store->createStoreInstance($store_info['store_id'], $language_info['code'], $currency_info['code']);

		// 2. Remove the unneeded keys.
		$request_data = $this->request->get;

		unset($request_data['user_token']);

		// 3. Add the request GET vars.
		$store->request->get = $request_data;

		$store->request->get['route'] = 'api/order';

		// 4. Add the request POST var
		$store->request->post = $this->request->post;

		// 5. Call the required API controller.
		$store->load->controller($store->request->get['route']);

		// 6. Call the required API controller and get the output.
		$output = $store->response->getOutput();

		// 7. Clean up data by clearing cart.
		$store->cart->clear();

		// 8. Deleting the current session, so we are not creating infinite sessions.
		$store->session->destroy();




		

		$start = ($page - 1) * $limit;
		$end = $start > ($article_total - $limit) ? $article_total : ($start + $limit);

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => $start,
			'limit' => $limit
		];
		// Total Attributes
		$article_total = $this->model_catalog_attribute->getTotalArticles();
		$article_total = $this->model_cms_article->getTotalArticles();

		$articles = $this->model_cms_article->getArticles($filter_data);

		foreach ($articles as $article) {
			if ($article['status']) {
				$descriptions = $this->model_cms_article->getDescriptions($article['article_id']);

				$stores = $this->model_cms_article->getStores($article['article_id']);

				foreach ($descriptions as $description) {
					if (isset($languages[$description['language_id']])) {
						if (!file_put_contents($directory . 'article.' . (int)$article['article_id'] . '.' . $languages[$description['language_id']] . '.json', json_encode($description + $article))) {
							$json['error'] = $this->language->get('error_file');
						}
					}
				}
			}
		}


		if (!$json) {
			$json['text'] = sprintf($this->language->get('text_article'), $start ?: 1, $end, $article_total);

			if ($end < $article_total) {
				$json['next'] = $this->url->link('task/catalog/article', 'user_token=' . $this->session->data['user_token'] . '&page=' . ($page + 1), true);
			} else {
				$json['success'] = $this->language->get('text_success');
			}
		}

		return ['success' => sprintf($this->language->get('text_info'), $store_info['name'], $language_info['name'], $country_info['name'])];
	}

	/**
	 * Info
	 *
	 * Generates article information.
	 *
	 * @return array
	 */
	public function info(array $args = []): array {
		$this->load->language('task/catalog/article');

		$this->load->model('cms/article');

		$article_info = $this->model_cms_article->getArticles((int)$args['article_id']);

		if (!$article_info) {
			return ['error' => $this->language->get('error_article')];
		}

		$this->load->model('setting/store');

		$store_info = $this->model_setting_store->getStore((int)$args['store_id']);

		if (!$store_info) {
			return ['error' => $this->language->get('error_store')];
		}

		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage((int)$args['language_id']);

		if (!$language_info) {
			return ['error' => $this->language->get('error_language')];
		}


		if ($information['status']) {
			$description_info = $this->model_catalog_information->getDescription($information['information_id'], $language_info['language_id']);

			if ($description_info) {
				$information_data[$information['information_id']] = $description_info + $information;
			}
		}



		$zone_description_info = $this->model_cms_article->getDescription((int)$zone['zone_id'], (int)$language_info['language_id']);



		$base = DIR_CATALOG . 'view/data/';
		$directory = parse_url($store_info['url'], PHP_URL_HOST) . '/' . $language_info['code'] . '/localisation/';
		$filename = 'country-' . $args['country_id'] . '.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($country_info + ['zone' => $zone_data]))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_info'), $store_info['name'], $language_info['name'], $country_info['name'])];

	}


	public function rating(): array {
		$this->load->language('task/catalog/article');


		$this->load->language('cms/article');

		$json = [];

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		if (!$this->user->hasPermission('modify', 'cms/article')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$limit = 100;

			// Articles
			$filter_data = [
				'sort'  => 'date_added',
				'order' => 'ASC',
				'start' => ($page - 1) * $limit,
				'limit' => $limit
			];

			$this->load->model('cms/article');

			$results = $this->model_cms_article->getArticles($filter_data);

			foreach ($results as $result) {
				$like = 0;
				$dislike = 0;

				$ratings = $this->model_cms_article->getRatings($result['article_id']);

				foreach ($ratings as $rating) {
					if ($rating['rating'] == 1) {
						$like = $rating['total'];
					}

					if ($rating['rating'] == 0) {
						$dislike = $rating['total'];
					}
				}

				$this->model_cms_article->editRating($result['article_id'], $like - $dislike);
			}

			// Total Articles
			$article_total = $this->model_cms_article->getTotalArticles();

			$start = ($page - 1) * $limit;
			$end = ($start > ($article_total - $limit)) ? $article_total : ($start + $limit);

			if ($end < $article_total) {
				$json['text'] = sprintf($this->language->get('text_next'), $start ?: 1, $end, $article_total);

				$json['next'] = $this->url->link('cms/article.rating', 'user_token=' . $this->session->data['user_token'] . '&page=' . ($page + 1), true);
			} else {
				$json['success'] = $this->language->get('text_success');

				$json['next'] = '';
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));







		return ['success' => $this->language->get('text_success')];
	}

	public function clear(array $args = []): array {
		$this->load->language('task/catalog/article');

		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($stores as $store) {
			foreach ($languages as $language) {
				$base = DIR_CATALOG . 'view/data/';
				$directory = parse_url($store['url'], PHP_URL_HOST) . '/' . $language['code'] . '/cms/';

				$file = $base . $directory . 'article.json';

				if (is_file($file)) {
					unlink($file);
				}
			}
		}

		return ['success' => $this->language->get('text_clear')];
	}
}
