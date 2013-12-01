<?php
class ControllerStep4 extends Controller {
	public function index() {
		$data['footer'] = $this->load->controller('footer');
		$data['header'] = $this->load->controller('header');

		$this->response->setOutput($this->load->view('step_4.tpl', $data));
	}
}