<?php
class ControllerInstallStep1 extends Controller {
	public function index() {
		$this->language->load('install/step_1');
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$this->response->redirect($this->url->link('install/step_2'));
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');
		$data['heading_step_1_small'] = $this->language->get('heading_step_1_small');

		$data['text_license'] = $this->language->get('text_license');
		$data['text_installation'] = $this->language->get('text_installation');
		$data['text_configuration'] = $this->language->get('text_configuration');
		$data['text_finished'] = $this->language->get('text_finished');
		$data['text_terms'] = $this->language->get('text_terms');

		$data['button_continue'] = $this->language->get('button_continue');

		$data['action'] = $this->url->link('install/step_1');

		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('install/step_1', $data));
	}
}