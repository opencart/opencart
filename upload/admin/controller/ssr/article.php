<?php
namespace Opencart\Admin\Controller\Ssr;
/**
 * Class Article
 *
 * @package Opencart\Admin\Controller\Ssr
 */
class Article extends \Opencart\System\Engine\Controller {
	/**
	 * Generate
	 *
	 * @return void
	 */
	public function index() {
		$this->load->language('ssr/article');

		$json = [];

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		if (!$this->user->hasPermission('modify', 'ssr/article')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Languages
			$this->load->model('localisation/language');

			$languages = $this->model_localisation_language->getLanguages();

			$path = [];

			$directories = DIR_CATALOG . 'view/data/cms/';


			//foreach ($directory)


			$limit = 5;


			$this->load->model('cms/article');


			// Total Articles
			$article_total = $this->model_cms_article->getTotalArticles();

			$start = ($page - 1) * $limit;
			$end = $start > ($article_total - $limit) ? $article_total : ($start + $limit);

			$filter_data = [
				'start' => $start,
				'limit' => $limit
			];

			$articles = $this->model_cms_article->getArticles($filter_data);

			foreach ($articles as $article) {
				if ($article['status']) {

					echo 'hi';

					$descriptions = $this->model_cms_article->getDescriptions($article['article_id']);

					foreach ($descriptions as $description) {
						if (isset($languages[$description['language_id']])) {
							$code = preg_replace('/[^A-Z0-9\._-]/i', '', $languages[$description['language_id']]['code']);

							$file = DIR_CATALOG . 'view/data/cms/article.' . (int)$article['article_id'] . '.' . $code . '.json';

							if (!file_put_contents($file, json_encode($description + $article))) {
								$json['error'] = $this->language->get('error_file');
							}
						}
					}
				}
			}
		}

		if (!$json) {
			$json['text'] = sprintf($this->language->get('text_article'), $start, $end, $article_total);

			if ($end < $article_total) {
				$json['next'] = $this->url->link('ssr/article', 'user_token=' . $this->session->data['user_token'] . '&page=' . ($page + 1), true);
			} else {
				$json['success'] = $this->language->get('text_success');
			}
		}

		print_r($json);
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function template() {

	}

	public function image() {

	}
}