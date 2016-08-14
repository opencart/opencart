<?php
class ControllerApiCurrency extends Controller {
	public function index() {
		$this->load->language('api/currency');

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('localisation/currency');
			
			$currency_info = $this->model_localisation_currency->getCurrencyByCode($this->request->post['currency']);
			
			if ($currency_info) {
				$this->currency->set($this->request->post['currency']);
	
				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);

				$json['success'] = $this->language->get('text_success');
			} else {
				$json['error'] = $this->language->get('error_currency');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}