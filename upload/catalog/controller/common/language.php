<?php
class ControllerCommonLanguage extends Controller {
	public function index() {
		$this->load->language('common/language');

		$data['code'] = $this->config->get('config_language');

		$url_data = $this->request->get;

		if (isset($url_data['route'])) {
			$route = $url_data['route'];
		} else {
			$route = 'common/home';
		}

		unset($url_data['_route_']);

		unset($url_data['route']);

		unset($url_data['language']);

		$url = '';

		if ($url_data) {
			$url = '&' . urldecode(http_build_query($url_data, '', '&'));
		}

		$data['languages'] = array();

		$this->load->model('localisation/language');

		$results = $this->model_localisation_language->getLanguages();

		foreach ($results as $result) {
			if ($result['status']) {
				$data['languages'][] = array(
					'name' => $result['name'],
					'code' => $result['code'],
					'href' => $this->url->link('common/language/language', 'language=' . $result['code'] . '&redirect=' . urlencode($this->url->link($route, 'language=' . $result['code'] . $url)))
				);
			}
		}

		return $this->load->view('common/language', $data);
	}

	public function language() {
		if (isset($this->request->get['code'])) {
			//$this->session->data['language'] = $this->request->get['code'];
		}

		if (isset($this->request->get['redirect'])) {
			$this->response->redirect($this->request->get['redirect']);
		} else {
			$this->response->redirect($this->url->link('common/home', 'language=' . $this->request->get['code']));
		}
	}
}