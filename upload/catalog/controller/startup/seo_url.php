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
	private array $regex = [];
	private array $data = [];

	/**
	 * Index
	 *
	 * @return null
	 */
	public function index() {
		// Add rewrite to URL class
		if ($this->config->get('config_seo_url')) {
			$this->load->model('design/seo_url');

			$this->url->addRewrite($this);

			$this->load->model('design/seo_regex');

			$results = $this->model_design_seo_regex->getSeoRegexes();

			foreach ($results as $result) {
				$this->regex[$result['key']][] = $result;
			}

			// Decode URL
			if (!isset($this->request->get['_route_'])) {
				return null;
			}

			$parts = explode('/', trim($this->request->get['_route_'], '/'));

			foreach ($parts as $key => $value) {
				$seo_url_info = $this->model_design_seo_url->getSeoUrlByKeyword($value);

				if ($seo_url_info) {
					$this->request->get[$seo_url_info['key']] = $seo_url_info['value'];

					unset($parts[$key]);

					continue;
				}

				foreach ($results as $result) {
					if (preg_match($result['keyword'], $value)) {
						$this->request->get[$result['key']] = preg_replace($result['keyword'], $result['value'], $value);

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

		// Build the path
		$url .= str_replace('/index.php', '', $url_info['path']);

		// Parse the query into its separate parts
		parse_str($url_info['query'], $query);

		// Start changing the URL query into a path
		$paths = [];

		foreach ($query as $key => $value) {
			$index = $key . '=' . $value;

			// If already found cached query in property use.
			if (isset($this->data[$index])) {
				$paths[] = $this->data[$index];

				unset($query[$key]);

				continue;
			}

			// See if there is an SEO URL setup for the query
			$seo_url_info = $this->model_design_seo_url->getSeoUrlByKeyValue((string)$key, (string)$value);

			if ($seo_url_info) {
				$this->data[$index] = $seo_url_info;

				$paths[] = $seo_url_info;

				unset($query[$key]);

				continue;
			}

			// Run through the regexes to match and replace queries to a path
			if (isset($this->regex[$key])) {
				foreach ((array)$this->regex[$key] as $result) {
					if (preg_match($result['match'], $value)) {
						$this->data[$index] = ['keyword' => preg_replace($result['match'], $result['replace'], $value)] + $result;

						$paths[] = $this->data[$index];

						unset($query[$key]);

						break;
					}
				}
			}
		}

		$sort_order = [];

		foreach ($paths as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $paths);

		foreach ($paths as $result) {
			$url .= '/' . $result['keyword'];
		}

		$url .= '/';

		// Any remaining queries can be added to the end
		if ($query) {
			$url .= '?' . str_replace(['%2F'], ['/'], http_build_query($query));
		}

		return $url;
	}
}
