<?php
class ControllerStartupSeoUrl extends Controller {
	private $regex = array();
	private $keyword = array();

	public function index() {
		// Add rewrite to URL class
		if ($this->config->get('config_seo_url')) {
			$this->url->addRewrite($this);
		}

		$this->load->model('design/seo_url');
		$this->load->model('design/seo_regex');

		// Load all regexes in the var so we are not accessing the db so much.
		$results = $this->model_design_seo_regex->getSeoRegexes();

		foreach ($results as $result) {
			$this->regex[] = $result['regex'];
		}

		// Decode URL
		if (isset($this->request->get['_route_'])) {
			$parts = explode('/', $this->request->get['_route_']);

			// remove any empty arrays from trailing
			if (utf8_strlen(end($parts)) == 0) {
				array_pop($parts);
			}

			foreach ($parts as $part) {
				$results = $this->model_design_seo_url->getSeoUrlsByKeyword($part);

				if ($results) {
					foreach ($results as $result) {
						$data = array();

						// Push additional query string vars into GET data
						parse_str($result['push'], $data);

						foreach ($data as $key => $value) {
							$this->request->get[$key] = $value;
						}
					}
				} else {
					$this->request->get['route'] = 'error/not_found';

					break;
				}
			}
		}
	}

	public function rewrite($link) {
		$url = '';

		$url_info = parse_url(str_replace('&amp;', '&', $link));

		$data = array();

		parse_str($url_info['query'], $data);

		foreach ($this->regex as $regex) {
			$matches = array();

			$regex = preg_quote($regex, '/');

			if (preg_match('/' . $regex . '/', $url_info['query'], $matches)) {
				array_shift($matches);

				foreach ($matches as $match) {



					if (!$this->keyword[$match]) {
						$results = $this->model_design_seo_url->getSeoUrlsByQuery($match);

						if ($results) {
							foreach ($results as $result) {
								if (!empty($result['keyword'])) {
									$url .= '/' . $result['keyword'];
								}
							}

							parse_str($match, $remove);

							// Remove all the matched url elements
							foreach (array_keys($remove) as $key) {
								if (isset($data[$key])) {
									unset($data[$key]);
								}
							}
						}
					}







				}
			}
		}

		if ($url) {
			$query = '';

			if ($data) {
				foreach ($data as $key => $value) {
					$query .= '&' . rawurlencode((string)$key) . '=' . rawurlencode(is_array($value) ? http_build_query($value) : (string)$value);
				}

				if ($query) {
					$query = '?' . str_replace('&', '&amp;', trim(str_replace('%2F', '/', $query), '&'));
				}
			}

			return $url_info['scheme'] . '://' . $url_info['host'] . (isset($url_info['port']) ? ':' . $url_info['port'] : '') . str_replace('/index.php', '', $url_info['path']) . $url . $query;
		} else {
			return $link;
		}
	}
}