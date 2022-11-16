<?php
namespace Opencart\Catalog\Controller\Startup;
class SeoUrl extends \Opencart\System\Engine\Controller {
	private int $language_id = 0;

	public function index(): void {
		// Add rewrite to URL class
		if ($this->config->get('config_seo_url')) {
			$this->url->addRewrite($this);
		}

		// Set language ID
		if (isset($this->request->get['language'])) {
			$code = $this->request->get['language'];
		} else {
			$code = '';
		}

		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguageByCode($code);

		if ($language_info) {
			$this->language_id = $language_info['language_id'];
		}

		$this->load->model('design/seo_url');

		// Decode URL
		if (isset($this->request->get['_route_'])) {
			$parts = explode('/', $this->request->get['_route_']);

			// remove any empty arrays from trailing
			if (oc_strlen(end($parts)) == 0) {
				array_pop($parts);
			}

			foreach ($parts as $part) {
				$seo_url_info = $this->model_design_seo_url->getSeoUrlByKeyword($part, $this->language_id);

				if ($seo_url_info) {
					$this->request->get[$seo_url_info['key']] = html_entity_decode($seo_url_info['value'], ENT_QUOTES, 'UTF-8');
				}
			}
		}
	}

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
			[$key, $value] = explode('=', $part);

			$result = $this->model_design_seo_url->getSeoUrlByKeyValue((string)$key, (string)$value, $this->language_id);

			if ($result) {
				$paths[] = $result;

				unset($query[$key]);
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

		// Rebuild the URL query
		if ($query) {
			$url .= '?' . str_replace(['%2F'], ['/'], http_build_query($query));
		}

		return $url;
	}
}