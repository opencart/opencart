<?php
class ControllerCommonLanguage extends Controller {
	public function index() {
		$this->load->language('common/language');

		$data['code'] = $this->config->get('config_language_code');

		$this->load->model('localisation/language');

		$data['languages'] = array();

		$results = $this->model_localisation_language->getLanguages();

		if (!isset($this->request->get['route'])) {
			$route = 'common/home';
		} else {
			$route = $this->request->get['route'];
		}

		$url_data = $this->request->get;

		unset($url_data['_route_'], $url_data['route'], $url_data['language']);

		// Product context changes breadcrumbs, not product identity.
		if ($route == 'product/product') {
			unset($url_data['path'], $url_data['manufacturer_id']);
		}

		foreach ($results as $result) {
			if ($result['status']) {
				$language_url_data = $url_data;
				$language_url_data['language'] = $result['code'];

				$data['languages'][] = array(
					'name' => $result['name'],
					'code' => $result['code'],
					'href' => $this->url->link($route, $language_url_data, $this->request->server['HTTPS'])
				);
			}
		}

		return $this->load->view('common/language', $data);
	}

	public function language() {
		if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], $this->config->get('config_url')) === 0 || strpos($this->request->post['redirect'], $this->config->get('config_ssl')) === 0)) {
			$redirect = $this->request->post['redirect'];
		} else {
			$redirect = $this->url->link('common/home');
		}

		// Keep the POST endpoint compatible with third-party themes while making
		// the selected language part of the redirect URL.
		if (isset($this->request->post['code']) && is_string($this->request->post['code'])) {
			$this->load->model('localisation/language');

			$languages = $this->model_localisation_language->getLanguages();

			if (isset($languages[$this->request->post['code']])) {
				$redirect = $this->setLanguage($redirect, $this->request->post['code']);
			}
		}

		$this->response->redirect($redirect);
	}

	private function setLanguage($link, $code) {
		$url_info = parse_url(str_replace('&amp;', '&', $link));
		$data = array();

		if (isset($url_info['query'])) {
			parse_str($url_info['query'], $data);
		}

		if ($code == $this->config->get('config_language')) {
			unset($data['language']);
		} else {
			$data['language'] = $code;
		}

		$redirect = $url_info['scheme'] . '://' . $url_info['host'];

		if (isset($url_info['port'])) {
			$redirect .= ':' . $url_info['port'];
		}

		$redirect .= $url_info['path'];

		if ($data) {
			$redirect .= '?' . http_build_query($data);
		}

		if (isset($url_info['fragment'])) {
			$redirect .= '#' . $url_info['fragment'];
		}

		return $redirect;
	}
}
