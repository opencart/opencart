<?php
namespace Opencart\Catalog\Controller\Common;
/**
 * Class Currency
 *
 * Can be called from $this->load->controller('common/currency');
 *
 * @package Opencart\Catalog\Controller\Common
 */
class Currency extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return string
	 */
	public function index(): string {
		$this->load->language('common/currency');

		$data['action'] = $this->url->link('common/currency.save', 'language=' . $this->config->get('config_language'));

		$data['code'] = $this->session->data['currency'];

		$data['currencies'] = [];

		$this->load->model('localisation/currency');

		$results = $this->model_localisation_currency->getCurrencies();

		foreach ($results as $result) {
			if ($result['status']) {
				$data['currencies'][$result['code']] = $result;
			}
		}

		$data['title'] = $data['currencies'][$result['code']]['title'];
		$data['symbol_left'] = $data['currencies'][$result['code']]['symbol_left'];
		$data['symbol_right'] = $data['currencies'][$result['code']]['symbol_right'];

		$url_data = $this->request->get;

		if (isset($url_data['route'])) {
			$route = $url_data['route'];
		} else {
			$route = $this->config->get('action_default');
		}

		unset($url_data['route']);
		unset($url_data['_route_']);

		$url = '';

		if ($url_data) {
			$url .= '&' . urldecode(http_build_query($url_data, '', '&'));
		}

		$data['redirect'] = $this->url->link($route, $url);

		return $this->load->view('common/currency', $data);
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		if (isset($this->request->post['code'])) {
			$this->session->data['currency'] = $this->request->post['code'];

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
		}

		$option = [
			'expires'  => time() + 60 * 60 * 24 * 30,
			'path'     => '/',
			'SameSite' => 'Lax'
		];

		setcookie('currency', $this->session->data['currency'], $option);

		if (isset($this->request->post['redirect'])) {
			$redirect = urldecode(html_entity_decode($this->request->post['redirect'], ENT_QUOTES, 'UTF-8'));
		} else {
			$redirect = '';
		}

		if ($redirect && str_starts_with($redirect, $this->config->get('config_url'))) {
			$this->response->redirect($redirect);
		} else {
			$this->response->redirect($this->url->link($this->config->get('action_default'), 'language=' . $this->config->get('config_language')));
		}
	}
}
