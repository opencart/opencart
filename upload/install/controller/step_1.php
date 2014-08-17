<?php
class ControllerStep1 extends Controller {
	public function index() {
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$this->response->redirect($this->url->link('step_2'));
		}

		$this->document->setTitle($this->language->get('heading_step_1'));

		$data['heading_step_1'] = $this->language->get('heading_step_1');
		$data['heading_step_1_small'] = $this->language->get('heading_step_1_small');

		$data['text_license'] = $this->language->get('text_license');
		$data['text_installation'] = $this->language->get('text_installation');
		$data['text_configuration'] = $this->language->get('text_configuration');
		$data['text_finished'] = $this->language->get('text_finished');
		$data['text_terms'] = $this->language->get('text_terms');

		$data['button_continue'] = $this->language->get('button_continue');

		$data['action'] = $this->url->link('step_1');

		$data['footer'] = $this->load->controller('footer');
		$data['header'] = $this->load->controller('header');

		$this->response->setOutput($this->load->view('step_1.tpl', $data));
	}
}