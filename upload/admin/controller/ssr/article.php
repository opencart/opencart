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

		$directory = DIR_CATALOG . 'view/data/cms/';

		if (!is_dir($directory) && !mkdir($directory, 0777)) {
			$json['error'] = $this->language->get('error_directory');
		}

		if (!$json) {
			$this->load->model('localisation/language');

			$languages = $this->model_localisation_language->getLanguages();

			$limit = 5;

			$this->load->model('cms/article');

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
						$content = json_encode($description + $article);

						echo $content;


						$file = $directory . 'article.' . (int)$article['article_id'] . '.' . $article['language_id'] . '.json';

						if (!file_put_contents($file, $content)) {
							$json['error'] = $this->language->get('error_file');
						}

					}
				}
			}
		}

		if (!$json) {
			$json['text'] = sprintf($this->language->get('text_article'), $start ?: 1, $end, $article_total);

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