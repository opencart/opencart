<?php
namespace Opencart\Catalog\Controller\cms;
/**
 * Class Blog
 *
 * @package Opencart\Catalog\Controller\Cms
 */
class Blog extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
	public function index(): void {
		$this->load->language('cms/blog');

		if (isset($this->request->get['search'])) {
			$search = (string)$this->request->get['search'];
		} else {
			$search = '';
		}

		if (isset($this->request->get['blog_category_id'])) {
			$blog_category_id = (int)$this->request->get['blog_category_id'];
		} else {
			$blog_category_id = 0;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'b.date_added';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
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

		if (isset($this->request->get['search'])) {
			$url .= '&search=' . (string)$this->request->get['search'];
		}

		if (isset($this->request->get['blog_category_id'])) {
			$url .= '&blog_category_id=' . (int)$this->request->get['blog_category_id'];
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
			'text' => $this->language->get('text_blog'),
			'href' => $this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . $url)
		];

		$this->load->model('cms/blog_category');
		$this->load->model('tool/image');

		$blog_category_info = $this->model_cms_blog_category->getBlogCategory($blog_category_id);

		if ($blog_category_info) {
			$this->document->setTitle($blog_category_info['meta_title']);
			$this->document->setDescription($blog_category_info['meta_description']);
			$this->document->setKeywords($blog_category_info['meta_keyword']);

			$data['breadcrumbs'][] = [
				'text' => $blog_category_info['name'],
				'href' => $this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . $url)
			];

			$data['heading_title'] = $blog_category_info['name'];
			$data['description'] = html_entity_decode($blog_category_info['description'], ENT_QUOTES, 'UTF-8');

			if (is_file(DIR_IMAGE . html_entity_decode($blog_category_info['image'], ENT_QUOTES, 'UTF-8'))) {
				$data['thumb'] = $this->model_tool_image->resize(html_entity_decode($blog_category_info['image'], ENT_QUOTES, 'UTF-8'), $this->config->get('config_image_blog_width'), $this->config->get('config_image_blog_height'));
			} else {
				$data['thumb'] = '';
			}
		} else {
			$this->document->setTitle($this->language->get('heading_title'));

			$data['heading_title'] = $this->language->get('heading_title');
			$data['description'] = '';
			$data['thumb'] = '';
		}

		$limit = 20;

		$data['blogs'] = [];

		$filter_data = [
			'filter_search'           => $search,
			'filter_blog_category_id' => $blog_category_id,
			'sort'                    => $sort,
			'order'                   => $order,
			'start'                   => ($page - 1) * $limit,
			'limit'                   => $limit
		];

		$this->load->model('cms/blog');

		$blog_total = $this->model_cms_blog->getTotalBlogs($filter_data);

		$results = $this->model_cms_blog->getBlogs($filter_data);

		foreach ($results as $result) {
			if (is_file(DIR_IMAGE . html_entity_decode($result['image'], ENT_QUOTES, 'UTF-8'))) {
				$image = $this->model_tool_image->resize(html_entity_decode($result['image'], ENT_QUOTES, 'UTF-8'), $this->config->get('config_image_blog_width'), $this->config->get('config_image_blog_height'));
			} else {
				$image = '';
			}

			$data['blogs'][] = [
				'blog_id'     => $result['blog_id'],
				'thumb'       => $image,
				'name'        => $result['name'],
				'description' => oc_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('config_product_description_length')) . '..',
				'href'        => $this->url->link('cms/blog.info', 'language=' . $this->config->get('config_language') . '&blog_id=' . $result['blog_id'] . $url)
			];
		}

		$url = '';

		if (isset($this->request->get['search'])) {
			$url .= '&search=' . $this->request->get['search'];
		}

		if (isset($this->request->get['blog_category_id'])) {
			$url .= '&blog_category_id=' . $this->request->get['blog_category_id'];
		}

		$data['sorts'] = [];

		$data['sorts'][] = [
			'text'  => $this->language->get('text_name_asc'),
			'value' => 'bd.name-ASC',
			'href'  => $this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . '&sort=bd.name&order=ASC' . $url)
		];

		$data['sorts'][] = [
			'text'  => $this->language->get('text_name_desc'),
			'value' => 'bd.name-DESC',
			'href'  => $this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . '&sort=bd.name&order=DESC' . $url)
		];

		$data['sorts'][] = [
			'text'  => $this->language->get('text_date_added_asc'),
			'value' => 'b.date_added-ASC',
			'href'  => $this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . '&sort=b.date_added&order=ASC' . $url)
		];

		$data['sorts'][] = [
			'text'  => $this->language->get('text_date_added_desc'),
			'value' => 'b.date_added-DESC',
			'href'  => $this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . '&sort=b.date_added&order=DESC' . $url)
		];

		$url = '';

		if (isset($this->request->get['search'])) {
			$url .= '&search=' . $this->request->get['search'];
		}

		if (isset($this->request->get['blog_category_id'])) {
			$url .= '&blog_category_id=' . $this->request->get['blog_category_id'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $blog_total,
			'page'  => $page,
			'limit' => $limit,
			'url'   => $this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($blog_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($blog_total - $limit)) ? $blog_total : ((($page - 1) * $limit) + $limit), $blog_total, ceil($blog_total / $limit));

		// http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
		if ($page == 1) {
			$this->document->addLink($this->url->link('cms/blog', 'language=' . $this->config->get('config_language')), 'canonical');
		} else {
			$this->document->addLink($this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . '&page='. $page), 'canonical');
		}

		if ($page > 1) {
			$this->document->addLink($this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . (($page - 2) ? '&page='. ($page - 1) : '')), 'prev');
		}

		if (ceil($blog_total / $limit) > $page) {
			$this->document->addLink($this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . '&page='. ($page + 1)), 'next');
		}

		$data['search'] = $search;
		$data['blog_category_id'] = $blog_category_id;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('cms/blog_list', $data));
	}

	public function info(): void {
		$this->load->language('cms/blog');

		if (isset($this->request->get['blog_id'])) {
			$blog_id = (int)$this->request->get['blog_id'];
		} else {
			$blog_id = 0;
		}

		if (isset($this->request->get['blog_category_id'])) {
			$blog_category_id = (int)$this->request->get['blog_category_id'];
		} else {
			$blog_category_id = 0;
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
		];

		$url = '';

		if (isset($this->request->get['search'])) {
			$url .= '&search=' . $this->request->get['search'];
		}

		if (isset($this->request->get['blog_category_id'])) {
			$url .= '&blog_category_id=' . $this->request->get['blog_category_id'];
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
			'text' => $this->language->get('text_blog'),
			'href' => $this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . $url)
		];

		$this->load->model('cms/blog_category');

		$blog_category_info = $this->model_cms_blog_category->getBlogCategory($blog_category_id);

		if ($blog_category_info) {
			$data['breadcrumbs'][] = [
				'text' => $blog_category_info['name'],
				'href' => $this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . $url)
			];
		}

		$this->load->model('cms/blog');

		$blog_info = $this->model_cms_blog->getBlog($blog_id);

		if ($blog_info) {
			$this->document->setTitle($blog_info['meta_title']);
			$this->document->setDescription($blog_info['meta_description']);
			$this->document->setKeywords($blog_info['meta_keyword']);

			$data['breadcrumbs'][] = [
				'text' => $blog_info['title'],
				'href' => $this->url->link('cms/blog.info', 'language=' . $this->config->get('config_language') . '&blog_id=' .  $blog_id . $url)
			];

			$data['heading_title'] = $blog_info['name'];

			$data['description'] = html_entity_decode($blog_info['description'], ENT_QUOTES, 'UTF-8');

			$data['continue'] = $this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . $url);

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('cms/blog_info', $data));
		} else {
			$data['breadcrumbs'][] = [
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('cms/blog.info', 'language=' . $this->config->get('config_language') . '&blog_id=' . $blog_id . $url)
			];

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['continue'] = $this->url->link('common/home', 'language=' . $this->config->get('config_language') . $url);

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}
}
