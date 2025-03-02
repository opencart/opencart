<?php
namespace Opencart\Catalog\Controller\Common;
/**
 * Class Language
 *
 * Can be called from $this->load->controller('common/language');
 *
 * @package Opencart\Catalog\Controller\Common
 */
class Language extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return string
	 */
	public function index(): string {
		$this->load->language('common/language');

		$data['action'] = $this->url->link('common/language.save', 'language=' . $this->config->get('config_language'));

		$data['code'] = $this->request->get['language'] ?? $this->config->get('config_language');

		$data['languages'] = [];

		$this->load->model('localisation/language');

		$results = $this->model_localisation_language->getLanguages();

		foreach ($results as $result) {
			if ($result['status']) {
				$data['languages'][$result['code']] = $result;
			}
		}

		$code = $data['code'];

		$data['name'] = $data['languages'][$code]['name'];
		$data['image'] = $data['languages'][$code]['image'];

		// Build the url
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
			$url .= '&' . urldecode(http_build_query($url_data, '', '&'));
		}

		// Make sure we are not using SEO urls
		$data['redirect'] = HTTP_SERVER . 'index.php?route=' . $route . $url;

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

		$keys = [
			'code',
			'redirect'
		];

		foreach ($keys as $key) {
			if (!isset($this->request->post[$key])) {
				$this->request->post[$key] = '';
			}
		}

		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguageByCode($this->request->post['code']);

		if (!$language_info) {
			$json['error'] = $this->language->get('error_language');
		}

		if (!$json) {
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);

			if ($this->request->post['redirect']) {
				$redirect = urldecode(html_entity_decode($this->request->post['redirect'], ENT_QUOTES, 'UTF-8'));

				// Build the url
				$url_info = parse_url($redirect);

				parse_str($url_info['query'], $query);

				if (isset($query['route'])) {
					$route = $query['route'];
				} else {
					$route = $this->config->get('action_default');
				}

				unset($query['route']);

				$query['language'] = $this->request->post['code'];

				$redirect = $this->url->link($route, $query, true);
			} else {
				$redirect = '';
			}

			if (str_starts_with($redirect, $this->config->get('config_url'))) {
				$json['redirect'] = $redirect;
			} else {
				$json['redirect'] = $this->url->link($this->config->get('action_default'), 'language=' . $this->request->post['code'], true);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
