<?php
namespace Opencart\Admin\Controller\Ssr\Catalog;
/**
 * Class Topic
 *
 * @package Opencart\Admin\Controller\Ssr
 */
class Topic extends \Opencart\System\Engine\Controller {
	/**
	 * Generate
	 *
	 * @return void
	 */
	public function index() {
		$this->load->language('ssr/topic');

		$json = [];

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		if (!$this->user->hasPermission('modify', 'ssr/topic')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$directory = DIR_CATALOG . 'view/data/cms/';

		if (!is_dir($directory) && !mkdir($directory, 0777)) {
			$json['error'] = $this->language->get('error_directory');
		}

		if (!$json) {
			// Languages
			$this->load->model('localisation/language');

			$languages = $this->model_localisation_language->getLanguages();

			$limit = 5;

			// Total Topics
			$topic_total = $this->model_cms_topic->getTotalTopics();

			$start = ($page - 1) * $limit;
			$end = $start > ($topic_total - $limit) ? $topic_total : ($start + $limit);

			$filter_data = [
				'start' => $start,
				'limit' => $limit
			];

			$this->load->model('cms/topic');

			$topics = $this->model_cms_topic->getTopics($filter_data);

			foreach ($topics as $topic) {
				if ($topic['status']) {
					$descriptions = $this->model_cms_topic->getDescriptions($topic['topic_id']);

					foreach ($descriptions as $description) {
						if (isset($languages[$description['language_id']])) {
							$code = preg_replace('/[^A-Z0-9_-]/i', '', $languages[$description['language_id']]['code']);

							$file = DIR_CATALOG . 'view/data/cms/topic.' . (int)$topic['topic_id'] . '.' . $code . '.json';

							if (!file_put_contents($file, json_encode($description + $topic))) {
								$json['error'] = $this->language->get('error_file');
							}
						}
					}
				}
			}
		}

		if (!$json) {
			$json['text'] = sprintf($this->language->get('text_topic'), $start, $end, $topic_total);

			if ($end < $topic_total) {
				$json['next'] = $this->url->link('ssr/topic', 'user_token=' . $this->session->data['user_token'] . '&page=' . ($page + 1), true);
			} else {
				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}