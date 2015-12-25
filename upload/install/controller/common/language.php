<?php
class ControllerCommonLanguage extends Controller {
	public function index() {
		$data['action'] = $this->url->link('common/language/language', '', $this->request->server['HTTPS']);
		
		if (isset($this->session->data['language'])) {
			$data['code'] = $this->session->data['language'];
		} else {
			$data['code'] = $this->config->get('language.default');
		}
		
		$data['languages'] = array();
		
		$languages = glob(DIR_LANGUAGE . '*', GLOB_ONLYDIR);
		
		foreach ($languages as $language) {
			$data['languages'][] = basename($language);
		}

		if (!isset($this->request->get['route'])) {
			$data['redirect'] = $this->url->link('install/step_1');
		} else {
			$url_data = $this->request->get;

			$route = $url_data['route'];

			unset($url_data['route']);

			$url = '';

			if ($url_data) {
				$url = '&' . urldecode(http_build_query($url_data, '', '&'));
			}

			$data['redirect'] = $this->url->link($route, $url, $this->request->server['HTTPS']);
		}
		
		return $this->load->view('common/language', $data);
	}

	public function language() {
		if (isset($this->request->post['language']) && is_dir(DIR_LANGUAGE . str_replace('../', '/', $this->request->post['language']))) {
			$this->session->data['language'] = $this->request->post['language'];
		}

		if (isset($this->request->post['redirect'])) {
			$this->response->redirect($this->request->post['redirect']);
		} else {
			$this->response->redirect($this->url->link('install/step_1'));
		}
	}	
}