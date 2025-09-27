<?php
namespace Opencart\Catalog\Controller\Startup;
/**
 * Class SeoUrl
 *
 * @package Opencart\Catalog\Controller\Startup
 */
class SeoUrl extends \Opencart\System\Engine\Controller {
	/**
	 * @var array<string, string>
	 */
	private array $data = [];
	private array $regex = [];

	/**
	 * Index
	 *
	 * @return null
	 */
	public function index() {
		// Add rewrite to URL class
		if ($this->config->get('config_seo_url')) {
			$this->url->addRewrite($this);

			$this->load->model('design/seo_regex');

			$this->regex = $this->model_design_seo_regex->getSeoRegexes();

			$this->load->model('design/seo_url');

			// Decode URL
			if (!isset($this->request->get['_route_'])) {
				return null;
			}

			$parts = explode('/', trim($this->request->get['_route_'], '/'));

			foreach ($parts as $key => $value) {
				$seo_url_info = $this->model_design_seo_url->getSeoUrlByKeyword($value);

				if ($seo_url_info) {
					$this->request->get[$seo_url_info['key']] = html_entity_decode($seo_url_info['value'], ENT_QUOTES, 'UTF-8');

					unset($parts[$key]);

					continue;
				}

				foreach ($this->regex as $result) {
					$query = preg_replace($result['keyword_match'], $result['keyword_replace'], $value, 1, $count);

					if ($count) {


						unset($parts[$key]);

						continue;
					}
				}
			}

			if ($parts) {
				$this->request->get['route'] = $this->config->get('action_error');
			}

			if (!isset($this->request->get['route'])) {
				$this->request->get['route'] = $this->config->get('action_default');
			}

			if ($parts) {
				$this->request->get['route'] = $this->config->get('action_error');
			}
		}
	}

	/**
	 * Rewrite
	 *
	 * @param string $link
	 *
	 * @return string
	 */
	public function rewrite(string $link): string {
		$url_info = parse_url(str_replace('&amp;', '&', $link));

		// Build the url
		$url = '';

		if ($url_info['scheme']) {
			$url .= $url_info['scheme'];
		}

		$url .= '://';

		if ($url_info['host']) {
			$url .= $url_info['host'];
		}

		if (isset($url_info['port'])) {
			$url .= ':' . $url_info['port'];
		}

		parse_str($url_info['query'], $query);

		// Start changing the URL query into a path
		$paths = [];

		// Parse the query into its separate parts
		$parts = explode('&', $url_info['query']);

		foreach ($parts as $part) {
			$pair = explode('=', $part);

			if (!isset($this->data[$part])) {
				if (isset($pair[0])) {
					$key = (string)$pair[0];
				}

				if (isset($pair[1])) {
					$value = (string)$pair[1];
				} else {
					$value = '';
				}

				// See if there is an SEO URL setup for the query
				$seo_url_info = $this->model_design_seo_url->getSeoUrlByKeyValue((string)$key, (string)$value);

				if ($seo_url_info) {
					$this->data[$part] = $seo_url_info;

					unset($query[$key]);

					$paths[] = $this->data[$part];

					continue;
				}

				// Run through the regexes to match and replace queries to a path
				foreach ($this->regex as $result) {
					$keyword = preg_replace($result['query_match'], $result['query_replace'], $part, 1, $count);

					if ($count) {
						$this->data[$part] = [
							'keyword'    => $keyword,
							'sort_order' => $result['sort_order']
						];

						unset($query[$key]);

						$paths[] = $this->data[$part];

						continue;
					}
				}
			}
		}


		$sort_order = [];

		foreach ($paths as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $paths);

		// Build the path
		$url .= str_replace('/index.php', '', $url_info['path']);

		foreach ($paths as $result) {
			$url .= '/' . $result['keyword'];
		}

		$url .= '/';

		// Any remaining queries can be converted
		if ($query) {
			$url .= '?' . str_replace(['%2F'], ['/'], http_build_query($query));
		}

		return $url;
	}
}
