<?php
// *	@copyright	OPENCART.PRO 2011 - 2017.
// *	@forum	http://forum.opencart.pro
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerExtensionModuleBlogPopular extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/blog_popular');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_views'] = $this->language->get('text_views');
		$data['button_more'] = $this->language->get('button_more');

		$this->load->model('blog/article');

		$this->load->model('tool/image');

		$data['articles'] = array();

		$filter_data = array(
			'sort'  => 'p.viewed',
			'order' => 'DESC',
			'start' => 0,
			'limit' => $setting['limit']
		);

		$results = $this->model_blog_article->getArticles($filter_data);

		if ($results) {
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
				}
				
				$data['configblog_review_status'] = $this->config->get('configblog_review_status');

				if ($this->config->get('configblog_review_status')) {
					$rating = $result['rating'];
				} else {
					$rating = false;
				}

				$data['articles'][] = array(
					'article_id'  => $result['article_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('configblog_article_description_length')) . '..',
					'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'viewed'      => $result['viewed'],
					'reviews'    => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
					'rating'      => $rating,
					'href'        => $this->url->link('blog/article', 'article_id=' . $result['article_id']),
				);
			}

			return $this->load->view('extension/module/blog_popular', $data);
		}
	}
}