<?php
namespace Opencart\Admin\Controller\Common;
/**
 * Class Language
 *
 * Can be loaded using $this->load->controller('common/language');
 *
 * @package Opencart\Admin\Controller\Common
 */
class Language extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return string
	 */
	public function index(): string {
		// Languages
		$data['languages'] = [];

		$this->load->model('localisation/language');

		$results = $this->model_localisation_language->getLanguages();

		foreach ($results as $result) {
			$data['languages'][] = [
				'name'  => $result['name'],
				'code'  => $result['code'],
				'image' => $result['image']
			];
		}

		if (isset($this->request->cookie['language'])) {
			$data['code'] = $this->request->cookie['language'];
		} else {
			$data['code'] = $this->config->get('config_language_admin');
		}

		// Redirect
		$url_data = $this->request->get;

		if (isset($url_data['route'])) {
			$route = $url_data['route'];
		} else {
			$route = 'common/dashboard';
		}

		unset($url_data['route']);

		$url = '';

		if ($url_data) {
			$url .= '&' . urldecode(http_build_query($url_data));
		}

		$data['redirect'] = $this->url->link($route, $url);

		$data['user_token'] = $this->session->data['user_token'];

		return $this->load->view('common/language', $data);
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('common/language');

		$json = [];

		if (isset($this->request->post['code'])) {
			$code = (string)$this->request->post['code'];
		} else {
			$code = '';
		}

		if (isset($this->request->post['redirect'])) {
			$redirect = html_entity_decode((string)$this->request->post['redirect'], ENT_QUOTES, 'UTF-8');
		} else {
			$redirect = '';
		}

		// Language
		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguageByCode($code);

		if (!$language_info) {
			$json['error'] = $this->language->get('error_language');
		}

		if (!$json) {
			$option = [
				'expires'  => time() + 60 * 60 * 24 * 365 * 10,
				'path'     => $this->config->get('session_path'),
				'secure'   => $this->request->server['HTTPS'],
				'SameSite' => $this->config->get('config_session_samesite')
			];

			setcookie('language', $code, $option);

			if ($redirect && str_starts_with($redirect, $this->config->get('config_url'))) {
				$json['redirect'] = $redirect;
			} else {
				$json['redirect'] = $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
