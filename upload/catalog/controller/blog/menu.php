<?php
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerBlogMenu extends Controller {
	public function index() {

		$this->load->language('blog/menu');
		
		$configblog_name = $this->config->get('configblog_name');
		
		if (!empty($configblog_name)) {
			$data['text_blog'] = $this->config->get('configblog_name');
		} else {
			$data['text_blog'] = $this->language->get('text_blog');
		}

		$data['text_all'] = $this->language->get('text_all');

		$data['blog'] = $this->url->link('blog/latest');

		// Menu
		$this->load->model('blog/category');

		$this->load->model('blog/article');

		$data['categories'] = array();

		$categories = $this->model_blog_category->getCategories(0);

		foreach ($categories as $category) {
			if ($category['top']) {
				// Level 2
				$children_data = array();

				$children = $this->model_blog_category->getCategories($category['blog_category_id']);

				foreach ($children as $child) {
					$filter_data = array(
						'filter_blog_category_id'  => $child['blog_category_id'],
						'filter_sub_category' => true
					);

					$children_data[] = array(
						'name'  => $child['name'] . ($this->config->get('configblog_article_count') ? ' (' . $this->model_blog_article->getTotalArticles($filter_data) . ')' : ''),
						'href'  => $this->url->link('blog/category', 'blog_category_id=' . $category['blog_category_id'] . '_' . $child['blog_category_id'])
					);
				}

				// Level 1
				$filter_data = array(
						'filter_blog_category_id'  => $category['blog_category_id']
					);
				
				$data['categories'][] = array(
					'name'     => $category['name'] . ($this->config->get('configblog_article_count') ? ' (' . $this->model_blog_article->getTotalArticles($filter_data) . ')' : ''),
					'children' => $children_data,
					'column'   => $category['column'] ? $category['column'] : 1,
					'href'     => $this->url->link('blog/category', 'blog_category_id=' . $category['blog_category_id'])
				);
			}
		}
		
		return $this->load->view('blog/menu', $data);
	}
}