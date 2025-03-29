<?php
namespace Opencart\Catalog\Controller\Cms;
/**
 * Class Blog
 *
 * @package Opencart\Catalog\Controller\Cms
 */
class Blog extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('cms/blog');

		if (isset($this->request->get['search'])) {
			$filter_search = (string)$this->request->get['search'];
		} else {
			$filter_search = '';
		}

		if (isset($this->request->get['tag'])) {
			$filter_tag = (string)$this->request->get['tag'];
		} else {
			$filter_tag = '';
		}

		if (isset($this->request->get['topic_id'])) {
			$filter_topic_id = (int)$this->request->get['topic_id'];
		} else {
			$filter_topic_id = 0;
		}

		if (isset($this->request->get['author'])) {
			$filter_author = (string)$this->request->get['author'];
		} else {
			$filter_author = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = (string)$this->request->get['sort'];
		} else {
			$sort = 'date_added';
		}

		if (isset($this->request->get['order'])) {
			$order = (string)$this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
		];

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_blog'),
			'href' => $this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . $url)
		];

		// Topic
		$this->load->model('cms/topic');

		$topic_info = $this->model_cms_topic->getTopic($filter_topic_id);

		if ($topic_info) {
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

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$data['breadcrumbs'][] = [
				'text' => $topic_info['name'],
				'href' => $this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . $url)
			];
		}

		if ($topic_info) {
			$this->document->setTitle($topic_info['meta_title']);
			$this->document->setDescription($topic_info['meta_description']);
			$this->document->setKeywords($topic_info['meta_keyword']);

			$data['heading_title'] = $topic_info['name'];
		} else {
			$this->document->setTitle($this->language->get('heading_title'));

			$data['heading_title'] = $this->language->get('heading_title');
		}

		if ($topic_info) {
			$data['description'] = html_entity_decode($topic_info['description'], ENT_QUOTES, 'UTF-8');
		} else {
			$data['description'] = '';
		}

		// Image
		$this->load->model('tool/image');

		if (!empty($topic_info['image']) && is_file(DIR_IMAGE . html_entity_decode($topic_info['image'], ENT_QUOTES, 'UTF-8'))) {
			$data['image'] = $this->model_tool_image->resize($topic_info['image'], $this->config->get('config_image_topic_width'), $this->config->get('config_image_topic_height'));
		} else {
			$data['image'] = '';
		}

		$limit = $this->config->get('config_pagination');

		// Articles
		$data['articles'] = [];

		$filter_data = [
			'filter_search'   => $filter_search,
			'filter_topic_id' => $filter_topic_id,
			'filter_author'   => $filter_author,
			'filter_tag'      => $filter_tag,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $limit,
			'limit'           => $limit
		];

		$this->load->model('cms/article');

		$results = $this->model_cms_article->getArticles($filter_data);

		foreach ($results as $result) {
			$description = trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')));

			if (oc_strlen($description) > $this->config->get('config_article_description_length')) {
				$description = oc_substr($description, 0, $this->config->get('config_article_description_length')) . '..';
			}

			if ($result['image'] && is_file(DIR_IMAGE . html_entity_decode($result['image'], ENT_QUOTES, 'UTF-8'))) {
				$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_article_width'), $this->config->get('config_image_article_height'));
			} else {
				$image = '';
			}

			$data['articles'][] = [
				'description'   => $description,
				'image'         => $image,
				'filter_author' => $this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . '&author=' . $result['author'] . $url),
				'comment_total' => $this->model_cms_article->getTotalComments($result['article_id'], ['parent_id' => 0]),
				'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'href'          => $this->url->link('cms/blog.info', 'language=' . $this->config->get('config_language') . '&article_id=' . $result['article_id'] . $url)
			] + $result;
		}

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
			$url .= '&author=' . (string)$this->request->get['author'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		// Total Articles
		$article_total = $this->model_cms_article->getTotalArticles($filter_data);

		// Pagination
		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $article_total,
			'page'  => $page,
			'limit' => $limit,
			'url'   => $this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($article_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($article_total - $limit)) ? $article_total : ((($page - 1) * $limit) + $limit), $article_total, ceil($article_total / $limit));

		$data['search'] = $filter_search;
		$data['topic_id'] = $filter_topic_id;

		$data['topics'] = $this->model_cms_topic->getTopics();

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
			$url .= '&author=' . (string)$this->request->get['author'];
		}

		$data['sorts'] = [];

		$data['sorts'][] = [
			'text'  => $this->language->get('text_date_added_asc'),
			'value' => 'date_added-ASC',
			'href'  => $this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . '&sort=date_added&order=ASC' . $url)
		];

		$data['sorts'][] = [
			'text'  => $this->language->get('text_date_added_desc'),
			'value' => 'date_added-DESC',
			'href'  => $this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . '&sort=date_added&order=DESC' . $url)
		];

		$data['sorts'][] = [
			'text'  => $this->language->get('text_rating_asc'),
			'value' => 'rating-ASC',
			'href'  => $this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . '&sort=rating&order=ASC' . $url)
		];

		$data['sorts'][] = [
			'text'  => $this->language->get('text_rating_desc'),
			'value' => 'rating-DESC',
			'href'  => $this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . '&sort=rating&order=DESC' . $url)
		];

		$data['sort'] = $sort;
		$data['order'] = $order;

		// https://developers.google.com/search/blog/2011/09/pagination-with-relnext-and-relprev
		if ($page == 1) {
			$this->document->addLink($this->url->link('cms/blog', 'language=' . $this->config->get('config_language')), 'canonical');
		} else {
			$this->document->addLink($this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . '&page=' . $page), 'canonical');
		}

		if ($page > 1) {
			$this->document->addLink($this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . (($page - 2) ? '&page=' . ($page - 1) : '')), 'prev');
		}

		if (ceil($article_total / $limit) > $page) {
			$this->document->addLink($this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . '&page=' . ($page + 1)), 'next');
		}

		$data['language'] = $this->config->get('config_language');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('cms/blog', $data));
	}

	/**
	 * Info
	 *
	 * @return \Opencart\System\Engine\Action|null
	 */
	public function info(): ?\Opencart\System\Engine\Action {
		$this->load->language('cms/blog');

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

		if ($article_info) {
			$this->document->setTitle($article_info['meta_title']);
			$this->document->setDescription($article_info['meta_description']);
			$this->document->setKeywords($article_info['meta_keyword']);

			$data['breadcrumbs'] = [];

			$data['breadcrumbs'][] = [
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
			];

			$data['breadcrumbs'][] = [
				'text' => $this->language->get('text_blog'),
				'href' => $this->url->link('cms/blog', 'language=' . $this->config->get('config_language'))
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
					'href' => $this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . $url)
				];
			}

			$data['breadcrumbs'][] = [
				'text' => $article_info['name'],
				'href' => $this->url->link('cms/blog.info', 'language=' . $this->config->get('config_language') . '&article_id=' . $article_id . $url)
			];

			$data['heading_title'] = $article_info['name'];

			// Image
			$this->load->model('tool/image');

			if (!empty($article_info['image']) && is_file(DIR_IMAGE . html_entity_decode($article_info['image'], ENT_QUOTES, 'UTF-8'))) {
				$data['image'] = $this->model_tool_image->resize($article_info['image'], $this->config->get('config_image_article_width'), $this->config->get('config_image_article_height'));
			} else {
				$data['image'] = '';
			}

			$data['description'] = html_entity_decode($article_info['description'], ENT_QUOTES, 'UTF-8');
			$data['author'] = $article_info['author'];
			$data['filter_author'] = $this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . '&author=' . $article_info['author']);
			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($article_info['date_added']));

			$data['tags'] = [];

			if ($article_info['tag']) {
				$tags = explode(',', trim($article_info['tag'], ','));

				foreach ($tags as $tag) {
					$data['tags'][] = [
						'tag'  => trim($tag),
						'href' => $this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . '&tag=' . trim($tag))
					];
				}
			}

			$data['comment'] = $this->config->get('config_comment_status') ? $this->load->controller('cms/comment') : '';
			$data['comment_total'] = $this->model_cms_article->getTotalComments($article_id, ['parent_id' => 0]);

			$data['continue'] = $this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . $url);

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('cms/blog_info', $data));
		} else {
			return new \Opencart\System\Engine\Action('error/not_found');
		}

		return null;
	}
}
