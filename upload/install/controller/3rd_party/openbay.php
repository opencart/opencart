<?php
class Controller3rdPartyOpenbay extends Controller {
	public function index() {
		$this->language->load('3rd_party/openbay');
		
		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_openbay'] = $this->language->get('text_openbay');
		$data['text_ebay'] = $this->language->get('text_ebay');
		$data['text_amazon'] = $this->language->get('text_amazon');

		$data['button_register'] = $this->language->get('button_register');
		$data['button_register_eu'] = $this->language->get('button_register_eu');
		$data['button_register_us'] = $this->language->get('button_register_us');
		$data['button_back'] = $this->language->get('button_back');

		$data['back'] = $this->url->link('install/step_4');

		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('3rd_party/openbay', $data));
	}
}