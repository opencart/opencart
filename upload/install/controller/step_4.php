<?php
class ControllerStep4 extends Controller {
	public function index() {
		$this->document->setTitle($this->language->get('heading_step_4'));

		$data['heading_step_4'] = $this->language->get('heading_step_4');
		
		$data['text_license'] = $this->language->get('text_license');
		$data['text_installation'] = $this->language->get('text_installation');
		$data['text_configuration'] = $this->language->get('text_configuration');
		$data['text_finished'] = $this->language->get('text_finished');	
		$data['text_congratulation'] = $this->language->get('text_congratulation');	
		$data['text_forget'] = $this->language->get('text_forget');	
		$data['text_shop'] = $this->language->get('text_shop');	
		$data['text_login'] = $this->language->get('text_login');	
		
		$data['footer'] = $this->load->controller('footer');
		$data['header'] = $this->load->controller('header');

		$this->response->setOutput($this->load->view('step_4.tpl', $data));
	}
}