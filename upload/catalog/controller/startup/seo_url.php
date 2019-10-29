<?php
class ControllerStartupSeoUrl extends Controller {
	private $regex   = array();
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
			//$this->regex[$result['key']][] = '/' . $result['regex'] . '/';
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
		/*
		$url = '';

		$url_info = parse_url(str_replace('&amp;', '&', $link));

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

		// Start replacing the URL query
		$url_data = array();

		$path = '';

		parse_str($url_info['query'], $url_data);

		foreach ($url_data as $key => $value) {
			$url_key = $key . '=' . $value;

			if (isset($this->regex[$key])) {
				foreach ($this->regex[$key] as $regex) {
					echo $regex . "\n";

					$matches = array();

					if (preg_match($regex, $value, $matches)) {
						print_r($matches);

						array_shift($matches);

						foreach ($matches as $match) {
							print_r($match);

							$path .= '/' . $match[0];

							if (!isset($this->keyword[$url_key])) {
								$this->keyword[$url_key] = $this->model_design_seo_url->getKeywordByQuery($url_key);
							}


							if ($this->keyword[$url_key]) {
								$path .= '/' . $this->keyword[$url_key];

								unset($url_data[$key]);
							}


						}


					}
				}
			}


			echo $path . "\n";
		}

		$query = '';

		//foreach ($data as $key => $value) {
		//	$query .= '&' . rawurlencode((string)$key) . '=' . rawurlencode(is_array($value) ? http_build_query($value) : (string)$value);
		//}

		//if ($query) {
		//	$query = '?' . str_replace('&', '&amp;', trim(str_replace('%2F', '/', $query), '&'));
		//}

		/*
		foreach ($matches as $match) {
			echo $match . "\n";

			if (!isset($this->keyword[$match])) {
				$this->keyword[$match] = $this->model_design_seo_url->getKeywordByQuery($match);

				$url .= '/' . $this->keyword[$match];

			}

			if ($this->keyword[$match]) {

			}

			parse_str($match, $remove);

			// Remove all the matched url elements
			foreach (array_keys($remove) as $key) {
				//echo $key . "\n";

				if (isset($data[$key])) {
					unset($data[$key]);
				}
			}
		}


		if ($url_info['path']) {
			$url .= str_replace('/index.php', '', $url_info['path']);
		}
*/

		return $link;
	}

}