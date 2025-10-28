<?php
namespace Opencart\Catalog\Controller\Cms;
/**
 * Class Article
 *
 * @package Opencart\Catalog\Controller\Cms
 */
class Article extends \Opencart\System\Engine\Controller {
	/**
	 * Info
	 *
	 * @return \Opencart\System\Engine\Action|null
	 */
	public function index() {
		$this->load->language('cms/article');

		if (isset($this->request->get['article_id'])) {
			$article_id = (int)$this->request->get['article_id'];
		} else {
			$article_id = 0;
		}

		if (isset($this->request->get['topic_id'])) {
			$topic_id = (int)$this->request->get['topic_id'];
		} else {
			$topic_id = 0;
		}

		// Article
		$this->load->model('cms/article');

		$article_info = $this->model_cms_article->getArticle($article_id);

		if (!$article_info) {
			return new \Opencart\System\Engine\Action('error/not_found');
		}

		$this->document->setTitle($article_info['meta_title']);
		$this->document->setDescription($article_info['meta_description']);
		$this->document->setKeywords($article_info['meta_keyword']);

		$this->document->addScript('catalog/view/javascript/comment.js');

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_blog'),
			'href' => $this->url->link('cms/topic', 'language=' . $this->config->get('config_language'))
		];

		$url = '';

		if (isset($this->request->get['search'])) {
			$url .= '&search=' . $this->request->get['search'];
		}

		if (isset($this->request->get['tag'])) {
			$url .= '&tag=' . $this->request->get['tag'];
		}

		if (isset($this->request->get['topic_id'])) {
			$url .= '&topic_id=' . $this->request->get['topic_id'];
		}

		if (isset($this->request->get['author'])) {
			$url .= '&author=' . $this->request->get['author'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		// Topic
		$this->load->model('cms/topic');

		$topic_info = $this->model_cms_topic->getTopic($topic_id);

		if ($topic_info) {
			$data['breadcrumbs'][] = [
				'text' => $topic_info['name'],
				'href' => $this->url->link('cms/topic', 'language=' . $this->config->get('config_language') . $url)
			];
		}

		$data['breadcrumbs'][] = [
			'text' => $article_info['name'],
			'href' => $this->url->link('cms/topic', 'language=' . $this->config->get('config_language') . '&article_id=' . $article_id . $url)
		];

		$data['heading_title'] = $article_info['name'];

		// Image
		$this->load->model('tool/image');

		if (!empty($article_info['image']) && is_file(DIR_IMAGE . html_entity_decode($article_info['image'], ENT_QUOTES, 'UTF-8'))) {
			$data['image'] = $this->config->get('config_url') . 'image/' . $article_info['image'];
		} else {
			$data['image'] = '';
		}

		$data['description'] = html_entity_decode($article_info['description'], ENT_QUOTES, 'UTF-8');
		$data['author'] = $article_info['author'];
		$data['filter_author'] = $this->url->link('cms/topic', 'language=' . $this->config->get('config_language') . '&author=' . $article_info['author']);
		$data['date_added'] = date($this->language->get('date_format_short'), strtotime($article_info['date_added']));

		$data['tags'] = [];

		if ($article_info['tag']) {
			$tags = explode(',', trim($article_info['tag'], ','));

			foreach ($tags as $tag) {
				$data['tags'][] = [
					'tag'  => trim($tag),
					'href' => $this->url->link('cms/topic', 'language=' . $this->config->get('config_language') . '&tag=' . trim($tag))
				];
			}
		}

		$data['comment'] = $this->config->get('config_comment_status') ? $this->load->controller('cms/comment') : '';
		$data['comment_total'] = $this->model_cms_article->getTotalComments($article_id, ['parent_id' => 0]);

		$data['continue'] = $this->url->link('cms/topic', 'language=' . $this->config->get('config_language') . $url);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('cms/article', $data));

		return null;
	}
}
