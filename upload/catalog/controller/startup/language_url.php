<?php
class ControllerStartupLanguageUrl extends Controller {
	private $languages = array();

	public function index() {
		$this->load->model('localisation/language');

		$this->languages = $this->model_localisation_language->getLanguages();

		if (count($this->languages) > 1) {
			$this->url->addRewrite($this);
		}
	}

	public function rewrite($link) {
		$url_info = parse_url(str_replace('&amp;', '&', $link));

		$data = array();

		if (isset($url_info['query'])) {
			parse_str($url_info['query'], $data);
		}

		if (isset($data['language']) && isset($this->languages[$data['language']])) {
			$code = $data['language'];
		} else {
			$code = $this->config->get('config_language_code');
		}

		if ($code == $this->config->get('config_language')) {
			unset($data['language']);
		} else {
			$data['language'] = $code;
		}

		$path = $url_info['path'];

		if (isset($data['route']) && $data['route'] == 'common/home') {
			unset($data['route']);
			$path = preg_replace('~/index\\.php$~', '/', $path);
		}

		$url = $url_info['scheme'] . '://' . $url_info['host'];

		if (isset($url_info['port'])) {
			$url .= ':' . $url_info['port'];
		}

		$url .= $path;

		if ($data) {
			$url .= '?' . str_replace('&', '&amp;', http_build_query($data));
		}

		if (isset($url_info['fragment'])) {
			$url .= '#' . $url_info['fragment'];
		}

		return $url;
	}
}
