<?php
// *	@copyright	OPENCART.PRO 2011 - 2017.
// *	@forum	http://forum.opencart.pro
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerExtensionFeedBlogSitemapYandex extends Controller {
	public function index() {
		if ($this->config->get('blog_sitemap_status')) {
			$output  = '<?xml version="1.0" encoding="UTF-8"?>';
			$output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

			$this->load->model('blog/article');

			$articles = $this->model_blog_article->getArticles();

			foreach ($articles as $article) {
					$output .= '<url>';
					$output .= '<loc>' . $this->url->link('blog/article', 'article_id=' . $article['article_id']) . '</loc>';
					$output .= '<changefreq>weekly</changefreq>';
					$output .= '<priority>1.0</priority>';
					$output .= '</url>';
					$output .= "\n";
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
