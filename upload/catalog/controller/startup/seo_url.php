<?php
namespace Opencart\Application\Controller\Startup;
class SeoUrl extends \Opencart\System\Engine\Controller {
	public function index() {
		// Add rewrite to URL class
		if ($this->config->get('config_seo_url')) {
			$this->url->addRewrite($this);
		}

		$this->load->model('design/seo_profile');
		$this->load->model('design/seo_url');

		// Decode URL
		if (isset($this->request->get['_route_'])) {
			$parts = explode('/', $this->request->get['_route_']);

			// remove any empty arrays from trailing
			if (utf8_strlen(end($parts)) == 0) {
				array_pop($parts);
			}

			foreach ($parts as $part) {
				$seo_url_info = $this->model_design_seo_url->getSeoUrlByKeyword($part);

				if ($seo_url_info) {
					$this->request->get[$seo_url_info['key']] = html_entity_decode($seo_url_info['value'], ENT_QUOTES, 'UTF-8');

					$results = $this->model_design_seo_profile->getSeoProfilesByKey($seo_url_info['key']);

					foreach ($results as $result) {
						// Push additional query string vars into GET data
						parse_str(html_entity_decode($result['push'], ENT_QUOTES, 'UTF-8'), $push);

						$this->request->get = array_merge($this->request->get, $push);
					}
				}
			}
		}
	}

	public function rewrite($link) {
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

		// Start changing the URL query into a path
		$path_data = [];

		$query = [];

		// Parse the query into its separate parts
		parse_str($url_info['query'], $query);

		foreach ($query as $key => $value) {
			$results = $this->model_design_seo_profile->getSeoProfilesByKey($key);

			foreach ($results as $result) {
				$match = [];

				$regex = html_entity_decode($result['regex'], ENT_QUOTES, 'UTF-8');

				if (preg_match($regex, html_entity_decode($value, ENT_QUOTES, 'UTF-8'), $match)) {
					$keyword = $this->model_design_seo_url->getKeywordByKeyValue($key, $match[0]);

					if ($keyword) {
						$path_data[] = [
							'keyword'    => $keyword,
							'remove'     => $result['remove'],
							'sort_order' => $result['sort_order']
						];
					}
				}
			}
		}

		$sort_order = [];

		foreach ($path_data as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $path_data);

		// Build the path
		$url .= str_replace('/index.php', '', $url_info['path']);

		foreach ($path_data as $result) {
			$url .= '/' . $result['keyword'];

			if ($result['remove']) {
				$keys = explode(',', $result['remove']);

				foreach ($keys as $key) {
					unset($query[$key]);
				}
			}
		}

		// Rebuild the URL query
		if ($query) {
			$url .= '?' . str_replace('%2F', '/', http_build_query($query));
		}

		return $url;
	}
}