<?php
namespace Opencart\Catalog\Controller\Extension\Opencart\Module;
/**
 * Class Topic
 *
 * @package Opencart\Catalog\Controller\Extension\Opencart\Module
 */
class Topic extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return string
	 */
	public function index(): string {
		$this->load->language('extension/opencart/module/topic');

		if (isset($this->request->get['topic_id'])) {
			$data['topic_id'] = (int)$this->request->get['topic_id'];
		} else {
			$data['topic_id'] = 0;
		}

		$remove = [
			'route',
			'user_token',
			'code',
			'page'
		];

		$url = http_build_query(array_diff_key($this->request->get, array_flip($remove)));

		// Topics
		$data['topics'] = [];

		$this->load->model('cms/topic');

		// Article
		$this->load->model('cms/article');

		$topics = $this->model_cms_topic->getTopics();

		if ($topics) {
			$data['topics'][] = [
				'topic_id' => 0,
				'name'     => $this->language->get('text_all') . ($this->config->get('config_article_count') ? ' (' . $this->model_cms_article->getTotalArticles() . ')' : ''),
				'href'     => $this->url->link('cms/topic', 'language=' . $this->config->get('config_language') . $url)
			];

			foreach ($topics as $topic) {
				$data['topics'][] = [
					'topic_id' => $topic['topic_id'],
					'name'     => $topic['name'] . ($this->config->get('config_article_count') ? ' (' . $this->model_cms_article->getTotalArticles(['filter_topic_id' => $data['topic_id']]) . ')' : ''),
					'href'     => $this->url->link('cms/topic', 'language=' . $this->config->get('config_language') . '&topic_id=' . $topic['topic_id'] . $url)
				];
			}

			return $this->load->view('extension/opencart/module/topic', $data);
		}

		return '';
	}
}
