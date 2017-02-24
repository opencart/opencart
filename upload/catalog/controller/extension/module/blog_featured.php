<?php
// *	@copyright	OPENCART.PRO 2011 - 2017.
// *	@forum	http://forum.opencart.pro
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerExtensionModuleBlogFeatured extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/blog_featured');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_views'] = $this->language->get('text_views');
		$data['button_more'] = $this->language->get('button_more');

		$this->load->model('blog/article');

		$this->load->model('tool/image');

		$data['articles'] = array();

		if (!$setting['limit']) {
			$setting['limit'] = 4;
		}

		if (!empty($setting['article'])) {
			$articles = array_slice($setting['article'], 0, (int)$setting['limit']);

			foreach ($articles as $article_id) {
				$article_info = $this->model_blog_article->getArticle($article_id);

				if ($article_info) {
					if ($article_info['image']) {
						$image = $this->model_tool_image->resize($article_info['image'], $setting['width'], $setting['height']);
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
					}
					
					$data['configblog_review_status'] = $this->config->get('configblog_review_status');

					if ($this->config->get('configblog_review_status')) {
						$rating = $article_info['rating'];
					} else {
						$rating = false;
					}

					$data['articles'][] = array(
						'article_id'  => $article_info['article_id'],
						'thumb'       => $image,
						'name'        => $article_info['name'],
						'description' => utf8_substr(strip_tags(html_entity_decode($article_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('configblog_article_description_length')) . '..',
						'rating'      => $rating,
						'date_added'  => date($this->language->get('date_format_short'), strtotime($article_info['date_added'])),
						'viewed'      => $article_info['viewed'],
						'href'        => $this->url->link('blog/article', 'article_id=' . $article_info['article_id'])
					);
				}
			}
		}

		if ($data['articles']) {
			return $this->load->view('extension/module/blog_featured', $data);
		}
	}
}