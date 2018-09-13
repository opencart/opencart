<?php
class ControllerCommonCurrency extends Controller {
	public function index() {
		$this->load->language('common/currency');

		$data['action'] = $this->url->link('common/currency/currency', 'language=' . $this->config->get('config_language'));

		$data['code'] = $this->session->data['currency'];

		$url_data = $this->request->get;

		if (isset($url_data['route'])) {
			$route = $url_data['route'];
		} else {
			$route = $this->config->get('action_default');
		}

		unset($url_data['_route_']);
		unset($url_data['route']);

		$data['currencies'] = array();

		$this->load->model('localisation/currency');

		$results = $this->model_localisation_currency->getCurrencies();

		foreach ($results as $result) {
			if ($result['status']) {
				$data['currencies'][] = array(
					'title'        => $result['title'],
					'code'         => $result['code'],
					'symbol_left'  => $result['symbol_left'],
					'symbol_right' => $result['symbol_right']
				);
			}
		}

		$url = '';

		if ($url_data) {
			$url = '&' . urldecode(http_build_query($url_data, '', '&'));
		}

		$data['redirect'] = $this->url->link($route, 'language=' . $this->config->get('config_language') . $url);

		return $this->load->view('common/currency', $data);
	}

	public function currency() {
		if (isset($this->request->post['code'])) {
			$this->session->data['currency'] = $this->request->post['code'];
		
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
		}
		
		if (isset($this->request->post['redirect']) && substr($this->request->post['redirect'], 0, strlen($this->config->get('config_url'))) == $this->config->get('config_url')) {
			$this->response->redirect(str_replace('&amp;', '&', $this->request->post['redirect']));
		} else {
			$this->response->redirect($this->url->link($this->config->get('action_default')));
		}
	}
}