<?php
class ControllerStep4 extends Controller {
	public function index() {
		$this->document->setTitle($this->language->get('heading_step_4'));

		$data['heading_step_4'] = $this->language->get('heading_step_4');
		$data['heading_step_4_small'] = $this->language->get('heading_step_4_small');

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

	public function extensions() {
		$defaults = array(
			CURLOPT_POST => 1,
			CURLOPT_HEADER => 0,
			CURLOPT_URL => 'http://www.opencart.com/index.php?route=extension/json/extensions',
			CURLOPT_USERAGENT => "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1",
			CURLOPT_FRESH_CONNECT => 1,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_FORBID_REUSE => 1,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_POSTFIELDS => array(),
		);

		$ch = curl_init();
		curl_setopt_array($ch, ($defaults));
		$result = curl_exec($ch);
		curl_close($ch);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput($result);
	}
}