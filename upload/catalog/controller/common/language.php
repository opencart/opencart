<?php
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerCommonLanguage extends Controller {
	public function index() {
		$this->load->language('common/language');

		$data['action'] = $this->url->link('common/language/language', '', $this->request->server['HTTPS']);

		$data['code'] = $this->session->data['language'];

		$this->load->model('localisation/language');

		$data['languages'] = array();

		$results = $this->model_localisation_language->getLanguages();

		foreach ($results as $result) {
			if ($result['status']) {
				$data['languages'][] = array(
					'name' => $result['name'],
					'code' => $result['code']
				);
			}
		}

		if (!isset($this->request->get['route'])) {
			
			if($this->config->get('config_seo_pro')){ 
				$redirect_data = ['route' => 'common/home', 'url' => '', 'protocol' => $this->request->server['HTTPS']];
				$data['redirect'] = base64_encode(json_encode($redirect_data));
			} else {
				$data['redirect'] = $this->url->link('common/home');
			};
			
		} else {
			$url_data = $this->request->get;

			unset($url_data['_route_']);

			$route = $url_data['route'];

			unset($url_data['route']);

			$url = '';

			if ($url_data) {
				$url = '&' . urldecode(http_build_query($url_data, '', '&'));
			}
			
			if($this->config->get('config_seo_pro')){ 
				$redirect_data = ['route' => $route, 'url' => $url, 'protocol' => $this->request->server['HTTPS']];
				$data['redirect'] = base64_encode(json_encode($redirect_data));
			} else {
				$data['redirect'] = $this->url->link($route, $url, $this->request->server['HTTPS']);
			};
			
		}

		return $this->load->view('common/language', $data);
	}

	public function language() {
		if($this->config->get('config_seo_pro'))
			$this->seo_language();
			
		if (isset($this->request->post['code'])) {
			$this->session->data['language'] = $this->request->post['code'];
		}

		if (isset($this->request->post['redirect'])) {
			$this->response->redirect($this->request->post['redirect']);
		} else {
			$this->response->redirect($this->url->link('common/home'));
		}
	}
	
	private function seo_language() {
		if (isset($this->request->post['code'])) {
			$this->session->data['language'] = $this->request->post['code'];
			$languages = $this->model_localisation_language->getLanguages();
			if (isset($languages[$this->request->post['code']])) {
				$this->config->set('config_language_id', $languages[$this->request->post['code']]['language_id']);	
			}
		}

		if (isset($this->request->post['redirect'])) {
			$redirect = $this->request->post['redirect'];
			$redirect_data = json_decode(base64_decode($redirect), true);
			extract($redirect_data);
			if(isset($route)&& isset($url) && isset($protocol)) {
				$redirect_url = $this->url->link($route, $url, $protocol);
			} else {
				$redirect_url = $this->url->link('common/home');
			}
			$this->response->redirect($redirect_url);
		} else {
			$this->response->redirect($this->url->link('common/home'));
		}
	}
}