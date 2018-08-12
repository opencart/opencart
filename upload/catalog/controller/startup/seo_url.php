<?php
class ControllerStartupSeoUrl extends Controller {
	private $regex = array();

	public function index() {
		// Add rewrite to url class
		if ($this->config->get('config_seo_url')) {
			$this->url->addRewrite($this);
		}

		// Load all regexes in the var so we are not accessing the db so much.
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_regex ORDER BY sort_order ASC");

		$this->regex = $query->rows;

		// Decode URL
		if (isset($this->request->get['_route_'])) {
			$parts = explode('/', $this->request->get['_route_']);

			// remove any empty arrays from trailing
			if (utf8_strlen(end($parts)) == 0) {
				array_pop($parts);
			}

			foreach ($parts as $part) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE keyword = '" . $this->db->escape($part) . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

				if ($query->num_rows) {
					foreach ($query->rows as $result) {
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

		parse_str($url_info['query'], $data);

		foreach ($this->regex as $result) {
			if (preg_match('/' . $result['regex'] . '/', $url_info['query'], $matches)) {
				array_shift($matches);

				foreach ($matches as $match) {
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE `query` = '" . $this->db->escape($match) . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

					if ($query->num_rows) {
						foreach ($query->rows as $seo) {
							if (!empty($seo['keyword'])) {
								$url .= '/' . $seo['keyword'];
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
