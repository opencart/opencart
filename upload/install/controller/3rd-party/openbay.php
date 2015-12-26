<?php
class Controller3rdPartyOpenbay extends Controller {
	public function index() {
		$this->document->setTitle($this->language->get('heading_openbay'));

		$data['heading_openbay'] = $this->language->get('heading_openbay');
		$data['heading_openbay_small'] = $this->language->get('heading_openbay_small');

		$data['text_ebay_about'] = $this->language->get('text_ebay_about');
		$data['text_amazon_about'] = $this->language->get('text_amazon_about');

		$data['button_register'] = $this->language->get('button_register');
		$data['button_register_eu'] = $this->language->get('button_register_eu');
		$data['button_register_us'] = $this->language->get('button_register_us');
		$data['button_back'] = $this->language->get('button_back');

		$data['back'] = $this->url->link('install/step_4');

		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('3rd-party/openbay', $data));
	}
}