<?php
// *	@copyright	OPENCART.PRO 2011 - 2017.
// *	@forum	http://forum.opencart.pro
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerExtensionFeedBlogSitemap extends Controller {
	public function index() {
		if ($this->config->get('blog_sitemap_status')) {
			$output  = '<?xml version="1.0" encoding="UTF-8"?>';
			$output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

			$this->load->model('blog/article');
			$this->load->model('tool/image');

			$articles = $this->model_blog_article->getArticles();

			foreach ($articles as $article) {
				if ($article['image']) {
					$output .= '<url>';
					$output .= '<loc>' . $this->url->link('blog/article', 'article_id=' . $article['article_id']) . '</loc>';
					$output .= '<changefreq>weekly</changefreq>';
					$output .= '<priority>1.0</priority>';
					$output .= '<image:image>';
					$output .= '<image:loc>' . $this->model_tool_image->resize($article['image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height')) . '</image:loc>';
					$output .= '<image:caption>' . $article['name'] . '</image:caption>';
					$output .= '<image:title>' . $article['name'] . '</image:title>';
					$output .= '</image:image>';
					$output .= '</url>';
				}
			}

			$this->load->model('blog/category');

			$output .= $this->getCategories(0);

			$output .= '</urlset>';

			$this->response->addHeader('Content-Type: application/xml');
			$this->response->setOutput($output);
		}
	}

	protected function getCategories($parent_id, $current_path = '') {
		$output = '';

		$results = $this->model_blog_category->getCategories($parent_id);

		foreach ($results as $result) {
			if (!$current_path) {
				$new_path = $result['blog_category_id'];
			} else {
				$new_path = $current_path . '_' . $result['blog_category_id'];
			}

			$output .= '<url>';
			$output .= '<loc>' . $this->url->link('blog/category', 'blog_category_id=' . $new_path) . '</loc>';
			$output .= '<changefreq>weekly</changefreq>';
			$output .= '<priority>0.7</priority>';
			$output .= '</url>';

			$output .= $this->getCategories($result['blog_category_id'], $new_path);
		}

		return $output;
	}
}
