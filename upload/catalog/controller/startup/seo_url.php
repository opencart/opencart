<?php
class ControllerStartupSeoUrl extends Controller {
	private $profile = array();
	private $query = array();
	private $keyword = array();

	public function index() {
		// Add rewrite to URL class
		if ($this->config->get('config_seo_url')) {
			$this->url->addRewrite($this);
		}

		$this->load->model('design/seo_profile');
		$this->load->model('design/seo_url');

		// Load all regexes in the var so we are not accessing the db so much.
		//$results = $this->model_design_seo_regex->getSeoRegexes();

		//foreach ($results as $result) {
		//	$this->regex[$result['key']][] = $result;
		//}
		/*
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
					//$seo_url_info = $this->model_design_seo_url->getSeoProfilesKey($part);

					$data = array();

					// Push additional query string vars into GET data
					parse_str(html_entity_decode($seo_url_info['push'], ENT_QUOTES, 'UTF-8'), $data);

					foreach ($data as $key => $value) {
						$this->request->get[$key] = $value;
					}
				}
			}
		}
		*/
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
		$path = $url_info['path'];

		if ($url_info['query']) {
			$query = array();

			// Parse the query into its separate parts
			parse_str($url_info['query'], $query);

			foreach ($query as $key => $value) {
				if (!isset($this->profile[$key])) {
					$this->profile[$key] = $this->model_design_seo_profile->getSeoProfilesByKey($key);
				}

				foreach ($this->profile[$key] as $result) {
					$match = array();

					$regex = html_entity_decode($result['regex'], ENT_QUOTES, 'UTF-8');

					if (preg_match($regex, $value, $match)) {
						echo $key;

						$keyword = $this->model_design_seo_url->getKeyword($key, $match[0]);

						if ($keyword) {


							$this->query[] = array(
								'keyword'    => $keyword,
								'remove' 	 => explode(',', $result['remove']),
								'sort_order' => $result['sort_order']
							);



						}
					}
				}
			}
		}

		$sort_order = array();

		foreach ($this->query as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $this->query);

		/*
		//$path .= '/' . $keyword;

		foreach ($query as $key => $value) {
			if ($result['remove']) {
				$keys = $result['remove'];

				foreach ($keys as $key) {
					unset($query[$key]);
				}
			}
		}
		*/

		if ($path) {
			if ($query) {
				$url .= $path . '/';
			} else {
				$url .= str_replace('/index.php', '', $path) . '/';
			}
		}

		// Rebuild the URL query
		if ($query) {
			$url .= '?' . http_build_query($query);
		}

		return $url;
	}
}