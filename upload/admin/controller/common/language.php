<?php
namespace Opencart\Admin\Controller\Common;
class Language extends \Opencart\System\Engine\Controller {
	public function index(): string {
		$data['action'] = $this->url->link('common/language|save', 'user_token=' . $this->session->data['user_token']);

		// Languages
		$data['languages'] = [];

		$this->load->model('localisation/language');

		$results = $this->model_localisation_language->getLanguages();

		foreach ($results as $result) {
			if (!$result['extension']) {
				$image = HTTP_SERVER;
			} else {
				$image = HTTP_CATALOG . 'extension/' . $result['extension'] . '/admin/';
			}

			$data['languages'][] = [
				'name'  => $result['name'],
				'code'  => $result['code'],
				'image' => $image . 'language/' . $result['code'] . '/' . $result['code'] . '.png'
			];
		}

		$url_data = $this->request->get;

		if (isset($url_data['route'])) {
			$route = $url_data['route'];
		} else {
			$route = $this->config->get('action_default');
		}

		unset($url_data['route']);
		unset($url_data['_route_']);
		unset($url_data['language']);

		$url = '';

		if ($url_data) {
			$url = '&' . urldecode(http_build_query($url_data));
		}

		$data['redirect'] = $this->url->link($route, 'language=' . $result['code'] . $url, true);

		if (isset($this->session->data['language'])) {
			$data['code'] = $this->session->data['language'];
		} else {
			$data['code'] = $this->config->get('config_language');
		}

		return $this->load->view('common/language', $data);
	}

	public function save(): void {
		if (isset($this->request->get['code'])) {
			$code = $this->request->get['code'];
		} else {
			$code = $this->config->get('config_language');
		}

		if (isset($this->request->get['redirect'])) {
			$redirect =  htmlspecialchars_decode($this->request->get['redirect'], ENT_COMPAT);
		} else {
			$redirect = '';
		}

		if ($redirect && substr($redirect, 0, strlen($this->config->get('config_url'))) == $this->config->get('config_url')) {
			$this->response->redirect($redirect);
		} else {
			$this->response->redirect($this->url->link($this->config->get('action_default'), 'language=' . $code));
		}
	}
}
